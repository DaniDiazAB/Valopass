const title = document.getElementById("title");
const navBar = document.createElement("div");
navBar.classList.add("nav");

title.onclick = function () {
    recagarDefault()
}

document.addEventListener("DOMContentLoaded", function () {
    cargarNavegacion();
    getCuentas(true);
    cuentasPublicas.classList.add("nav-marcado");
});

// Links Navegacion
const perfil = document.createElement("a");
const cuentasPublicas = document.createElement("a");
const cuentas = document.createElement("a");
const agregarCuenta = document.createElement("a");
const cerrarSesion = document.createElement("a");
const linkActualizarRangos = document.createElement("a");

perfil.onclick = function () {
    eliminarNavMarcado();
    document.querySelectorAll(".div-cuenta").forEach((el) => el.remove());
    document.getElementById("cuentas").remove();
    getCuentas(true);
    marcarNav(perfil);
};

cuentas.onclick = function () {
    eliminarNavMarcado();
    document.querySelectorAll(".div-cuenta").forEach((el) => el.remove());
    document.getElementById("cuentas").remove();
    getCuentas(false);
    marcarNav(cuentas);
};

cuentasPublicas.onclick = function () {
    recagarDefault()
};

agregarCuenta.onclick = function () {
    eliminarNavMarcado();
    cargarInputs("", "", "", "", "", true);

    fetch("/valopass/server/crear-nueva-cuenta.php")
        .then((response) => response.json())
        .then((data) => { })
        .catch((error) => console.error("Error:", error));
    marcarNav(agregarCuenta);
};

cerrarSesion.onclick = function () {
    alert("Sesión cerrada");
    window.location.href = "/valopass/login";

    fetch("/valopass/server/outlog.php")
        .then((response) => response.json())
        .then((data) => {

        })
        .catch((error) => console.error("Error:", error));
};

function cargarNavegacion() {
    perfil.classList.add("enlace-nav");
    perfil.innerHTML = "Mi perfil";

    cuentas.classList.add("enlace-nav");
    cuentas.innerHTML = "Mis cuentas";

    cuentasPublicas.classList.add("enlace-nav");
    cuentasPublicas.innerHTML = "Cuentas publicas";

    agregarCuenta.classList.add("enlace-nav");
    agregarCuenta.innerHTML = "Agregar cuenta";

    cerrarSesion.classList.add("enlace-nav");
    cerrarSesion.innerHTML = "Cerrar Sesión";

    linkActualizarRangos.classList.add("enlace-nav");
    linkActualizarRangos.innerHTML = "Actualizar rangos";

    title.insertAdjacentElement("afterend", navBar);

    //navBar.append(perfil)
    navBar.append(cuentasPublicas);
    navBar.append(cuentas);
    navBar.append(linkActualizarRangos);
    navBar.append(agregarCuenta);
    navBar.append(cerrarSesion);
}

function getCuentas(isTodasCuentas) {
    fetch("/valopass/server/get-cuentas.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "isTodasCuentas=" + encodeURIComponent(isTodasCuentas),
    })
        .then((response) => response.json())
        .then((data) => {
            const divCuentas = document.createElement("div");
            divCuentas.id = "cuentas";
            navBar.insertAdjacentElement("afterend", divCuentas);

            data.forEach((cuenta) => {
                getRangos(
                    cuenta.nick_cuenta,
                    cuenta.tag_cuenta,
                    cuenta.username_cuenta,
                    cuenta.password_cuenta,
                    divCuentas
                );
            });
        })
        .catch((error) => console.error("Error:", error));
}

linkActualizarRangos.onclick = function () {
    const cargandoRangos = document.createElement("img");
    cargandoRangos.src = "/valopass/public/resources/loading.gif";
    cargandoRangos.alt = "Actualizando los rangos en la base de datos";
    cargandoRangos.id = "actulizando-rangos";
    cargandoRangos.width = 25;
    cargandoRangos.height = 25;
    cargandoRangos.style.borderRadius = "10px";
    document.body.insertBefore(cargandoRangos, document.body.firstChild);

    const divCuentas = document.getElementById("cuentas");
    divCuentas.className = "cuenta";

    const url = "/valopass/server/proxy.php";
    fetch(url)
        .then((response) => response.text())
        .then((data) => {
            if (data.status === "ok") {
                actualizarImagenRango(data.username, data.rango);
            }
            cargandoRangos.setAttribute("hidden", "");
        })
        .catch((error) => {
            console.error("Error al obtener el texto:", error);
        });

};

function getRangos(nick, tag, username, password, divCuentas) {
    const datosCuenta = {
        username: username,
    };

    fetch("/valopass/server/get-rangos.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosCuenta),
    })
        .then((response) => response.json())
        .then((data) => {
            let rango = data[0];
            cargarInputs(rango, nick, tag, username, password, false, divCuentas);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function cargarInputs(
    rango,
    nick,
    tag,
    usename,
    password,
    isNuevaCuenta,
    divCuentas
) {
    const labelNick = document.createElement("label");
    labelNick.classList.add("account-label");
    labelNick.htmlFor = "nick-" + usename; 
    labelNick.textContent = ""

    const textoNick = document.createElement("input");
    textoNick.classList.add("nick");
    textoNick.type = "text";
    textoNick.name = "nick";
    textoNick.value = nick;
    textoNick.id = "nick-" + usename;
    labelNick.append(textoNick);

    const labelAlmohadilla = document.createElement("label");
    labelAlmohadilla.classList.add("account-label");
    labelAlmohadilla.htmlFor = "almohadilla-" + usename;
    labelAlmohadilla.textContent = ""
    
    const textoAlmohadilla = document.createElement("p");
    textoAlmohadilla.classList.add("almohadilla");
    textoAlmohadilla.textContent = "#";

    textoAlmohadilla.value = "#";

    const labelTag = document.createElement("label");
    labelTag.classList.add("account-label");
    labelTag.htmlFor = "tag-" + usename; 
    labelTag.textContent = ""

    const textoTag = document.createElement("input");
    textoTag.classList.add("nick");
    textoTag.setAttribute("type", "text");
    textoTag.setAttribute("name", "tag");
    textoTag.value = tag;
    textoTag.id = "tag-" + usename;

    const textoRango = document.createElement("p");
    textoRango.classList.add("rango");
    textoRango.setAttribute("type", "text");
    textoRango.setAttribute("name", "rango");
    textoRango.value = rango;

    const labelUsername = document.createElement("label");
    labelUsername.classList.add("account-label");
    labelUsername.htmlFor = "username-" + usename; 
    labelUsername.textContent = ""

    const textoUsername = document.createElement("input");
    textoUsername.setAttribute("type", "text");
    textoUsername.setAttribute("name", "username");
    textoUsername.classList.add("username");
    textoUsername.value = usename;
    textoUsername.id = "username-" + usename;

    const labelPassword = document.createElement("label");
    labelUsername.classList.add("account-label");
    labelPassword.htmlFor = "password-" + usename; 
    labelPassword.textContent = ""

    const textoPassword = document.createElement("input");
    textoPassword.setAttribute("type", "password");
    textoPassword.setAttribute("name", "password");
    textoPassword.classList.add("password");
    textoPassword.value = password;
    textoPassword.id = "password-" + usename;


    const btnVerPassword = document.createElement("span")
    btnVerPassword.classList.add("toggle-password")
    btnVerPassword.innerHTML = "👁️"

    btnVerPassword.onclick = function () {
        const input = this.parentElement.querySelector(".password");   
        if (input.type === "password") {
            input.type = "text";
            toggle.textContent = "🙈";
        } else {
            input.type = "password";
            toggle.textContent = "👁️";
        }
    }

    const infoRango = document.createElement("div");
    infoRango.classList.add("div-cuenta");
    infoRango.setAttribute("data-username", usename);

     infoRango.append(labelNick);
    labelNick.appendChild(textoNick);

    infoRango.append(textoAlmohadilla);

    infoRango.append(labelTag);
    labelTag.appendChild(textoTag);

    infoRango.append(textoRango);

    infoRango.append(labelUsername);
    labelUsername.appendChild(textoUsername);

    infoRango.append(labelPassword);
    labelPassword.appendChild(textoPassword);

    infoRango.append(btnVerPassword);


    const divBtn = document.createElement("div");
    divBtn.classList.add("div-btn");

    if (isNuevaCuenta) {
        textoNick.setAttribute("placeholder", "Nick");

        textoUsername.setAttribute("placeholder", "Nombre para el login");
        textoPassword.setAttribute("placeholder", "Contraseña");

        const btnGuardarCuenta = document.createElement("button");
        btnGuardarCuenta.innerHTML = "Guardar cuenta";
        btnGuardarCuenta.classList.add("btn-editar");

        const btnNoGuardarCuenta = document.createElement("button");
        btnNoGuardarCuenta.innerHTML = "No guardar cuenta";
        btnNoGuardarCuenta.classList.add("btn-eliminar");

        const isPublica = document.createElement("input");
        isPublica.setAttribute("type", "checkbox");
        isPublica.classList.add("checkbox-publica");
        isPublica.id = "is-publica";

        const labelPublica = document.createElement("label");
        labelPublica.innerHTML = "¿La cuenta es publica?";

        labelPublica.append(isPublica);

        btnGuardarCuenta.onclick = function () {
            guardarNuevaCuenta(
                textoNick.value,
                textoTag.value,
                textoUsername.value,
                textoPassword.value,
                isPublica.checked
            );
        };

        btnNoGuardarCuenta.onclick = function () {
            infoRango.setAttribute("style", "display: none");
        };

        divBtn.append(labelPublica);
        divBtn.append(btnGuardarCuenta);
        divBtn.append(btnNoGuardarCuenta);

        const elementoPadre = document.getElementById("cuentas");
        elementoPadre.insertBefore(infoRango, elementoPadre.firstChild);
        infoRango.append(divBtn);
    } else {

        textoNick.readOnly = true;
        textoAlmohadilla.readOnly = true;
        textoTag.readOnly = true;
        textoRango.readOnly = true;
        textoUsername.readOnly = true;
        textoPassword.readOnly = true;

        const btnEditar = document.createElement("button");
        btnEditar.innerHTML = "Editar cuenta";
        //btnEditar.classList.add("btn-guardar-cambios");
        btnEditar.classList.add("btn-editar-cuenta");

        const btnEliminar = document.createElement("button");
        btnEliminar.innerHTML = "Eliminar cuenta";
        //btnEliminar.classList.add("btn-deshacer-cambios");
        btnEliminar.classList.add("btn-eliminar-cuenta");


        const btnCopiarPassword = document.createElement("button");
        btnCopiarPassword.innerHTML = "Copiar contraseña";
        btnCopiarPassword.classList.add("btn-copiar-password");

        btnEditar.onclick = function () {
            editarCuenta(
                textoNick,
                textoTag,
                textoUsername,
                textoPassword,
                divBtn,
                btnEditar,
                btnEliminar,
                btnCopiarPassword
            ); // Username es el de incio de sesion

            btnEditar.setAttribute("hidden", "");
            btnEliminar.setAttribute("hidden", "");
            btnCopiarPassword.setAttribute("hidden", "");
        };

        btnEliminar.onclick = function () {
            let isBorrar = confirm("¿Estás seguro de que quieres borrar la cuenta?");
            if (isBorrar) {
                modificarCambios(
                    textoNick.value,
                    textoTag.value,
                    textoUsername.value,
                    textoPassword.value,
                    true
                );
                infoRango.remove();
            }
        };

        btnCopiarPassword.onclick = function () {
            navigator.clipboard.writeText(password);
        };

        agregarImgRango(rango, textoRango);

        divBtn.append(btnEditar);
        divBtn.append(btnEliminar);
        divBtn.append(btnCopiarPassword);

        divCuentas.append(infoRango);
        infoRango.append(divBtn);
    }

}

function editarCuenta(
    textoNick,
    textoTag,
    textoUsername,
    textoPassword,
    divBtn,
    btnEditar,
    btnEliminar,
    btnCopiarPassword
) {
    textoNick.readOnly = false;
    textoTag.readOnly = false;
    //textoUsername.readOnly = false;
    textoPassword.readOnly = false;

    textoUsername.onclick = function(){ alert("No se puede cambiar el nombre de la cuenta para iniciar sesión") }

    const btnGuardarCambios = document.createElement("button");
    btnGuardarCambios.innerHTML = "Guardar cambios";
    btnGuardarCambios.classList.add("btn-guardar-cambios");

    const btnDeshacerCambios = document.createElement("button");
    btnDeshacerCambios.innerHTML = "No guardar cambios";
    btnDeshacerCambios.classList.add("btn-deshacer-cambios");

    const isCuentaPublica = document.createElement("input");
    isCuentaPublica.setAttribute("type", "checkbox");
    isCuentaPublica.classList.add("checkbox-publica");
    isCuentaPublica.id = "is-publica";

    const labelPublica = document.createElement("label");
    labelPublica.innerHTML = "¿La cuenta es publica?";

    let isChecked = false;

    fetch("/valopass/server/get-cuenta-publica.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: textoNick.value }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.cuenta_publica) {
                isCuentaPublica.checked = true;
                isChecked = true;

            } else {
                isCuentaPublica.checked = false;
                isChecked = false;
            }
        });

    labelPublica.append(isCuentaPublica);

    btnGuardarCambios.onclick = function () {
        let isConfirmarNoGuardar = confirm(
            "¿Estás seguro de SÍ guardar los cambios?",
            ""
        );

        if (isConfirmarNoGuardar) {
            textoNick.readOnly = true;
            textoUsername.readOnly = true;
            textoPassword.readOnly = true;
            btnGuardarCambios.setAttribute("hidden", "");
            btnDeshacerCambios.setAttribute("hidden", "");
            isCuentaPublica.setAttribute("hidden", "");
            btnEditar.removeAttribute("hidden");
            btnEliminar.removeAttribute("hidden");
            btnCopiarPassword.removeAttribute("hidden");
            const isPublicaChecked = document.getElementById("is-publica").checked;
            labelPublica.remove();

            modificarCambios(
                textoNick.value,
                textoTag.value,
                textoUsername.value,
                textoPassword.value,
                false,
                isPublicaChecked
            );
        }
    };

    btnDeshacerCambios.onclick = function () {
        let isConfirmarNoGuardar = confirm(
            "¿Estás seguro de NO guardar los cambios?",
            ""
        );

        if (isConfirmarNoGuardar) {
            textoNick.readOnly = true;
            textoUsername.readOnly = true;
            textoPassword.readOnly = true;
            btnGuardarCambios.setAttribute("hidden", "");
            btnDeshacerCambios.setAttribute("hidden", "");
            btnEditar.removeAttribute("hidden");
            btnEliminar.removeAttribute("hidden");
            btnCopiarPassword.removeAttribute("hidden");

            const divBorrar = document.getElementById("cuentas");
            divBorrar.remove();
            getCuentas(true);
        }
    };

    divBtn.append(labelPublica);
    divBtn.append(btnGuardarCambios);
    divBtn.append(btnDeshacerCambios);
}

function modificarCambios(
    textoNick,
    textoTag,
    textoUsername,
    textoPassword,
    isEliminar,
    isPublica
) {

    const datosCuenta = {
        nick: textoNick,
        tag: textoTag,
        username: textoUsername.trim(),
        password: textoPassword.trim(),
        eliminar: isEliminar,
        isPublica: isPublica,
    };

    fetch("/valopass/server/guardar-cambios-cuentas.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosCuenta),
    })
        .then((response) => response.json())
        .then((data) => {
            if (!data) {
                alert("No tienes permisos para cambiar esta cuenta")
                recagarDefault()
            }


        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function guardarNuevaCuenta(
    textoNick,
    textoTag,
    textoUsername,
    textoPassword,
    isPublica
) {
    console.log(textoNick);
    console.log(textoTag);
    console.log(textoUsername);
    console.log(textoPassword);
    console.log(isPublica);
    
    const parent = document.getElementById("cuentas");
    parent.remove();

    const datosNuevaCuenta = {
        nick: textoNick,
        tag: textoTag,
        username: textoUsername.trim(),
        password: textoPassword.trim(),
        isPublica: isPublica,
    };

    fetch("/valopass/server/crear-nueva-cuenta.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datosNuevaCuenta),
    })
        .then((response) => response.json())
        .then((data) => {
        })
        .catch((error) => {
            console.error("Error:", error);
        });

    setTimeout(function () {
        getCuentas(true);
    }, 100);
}

function agregarImgRango(rango, textoNick) {
    const imgRango = document.createElement("img");
    imgRango.className = "img-rango";
    imgRango.setAttribute("title", rango);

    switch (rango) {
        case "Iron 1":
            imgRango.src = "/valopass/public/resources/img_rangos/iron1.png";
            break;
        case "Iron 2":
            imgRango.src = "/valopass/public/resources/img_rangos/iron2.png";
            break;
        case "Iron 3":
            imgRango.src = "/valopass/public/resources/img_rangos/iron3.png";
            break;
        case "Bronze 1":
            imgRango.src = "/valopass/public/resources/img_rangos/br1.png";
            break;
        case "Bronze 2":
            imgRango.src = "/valopass/public/resources/img_rangos/br2.png";
            break;
        case "Bronze 3":
            imgRango.src = "/valopass/public/resources/img_rangos/br3.png";
            break;
        case "Silver 1":
            imgRango.src = "/valopass/public/resources/img_rangos/sil1.png";
            break;
        case "Silver 2":
            imgRango.src = "/valopass/public/resources/img_rangos/sil2.png";
            break;
        case "Silver 3":
            imgRango.src = "/valopass/public/resources/img_rangos/sil3.png";
            break;
        case "Gold 1":
            imgRango.src = "/valopass/public/resources/img_rangos/gold1.png";
            break;
        case "Gold 2":
            imgRango.src = "/valopass/public/resources/img_rangos/gold2.png";
            break;
        case "Gold 3":
            imgRango.src = "/valopass/public/resources/img_rangos/gold3.png";
            break;
        case "Platinum 1":
            imgRango.src = "/valopass/public/resources/img_rangos/plat1.png";
            break;
        case "Platinum 2":
            imgRango.src = "/valopass/public/resources/img_rangos/plat2.png";
            break;
        case "Platinum 3":
            imgRango.src = "/valopass/public/resources/img_rangos/plat3.png";
            break;
        case "Diamond 1":
            imgRango.src = "/valopass/public/resources/img_rangos/dia1.png";
            break;
        case "Diamond 2":
            imgRango.src = "/valopass/public/resources/img_rangos/dia2.png";
            break;
        case "Diamond 3":
            imgRango.src = "/valopass/public/resources/img_rangos/dia3.png";
            break;
        case "Ascendant 1":
            imgRango.src = "/valopass/public/resources/img_rangos/asc1.png";
            break;
        case "Ascendant 2":
            imgRango.src = "/valopass/public/resources/img_rangos/asc2.png";
            break;
        case "Ascendant 3":
            imgRango.src = "/valopass/public/resources/img_rangos/asc3.png";
            break;
        case "Immortal 1":
            imgRango.src = "/valopass/public/resources/img_rangos/imm1.png";
            break;
        case "Immortal 2":
            imgRango.src = "/valopass/public/resources/img_rangos/imm2.png";
            break;
        case "Immortal 3":
            imgRango.src = "/valopass/public/resources/img_rangos/imm3.png";
            break;
        case "Radiant":
            imgRango.src = "/valopass/public/resources/img_rangos/radiant.png";
            break;
        default:
            imgRango.src = "/valopass/public/resources/img_rangos/unranked.png";
            break;
    }
    imgRango.alt = rango;
    textoNick.append(imgRango);
}

// Implementar de forna mas optima
function actualizarImagenRango(username, nuevoRango) {
    const mapaRangos = {
        "Iron 1": "/valopass/public/resources/img_rangos/iron1.png",
        "Iron 2": "/valopass/public/resources/img_rangos/iron2.png",
        "Iron 3": "/valopass/public/resources/img_rangos/iron3.png",
        "Bronze 1": "/valopass/public/resources/img_rangos/bron1.png",
        "Bronze 2": "/valopass/public/resources/img_rangos/bron2.png",
        "Bronze 3": "/valopass/public/resources/img_rangos/bron3.png",
        "Silver 1": "/valopass/public/resources/img_rangos/sil1.png",
        "Silver 2": "/valopass/public/resources/img_rangos/sil2.png",
        "Silver 3": "/valopass/public/resources/img_rangos/sil3.png",
        "Gold 1": "/valopass/public/resources/img_rangos/gold1.png",
        "Gold 2": "/valopass/public/resources/img_rangos/gold2.png",
        "Gold 3": "/valopass/public/resources/img_rangos/gold3.png",
        "Platinum 1": "/valopass/public/resources/img_rangos/plat1.png",
        "Platinum 2": "/valopass/public/resources/img_rangos/plat2.png",
        "Platinum 3": "/valopass/public/resources/img_rangos/plat3.png",
        "Diamond 1": "/valopass/public/resources/img_rangos/dia1.png",
    };

    const cuentaDiv = document.querySelector(
        `.div-cuenta[data-username="${username}"]`
    );

    if (cuentaDiv) {
        const img = cuentaDiv.querySelector(".img-rango");
        if (img) {
            img.src = mapaRangos[nuevoRango] || "resources/default.png";
            img.title = nuevoRango;
        }
    }
}

function eliminarNavMarcado() {
    perfil.classList.remove("nav-marcado");
    cuentasPublicas.classList.remove("nav-marcado");
    cuentas.classList.remove("nav-marcado");
    agregarCuenta.classList.remove("nav-marcado");
    cerrarSesion.classList.remove("nav-marcado");
}

function marcarNav(navMarcado) {
    navMarcado.classList.add("nav-marcado");
}

function recagarDefault() {
    eliminarNavMarcado();
    const divBorrar = document.getElementById("cuentas");
    divBorrar.remove();
    getCuentas(true);
    marcarNav(cuentasPublicas);
}