const title = document.getElementById("title");
const navBar = document.createElement("div");
navBar.classList.add("nav");
navBar.id = "nav-bar";

const perfil = document.createElement("a");
const cuentasPublicas = document.createElement("a");
const cuentas = document.createElement("a");
const agregarCuenta = document.createElement("a");
const cerrarSesion = document.createElement("a");
const linkActualizarRangos = document.createElement("a");
const linkPerfil = document.createElement("a");
const btnNombreAgregarCuentaMain = document.createElement("input");
const btnTagAgregarCuentaMain = document.createElement("input");
const btnGuardar = document.createElement("button");
const btnCancelar = document.createElement("button");

//const linkAgregarCuentaMain = document.getElementById("agregar-main");
const divEstadisticas = document.getElementById("div-estadisticas");
const divAmigos = document.getElementById("div-amigos");

// Botones cuenta main
const btnTracker = document.getElementById("tracker");
const btnActualizarRango = document.getElementById("actualizar-elo");
const btnEditarCuenta = document.getElementById("editar-cuenta");
const btnEliminarCuenta = document.getElementById("eliminar-cuenta");

title.onclick = function () {
    window.location.href = "/valopass/";
};

document.addEventListener("DOMContentLoaded", function () {
    cargarNavegacion();
    cargarEstadisticas();
    linkPerfil.classList.add("nav-marcado");
});

cuentas.onclick = function () {
    window.location.href = "/valopass/";
};

cuentasPublicas.onclick = function () {
    window.location.href = "/valopass/";
};

agregarCuenta.onclick = function () {
    window.location.href = "/valopass/";
};

linkPerfil.onclick = function () {
    window.location.href = "/valopass/" + usernameSesion;
};



function cargarEstadisticas() {


    
}






/*
linkAgregarCuentaMain.onclick = function () {
  btnNombreAgregarCuentaMain.type = "text";
  btnNombreAgregarCuentaMain.placeholder = "Nombre de la cuenta";
  btnNombreAgregarCuentaMain.name = "nombreCuentaMain";

  btnTagAgregarCuentaMain.type = "text";
  btnTagAgregarCuentaMain.placeholder = "Tag de la cuenta";
  btnTagAgregarCuentaMain.name = "tagCuentaMain";

  divEstadisticas.appendChild(btnNombreAgregarCuentaMain);
  divEstadisticas.appendChild(btnTagAgregarCuentaMain);

  btnGuardar.id = "btnCancelarCuentaMain";
  btnGuardar.textContent = "Guardar cuenta";
  divEstadisticas.appendChild(btnGuardar);

  btnCancelar.id = "btnCancelarCuentaMain";
  btnCancelar.textContent = "Cancelar";
  divEstadisticas.appendChild(btnCancelar);

  linkAgregarCuentaMain.setAttribute("hidden", "");
};
*/



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

function setNewMain(nombreCuentaMain, tagCuentaMain) {
    const datosCuentaMain = {
        nombreCuentaMain: nombreCuentaMain,
        tagCuentaMain: tagCuentaMain,
    };

    fetch("/valopass/server/set-new-main-account.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosCuentaMain),
    })
        .then((response) => response.json())
        .then((data) => {
            location.reload();
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

btnTracker.onclick = function () {
    window.open("https://tracker.gg/valorant/profile/riot/" + nombreCuenta + "%23" + tagCuenta + "/overview", "_blank");
}

btnActualizarRango.onclick = function () {
    const datosCuentaMain = {
        nombreCuenta: nombreCuenta,
        tagCuenta: tagCuenta,
    };

    fetch("/valopass/server/set-editar-cuenta-main.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosCuentaMain),
    })
        .then((response) => response.json())
        .then((data) => {
            location.reload();
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

btnEditarCuenta.onclick = function () {
    btnNombreAgregarCuentaMain.type = "text";
    btnNombreAgregarCuentaMain.value = nombreCuenta;
    btnNombreAgregarCuentaMain.name = "nombreCuentaMain";

    btnTagAgregarCuentaMain.type = "text";
    btnTagAgregarCuentaMain.value = tagCuenta;
    btnTagAgregarCuentaMain.name = "tagCuentaMain";

    divEstadisticas.appendChild(btnNombreAgregarCuentaMain);
    divEstadisticas.appendChild(btnTagAgregarCuentaMain);

    btnGuardar.id = "btnCancelarCuentaMain";
    btnGuardar.textContent = "Guardar cuenta";
    divEstadisticas.appendChild(btnGuardar);

    btnCancelar.id = "btnCancelarCuentaMain";
    btnCancelar.textContent = "Cancelar";
    divEstadisticas.appendChild(btnCancelar);

    btnGuardar.onclick = function () {

        const datosCuentaMain = {
            nombreCuenta: btnNombreAgregarCuentaMain.value,
            tagCuenta: btnTagAgregarCuentaMain.value,
        };

        fetch("/valopass/server/set-editar-cuenta-main.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(datosCuentaMain),
        })
            .then((response) => response.json())
            .then((data) => {
                location.reload();
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}

btnEliminarCuenta.onclick = function () {
    const respuesta = confirm("¿Estás seguro de que quieres guardar los cambios?");

    if (respuesta) {
        const datosCuentaMain = {
            nombreCuenta: nombreCuenta,
            tagCuenta: tagCuenta,
        };
        fetch("/valopass/server/delete-cuenta-main.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(datosCuentaMain),
        })
            .then((response) => response.json())
            .then((data) => {
                location.reload();
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}


cerrarSesion.onclick = function () {
    alert("Sesión cerrada");
    window.location.href = "/valopass/login";

    fetch("/valopass/server/outlog.php")
        .then((response) => response.json())
        .then((data) => { })
        .catch((error) => console.error("Error:", error));
};