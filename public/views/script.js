const title = document.getElementById("title");
const navBar = document.createElement("div");
navBar.classList.add("nav");
navBar.id = "nav-bar";

const perfil = document.createElement("a");
const cuentasPublicas = document.createElement("a");
const cuentas = document.createElement("a");
const agregarCuenta = document.createElement("a");
const cerrarSesion = document.createElement("a");
const elementosInicio = [title, cuentas, cuentasPublicas, agregarCuenta];

const linkActualizarRangos = document.createElement("a");
const linkPerfil = document.createElement("a");
const btnNombreAgregarCuentaMain = document.createElement("input");
const btnTagAgregarCuentaMain = document.createElement("input");
const btnGuardar = document.createElement("button");
const btnCancelar = document.createElement("button");

const divEstadisticas = document.getElementById("div-estadisticas");
const divAmigos = document.getElementById("div-amigos");

// Botones cuenta main
const btnTracker = document.getElementById("tracker");
const btnActualizarRango = document.getElementById("actualizar-elo");
const btnEditarCuenta = document.getElementById("editar-cuenta");
const btnEliminarCuenta = document.getElementById("eliminar-cuenta");

let btnAgregarAmigo = null;
let isCuentaMainAgregada = false;

const datosCuentaMain = {
    nombreCuentaMain: null,
    tagCuentaMain: null,
};

async function postJSON(url, data) {
    try {
        const res = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });
        return await res.json();
    } catch (error) {
        console.error("Error:", error);
    }
}

document.addEventListener("DOMContentLoaded", init);

elementosInicio.forEach((elemento) => {
    elemento.addEventListener("click", () => {
        window.location.href = "/valopass/";
    });
});

linkPerfil.addEventListener("click", () => {
    window.location.href = `/valopass/${usernameSesion}`;
});

function init() {
    cargarNavegacion();
    cargarEstadisticas();
    linkPerfil.classList.add("nav-marcado");

    if (
        nombreCuenta != "" &&
        tagCuenta != "" &&
        idUsuarioLogin === idPerfilUsuario
    ) {
        isCuentaMainAgregada = true;
    }
    if (idUsuarioLogin != idPerfilUsuario) {
        btnAgregarAmigo = document.getElementById("agregar-amigo");
        btnAgregarAmigo.onclick = function () {
            agregarAmigo();
        };
    }
    funcionesBtns();
}

function cargarEstadisticas() {
    const datosCuentaMain = {
        idPerfilUsuario: idPerfilUsuario,
    };

    fetch("/valopass/server/get-low-high-accounts.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosCuentaMain),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status != "error") {
                if (data.length === 0) {
                    document.getElementById("elo-mayor").innerHTML =
                        "No hay cuentas públicas agregadas.";
                    document.getElementById("elo-menor").innerHTML = "";
                    return;
                } else if (data.length === 1) {
                    document.getElementById("elo-mayor").innerHTML =
                        `Solo tiene una cuenta: ${data[0].nick_cuenta} #${data[0].tag_cuenta} con un elo de ${data[0].rango_cuenta}`;
                    document.getElementById("elo-menor").innerHTML = ``;
                } else {
                    document.getElementById("elo-mayor").innerHTML =
                        `La cuenta con más elo es: ${data[1].nick_cuenta} #${data[1].tag_cuenta} con un elo de ${data[1].rango_cuenta}`;
                    document.getElementById("elo-menor").innerHTML =
                        `La cuenta con menos elo es: ${data[0].nick_cuenta} #${data[0].tag_cuenta} con un elo de ${data[0].rango_cuenta}`;
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });

    const idPerfiles = {
        idPerfilUsuario: idPerfilUsuario,
        idUsuarioLogin: idUsuarioLogin,
    };

    if (idUsuarioLogin != idPerfilUsuario) {
        fetch("/valopass/server/get-son-amigos.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(idPerfiles),
        })
            .then((response) => response.json())
            .then((data) => {

                if (data.son_amigos) {
                    btnAgregarAmigo.innerHTML = "Eliminar amigo";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}
function cargarNavegacion() {
    cuentasPublicas.classList.add("enlace-nav");
    cuentasPublicas.innerHTML = "Cuentas publicas";

    cuentas.classList.add("enlace-nav");
    cuentas.innerHTML = "Mis cuentas";

    linkActualizarRangos.classList.add("enlace-nav");
    linkActualizarRangos.innerHTML = "Actualizar rangos";

    agregarCuenta.classList.add("enlace-nav");
    agregarCuenta.innerHTML = "Agregar cuenta";

    linkPerfil.classList.add("enlace-nav");
    linkPerfil.innerHTML = usernameSesion;

    cerrarSesion.classList.add("enlace-final");
    cerrarSesion.innerHTML = "Cerrar Sesión";

    title.insertAdjacentElement("afterend", navBar);

    navBar.append(cuentasPublicas);
    navBar.append(cuentas);
    navBar.append(linkActualizarRangos);
    navBar.append(agregarCuenta);
    navBar.append(linkPerfil);
    navBar.append(cerrarSesion);
}

function funcionesBtns() {
    if (isCuentaMainAgregada) {
        btnTracker.onclick = function () {
            window.open(
                `https://tracker.gg/valorant/profile/riot/${nombreCuenta}%23${tagCuenta}/overview`,
                "_blank",
            );
        };

        btnActualizarRango.onclick = async function () {
            const datosCuentaMain = {
                nombreCuenta: nombreCuenta,
                tagCuenta: tagCuenta,
            };

            await postJSON(
                "/valopass/server/set-editar-cuenta-main.php",
                datosCuentaMain,
            );
            location.reload();
        };

        btnEditarCuenta.onclick = function () {
            crearInputs();

            btnGuardar.onclick = async function () {
                const datos = {
                    nombreCuenta: btnNombreAgregarCuentaMain.value,
                    tagCuenta: btnTagAgregarCuentaMain.value,
                };

                await postJSON("/valopass/server/set-editar-cuenta-main.php", datos);
                location.reload();
            };

            btnCancelar.onclick = function () {
                location.reload();
            };
        };

        btnEliminarCuenta.onclick = async function () {
            mostrarModal("¿Seguro que quieres eliminar la cuenta?", function () {
                const datos = {
                    nombreCuenta: nombreCuenta,
                    tagCuenta: tagCuenta,
                };
                postJSON("/valopass/server/delete-cuenta-main.php", datos);
                location.reload();
            });
        };
    } else {
        if (idUsuarioLogin === idPerfilUsuario) {
            const linkAgregarCuentaMain = document.getElementById("agregar-main");

            linkAgregarCuentaMain.onclick = function () {
                crearInputs();

                btnGuardar.onclick = async function () {
                    const datos = {
                        nombreCuenta: btnNombreAgregarCuentaMain.value,
                        tagCuenta: btnTagAgregarCuentaMain.value,
                    };
                    await postJSON("/valopass/server/set-cuenta-main.php", datos);
                    location.reload();
                };

                linkAgregarCuentaMain.setAttribute("hidden", "");
            };
        }
    }
}

function crearInputs() {
    btnNombreAgregarCuentaMain.type = "text";
    btnNombreAgregarCuentaMain.placeholder = "Nombre de la cuenta";
    btnNombreAgregarCuentaMain.name = "nombreCuentaMain";

    btnTagAgregarCuentaMain.type = "text";
    btnTagAgregarCuentaMain.placeholder = "Tag de la cuenta";
    btnTagAgregarCuentaMain.name = "tagCuentaMain";

    divEstadisticas.appendChild(btnNombreAgregarCuentaMain);
    divEstadisticas.appendChild(btnTagAgregarCuentaMain);

    btnNombreAgregarCuentaMain.focus();

    btnGuardar.id = "btnGuardarCuentaMain";
    btnGuardar.textContent = "Guardar cuenta";
    divEstadisticas.appendChild(btnGuardar);

    btnCancelar.id = "btnCancelarCuentaMain";
    btnCancelar.textContent = "Cancelar";
    divEstadisticas.appendChild(btnCancelar);
}

function agregarAmigo() {
    const nuevosAmigos = {
        idPerfilUsuario: idPerfilUsuario,
        idUsuarioLogin: idUsuarioLogin,
    };

    fetch("/valopass/server/set-nueva-amistad.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(nuevosAmigos),
    })
        .then((response) => response.json())
        .then((data) => {
            location.reload();
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function mostrarModal(texto, callbackConfirmar) {
    const modal = document.getElementById("modal-confirmacion");
    const modalTexto = document.getElementById("modal-texto");
    const btnConfirmar = document.getElementById("modal-confirmar");
    const btnCancelar = document.getElementById("modal-cancelar");

    modalTexto.textContent = texto;

    modal.style.display = "flex";

    btnConfirmar.onclick = function () {
        modal.style.display = "none";
        callbackConfirmar();
    };

    btnCancelar.onclick = function () {
        modal.style.display = "none";
    };
}

cerrarSesion.onclick = async function () {
    await fetch("/valopass/server/outlog.php");

    alert("Sesión cerrada");
    window.location.href = "/valopass/login";
};
