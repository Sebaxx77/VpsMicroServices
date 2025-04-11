// Archivo global para scripts

// Función para normalizar el texto: elimina acentos, espacios y pasa a minúsculas
function normalizeText(text) {
    return text
        .normalize("NFD") // descompone caracteres acentuados
        .replace(/[\u0300-\u036f]/g, "") // elimina marcas de acentos
        .replace(/\s+/g, "") // elimina espacios
        .toLowerCase();
}

document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");

    if (sidebarToggle && sidebar) {
        // Listener para mostrar/ocultar el sidebar al hacer clic en el botón
        sidebarToggle.addEventListener("click", function (event) {
            event.stopPropagation();
            sidebar.classList.toggle("-translate-x-full");
        });

        // Listener para cerrar el sidebar si se hace clic fuera de él
        document.addEventListener("click", function (event) {
            if (!sidebar.classList.contains("-translate-x-full")) {
                if (
                    !sidebar.contains(event.target) &&
                    !sidebarToggle.contains(event.target)
                ) {
                    sidebar.classList.add("-translate-x-full");
                }
            }
        });
    }

    // Funcionalidad de búsqueda en el sidebar
    const sidebarSearch = document.getElementById("sidebarSearch");
    const sidebarList = document.getElementById("sidebarList");

    if (sidebarSearch && sidebarList) {
        sidebarSearch.addEventListener("input", function () {
            // Obtiene el filtro normalizado
            let filter = normalizeText(sidebarSearch.value);

            // Obtiene todos los hijos directos del sidebarList
            let children = Array.from(sidebarList.children);

            let currentHeading = null;
            let currentItems = [];

            // Función para procesar la sección actual
            function processSection() {
                if (!currentHeading) return;
                // Se normaliza el texto del heading
                let headingText = normalizeText(currentHeading.textContent);
                let headingMatches = headingText.indexOf(filter) > -1;
                let anyItemMatches = false;

                // Procesa cada <li> de la sección
                currentItems.forEach((item) => {
                    let itemText = normalizeText(item.textContent);
                    let itemMatches = itemText.indexOf(filter) > -1;
                    // Si el heading coincide, se muestran todos los ítems
                    if (headingMatches || itemMatches || filter === "") {
                        item.style.display = "";
                        if (itemMatches) {
                            anyItemMatches = true;
                        }
                    } else {
                        item.style.display = "none";
                    }
                });

                // Se muestra el heading si:
                // - El filtro está vacío
                // - El heading coincide con el filtro
                // - Alguno de los items coincide
                currentHeading.style.display =
                    filter === "" || headingMatches || anyItemMatches
                        ? ""
                        : "none";
            }

            // Recorre los elementos del sidebarList
            children.forEach((child) => {
                let tag = child.tagName.toLowerCase();

                if (tag === "h2") {
                    // Si ya había una sección, la procesamos antes de iniciar una nueva
                    processSection();
                    // Inicia nueva sección
                    currentHeading = child;
                    currentItems = [];
                    // Mientras se escribe algo, oculta temporalmente el heading (se mostrará si corresponde en processSection)
                    currentHeading.style.display = filter === "" ? "" : "none";
                } else if (tag === "li") {
                    currentItems.push(child);
                } else if (tag === "hr") {
                    // Opcional: puedes ocultar o mostrar los <hr> según convenga.
                    child.style.display = filter === "" ? "" : "none";
                }
            });
            // Procesa la última sección
            processSection();
        });
    }

    // --- Función para controlar la fecha de entrega en el fomurlario publico ---
    const fechaEntregaInput = document.getElementById("fecha_entrega");

    if (fechaEntregaInput) {
        // Obtener la fecha de mañana
        const mañana = new Date();
        mañana.setDate(mañana.getDate() + 1);

        // Formatear la fecha de mañana a YYYY-MM-DD para el atributo min
        const añoMañana = mañana.getFullYear();
        const mesMañana = String(mañana.getMonth() + 1).padStart(2, "0");
        const diaMañana = String(mañana.getDate()).padStart(2, "0");
        const fechaMinima = `${añoMañana}-${mesMañana}-${diaMañana}`;

        // Establecer el atributo min del input de fecha
        fechaEntregaInput.setAttribute("min", fechaMinima);

        // Opcional: Agregar una validación adicional para navegadores que no soportan 'min'
        fechaEntregaInput.addEventListener("change", function () {
            const fechaSeleccionada = new Date(this.value);
            if (fechaSeleccionada < mañana) {
                this.value = ""; // Limpiar el campo si la fecha es anterior a mañana
                alert(
                    "La fecha de descarga debe ser igual o posterior a mañana."
                );
            }
        });
    }
    // --- Fin de la función para controlar la fecha ---
});
// Agregar funcionalidad de Alpine para los cards dinámicos:
document.addEventListener('alpine:init', () => {
    Alpine.data('cardManager', () => ({
        openCard: null,
        getFormAction() {
            if (this.openCard) {
                let parts = this.openCard.split('-');
                return "{{ route('solicitudes.update', ['id' => '__ID__']) }}".replace('__ID__', parts[1]);
            }
            return "#";
        }
    }));
});
// Función para procesar Datatables
window.initDataTable = function({ selector, apiUrl, apiToken = null, columns}) {
    console.log('initDataTable ejecutado');
    console.log('API URL:', apiUrl);
    console.log('API Token:', apiToken);
    
    const tabla = document.querySelector(selector);
    if (!tabla) {
        console.warn('Tabla no encontrada:', selector);
        return;
    }

    $(tabla).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: apiUrl,
            headers: apiToken ? { Authorization: 'Bearer ' + apiToken } : {},
            dataSrc: 'data',
        },
        columns: columns,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
            emptyTable: "No hay registros disponibles",
            zeroRecords: "No se encontraron coincidencias",
        }
    });
}
// Eventos para el perfil de usuario y autenticación 2FA

// Función para cargar datos del perfil de usuario
window.loadProfile = function () {
    const spinner = document.getElementById('profile-spinner');
    spinner.classList.remove('hidden');

    fetch(`${window.apiBaseUrl}/show`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${window.apiToken}`
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.name && data.email) {
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
        } else {
            Swal.fire('Error', 'No se pudieron cargar los datos del perfil.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Hubo un problema al cargar los datos del perfil.', 'error');
    })
    .finally(() => {
        spinner.classList.add('hidden');
    });
};

// Función para editar perfil de usuario
window.updateProfile = function(event) {
    event.preventDefault();

    Swal.fire({
        title: '¿Guardar cambios?',
        text: "Estás a punto de actualizar tu perfil.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            fetch(`${window.apiBaseUrl}/update`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${window.apiToken}`
                },
                body: JSON.stringify({ name, email })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success || data.message === 'Perfil actualizado.') {
                    Swal.fire('¡Actualizado!', 'Tu perfil fue modificado.', 'success').then(() => {
                        location.reload(); // Recarga la página después de confirmar
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo actualizar el perfil', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Hubo un problema al actualizar el perfil', 'error');
            });
        }
    });
};

// Función para manejar 2FA
window.toggleTwoFactor = function(event) {
    event.preventDefault();

    fetch(`${window.apiBaseUrl}/2FA`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${window.apiToken}`
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.message) {
            Swal.fire('2FA', data.message || '2FA actualizado', 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', 'Error al actualizar la 2FA', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Hubo un problema al cambiar 2FA', 'error');
    });
};
