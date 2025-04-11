// === GLOBAL.JS ===

// ========== DOM READY ==========
document.addEventListener("DOMContentLoaded", function () {
    // --- Toggle del Sidebar ---
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function (event) {
            event.stopPropagation();
            sidebar.classList.toggle("-translate-x-full");
        });

        document.addEventListener("click", function (event) {
            if (!sidebar.classList.contains("-translate-x-full") &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.add("-translate-x-full");
            }
        });
    }

    // --- Búsqueda en Sidebar ---
    const sidebarSearch = document.getElementById("sidebarSearch");
    const sidebarList = document.getElementById("sidebarList");

    if (sidebarSearch && sidebarList) {
        sidebarSearch.addEventListener("input", function () {
            let filter = normalizeText(sidebarSearch.value);
            let children = Array.from(sidebarList.children);
            let currentHeading = null;
            let currentItems = [];

            function processSection() {
                if (!currentHeading) return;
                let headingText = normalizeText(currentHeading.textContent);
                let headingMatches = headingText.indexOf(filter) > -1;
                let anyItemMatches = false;

                currentItems.forEach((item) => {
                    let itemText = normalizeText(item.textContent);
                    let itemMatches = itemText.indexOf(filter) > -1;
                    if (headingMatches || itemMatches || filter === "") {
                        item.style.display = "";
                        if (itemMatches) anyItemMatches = true;
                    } else {
                        item.style.display = "none";
                    }
                });

                currentHeading.style.display =
                    filter === "" || headingMatches || anyItemMatches ? "" : "none";
            }

            children.forEach((child) => {
                let tag = child.tagName.toLowerCase();
                if (tag === "h2") {
                    processSection();
                    currentHeading = child;
                    currentItems = [];
                    currentHeading.style.display = filter === "" ? "" : "none";
                } else if (tag === "li") {
                    currentItems.push(child);
                } else if (tag === "hr") {
                    child.style.display = filter === "" ? "" : "none";
                }
            });
            processSection();
        });
    }

    // --- Validación de fecha mínima ---
    const fechaEntregaInput = document.getElementById("fecha_entrega");
    if (fechaEntregaInput) {
        const mañana = new Date();
        mañana.setDate(mañana.getDate() + 1);

        const fechaMinima = mañana.toISOString().split("T")[0];
        fechaEntregaInput.setAttribute("min", fechaMinima);

        fechaEntregaInput.addEventListener("change", function () {
            const fechaSeleccionada = new Date(this.value);
            if (fechaSeleccionada < mañana) {
                this.value = "";
                alert("La fecha de descarga debe ser igual o posterior a mañana.");
            }
        });
    }
});

// ========== ALPINE ==========
document.addEventListener("alpine:init", () => {
    Alpine.data("cardManager", () => ({
        openCard: null,
        getFormAction() {
            if (this.openCard) {
                let parts = this.openCard.split("-");
                return "{{ route('solicitudes.update', ['id' => '__ID__']) }}".replace(
                    "__ID__",
                    parts[1]
                );
            }
            return "#";
        },
    }));
});

// ========== DATATABLE ==========
window.initDataTable = function ({ selector, apiUrl, apiToken = null, columns }) {
    console.log("initDataTable ejecutado");
    const tabla = document.querySelector(selector);
    if (!tabla) return console.warn("Tabla no encontrada:", selector);

    $(tabla).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: apiUrl,
            headers: apiToken ? { Authorization: "Bearer " + apiToken } : {},
            dataSrc: "data",
        },
        columns: columns,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
            emptyTable: "No hay registros disponibles",
            zeroRecords: "No se encontraron coincidencias",
        },
    });
};

// ========== PERFIL & 2FA ==========
window.loadProfile = function () {
    const spinner = document.getElementById("profile-spinner");
    spinner.classList.remove("hidden");

    fetch(`${window.apiBaseUrl}/show`, {
        headers: {
            Authorization: `Bearer ${window.apiToken}`,
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.name && data.email) {
                document.getElementById("name").value = data.name;
                document.getElementById("email").value = data.email;
            } else {
                Swal.fire("Error", "No se pudieron cargar los datos del perfil.", "error");
            }
        })
        .catch((err) => {
            console.error(err);
            Swal.fire("Error", "Hubo un problema al cargar el perfil.", "error");
        })
        .finally(() => {
            spinner.classList.add("hidden");
        });
};

window.updateProfile = function (event) {
    event.preventDefault();
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;

    apiRequestWithConfirm({
        method: "PUT",
        url: "update",
        data: { name, email },
        confirmTitle: "¿Guardar cambios?",
        confirmText: "Estás a punto de actualizar tu perfil.",
        successText: "Tu perfil fue modificado.",
        callback: () => location.reload(),
    });
};

window.toggleTwoFactor = function (event) {
    event.preventDefault();
    fetch(`${window.apiBaseUrl}/2FA`, {
        method: "POST",
        headers: {
            Authorization: `Bearer ${window.apiToken}`,
        },
    })
        .then((res) => res.json())
        .then((data) => {
            Swal.fire("2FA", data.message || "2FA actualizado", "success").then(() =>
                location.reload()
            );
        })
        .catch((err) => {
            console.error(err);
            Swal.fire("Error", "Hubo un problema al cambiar 2FA", "error");
        });
};

// ========== POBLAR FORMULARIOS ==========

window.populateFormData = function (data) {
    Object.entries(data).forEach(([key, value]) => {
        if (Array.isArray(value)) {
            const select = document.querySelector(`select[name="${key}[]"], select[name="${key}"]`);
            if (select) {
                select.innerHTML = "";
                value.forEach((item) => {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.name || item.nombre || item.label || `ID ${item.id}`;
                    select.appendChild(option);
                });
            }
        }

        if (typeof value === "object" && value !== null && !Array.isArray(value)) {
            Object.entries(value).forEach(([subkey, subvalue]) => {
                const input = document.querySelector(`[name="${subkey}"]`);
                if (input) input.value = subvalue;

                const multiSelect = document.querySelector(`select[name="${subkey}[]"]`);
                if (multiSelect && Array.isArray(subvalue)) {
                    subvalue.forEach((val) => {
                        const opt = multiSelect.querySelector(`option[value="${val.id}"]`);
                        if (opt) opt.selected = true;
                    });
                }
            });
        }
    });
};

// ========== MANAGEMENT DE FORMULARIOS ==========

window.handleFormSubmit = async function (formId, submitUrl, method = null, token, apiUrl) {
    const form = document.getElementById(formId);
    if (!form) return console.warn(`Formulario con ID ${formId} no encontrado.`);

    const formMethod = method || form.dataset.method || "POST";

    // 1. Cargar datos para el formulario (create o edit)
    try {
        const response = await fetch(apiUrl, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });
        const result = await response.json();
        if (response.ok && result && typeof populateFormData === "function") {
            populateFormData(result);
        }
    } catch (err) {
        console.error("Error al cargar datos del formulario:", err);
    }

    // 2. Escuchar el submit
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // 🔐 Validación de contraseña si aplica
        if (form.querySelector('[name="password"]') && form.querySelector('[name="password_confirmation"]')) {
            const errores = validarPassword(data.password, data.password_confirmation);
            if (errores.length > 0) {
                Swal.fire("Error de validación", errores.join("<br>"), "error");
                return;
            }
        }

        apiRequestWithConfirm({
            method: formMethod,
            url: submitUrl,
            data,
            token,
            callback: () => {
                window.location.href = document.referrer || "/";
            },
        });
    });
};

// ========== VALIDACIONES SI HAY CAMPO PASSWORD ==========

window.validarPassword = function(password, confirmation) {
    const errores = [];

    if (password.length < 8) {
        errores.push("La contraseña debe tener al menos 8 caracteres.");
    }
    if (!/[A-Z]/.test(password)) {
        errores.push("La contraseña debe contener al menos una letra mayúscula.");
    }
    if (!/\d/.test(password)) {
        errores.push("La contraseña debe contener al menos un número.");
    }
    if (!/[@$!%*#?&+.;]/.test(password)) {
        errores.push("La contraseña debe contener al menos un carácter especial (como @, $, !, %, *, #, ?, &, +, . o ;).");
    }
    if (password !== confirmation) {
        errores.push("Las contraseñas no coinciden.");
    }

    return errores;
};

window.apiRequestWithConfirm = function ({
    method,
    url,
    data,
    confirmTitle,
    confirmText,
    successText,
    callback,
    token,
}) {
    // Valores por defecto según método
    const defaults = {
        POST: {
            confirmTitle: "¿Crear nuevo registro?",
            confirmText: "¿Seguro que deseas crear este nuevo registro?",
            successText: "Registro creado exitosamente.",
        },
        PUT: {
            confirmTitle: "¿Actualizar registro?",
            confirmText: "¿Deseas guardar los cambios?",
            successText: "Registro actualizado correctamente.",
        },
        DELETE: {
            confirmTitle: "¿Eliminar registro?",
            confirmText: "Esta acción no se puede deshacer.",
            successText: "Registro eliminado con éxito.",
        },
    };

    const methodDefaults = defaults[method.toUpperCase()] || {};

    Swal.fire({
        title: confirmTitle || methodDefaults.confirmTitle || "¿Estás seguro?",
        text: confirmText || methodDefaults.confirmText || "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (!result.isConfirmed) return;

        console.log("Enviando datos a la API:", {
            method,
            url,
            data,
            token,
        });
        fetch(url, {
            method,
            headers: {
                Authorization: `Bearer ${token}`,      // Para que Laravel reconozca al usuario autenticado (Sanctum o Passport)
                "Content-Type": "application/json",    // Para decirle que estás enviando JSON (usado en POST/PUT)
                "Accept": "application/json",          // Para decirle que esperás respuesta JSON (evita redirecciones HTML de errores)
            },
            body: data ? JSON.stringify(data) : null,
        })
            .then((res) => res.json())
            .then((response) => {
                if (response.success || response.message) {
                    Swal.fire(
                        "Éxito",
                        successText ||
                            methodDefaults.successText ||
                            response.message,
                        "success"
                    ).then(() => {
                        if (typeof callback === "function") callback(response);
                    });
                } else {
                    Swal.fire(
                        "Error",
                        response.message || "Ocurrió un error inesperado.",
                        "error"
                    );
                }
            })
            .catch((err) => {
                console.error(err);
                Swal.fire(
                    "Error",
                    "Ocurrió un error al procesar la solicitud.",
                    "error"
                );
            });
    });
};

// ========== UTILIDADES ==========
function normalizeText(text) {
    return text
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, "")
        .toLowerCase();
}