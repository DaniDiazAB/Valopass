// Index loging
const title = document.getElementById("title");
const navBar = document.createElement("div")
navBar.classList.add("nav")


document.addEventListener("DOMContentLoaded", function () {
    //if (document.cookie.includes("usuario=")) {
    cargarNavegacion()
    getCuentas(true);
    //}
});

// Links Navegacion
const perfil = document.createElement("a")
const cuentas = document.createElement("a")
const cuentasPublicas = document.createElement("a")
const agregarCuenta = document.createElement("a")
const cerrarSesion = document.createElement("a")
const cargandoRangos = document.createElement("img")

perfil.onclick = function () {
    document.querySelectorAll(".div-cuenta").forEach(el => el.remove());
    document.getElementById("cuentas").remove()
    getCuentas(true);
}

cuentas.onclick = function () {
    document.querySelectorAll(".div-cuenta").forEach(el => el.remove());
    document.getElementById("cuentas").remove()
    getCuentas(false);
}

cuentasPublicas.onclick = function () {
    const divBorrar = document.getElementById("cuentas")
    divBorrar.remove()
    getCuentas(true)
}

agregarCuenta.onclick = function () {
    cargarInputs("", "", "", "", "", true)

    fetch("../server/crear-nueva-cuenta.php")
        .then((response) => response.json())
        .then((data) => {
        })
        .catch((error) => console.error("Error:", error));
}

cerrarSesion.onclick = function () {
    fetch("../server/outlog.php")
        .then((response) => response.json())
        .then((data) => {
            alert("Sesión cerrada")
            window.location.href = "views/login.html";
        })
        .catch((error) => console.error("Error:", error));
}

function cargarNavegacion() {
    perfil.classList.add("enlace-nav")
    perfil.innerHTML = "Mi perfil"

    cuentas.classList.add("enlace-nav")
    cuentas.innerHTML = "Mis cuentas"

    cuentasPublicas.classList.add("enlace-nav")
    cuentasPublicas.innerHTML = "Cuentas publicas"

    agregarCuenta.classList.add("enlace-nav")
    agregarCuenta.innerHTML = "Agregar cuenta"

    cerrarSesion.classList.add("enlace-nav")
    cerrarSesion.innerHTML = "Cerrar Sesión"

    cargandoRangos.src = "resources/loading.gif";
    cargandoRangos.alt = "Actualizando los rangos en la base de datos";
    cargandoRangos.id = "actulizando-rangos"
    cargandoRangos.width = 25;
    cargandoRangos.height = 25;
    cargandoRangos.style.borderRadius = "10px";
    document.body.insertBefore(cargandoRangos, document.body.firstChild);


    title.insertAdjacentElement("afterend", navBar);

    //navBar.append(perfil)
    navBar.append(cuentasPublicas)
    navBar.append(cuentas)
    navBar.append(agregarCuenta)
    navBar.append(cerrarSesion)
}

function getCuentas(isTodasCuentas) {
    fetch("../server/get-cuentas.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "isTodasCuentas=" + encodeURIComponent(isTodasCuentas)
    })
        .then((response) => response.json())
        .then((data) => {

            const divCuentas = document.createElement("div");
            divCuentas.id = "cuentas";
            navBar.insertAdjacentElement("afterend", divCuentas);

            data.forEach((cuenta) => {
                actualizarRangos(
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

function actualizarRangos(nick, tag, username, password, divCuentas) {
    getRangos(nick, tag, username, password, divCuentas)

    // PROXY, CAMBIAR EN LA VERSION FINAL -> https://vaccie.pythonanywhere.com/mmr/Dani/KKC/eu
    const url = "http://localhost:3000/api/mmr/" + nick + "/" + tag + "/EU";
    divCuentas.className = "cuenta";


    fetch(url)
        .then((response) => response.text())
        .then((data) => {
            let rango = data;
            rango = rango.split(","[0])[0];

            const datosActuCuenta = {
                username: username,
                rango: rango
            }

            fetch('../server/cambiar-rango.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosActuCuenta)
            })
                .then(() => {
                    cargandoRangos.setAttribute("hidden", "")
                })


        })
        .catch((error) => {
            console.error("Error al obtener el texto:", error);
        });
}

function getRangos(nick, tag, username, password, divCuentas) {
    const datosCuenta = {
        username: username,
    }

    fetch('../server/get-rangos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosCuenta)
    })
        .then(response => response.json())
        .then(data => {
            let rango = data[0]
            cargarInputs(rango, nick, tag, username, password, false, divCuentas);


        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function cargarInputs(rango, nick, tag, usename, password, isNuevaCuenta, divCuentas) {

    const textoNick = document.createElement("input");
    textoNick.classList.add("nick");
    textoNick.setAttribute("type", "text");
    textoNick.setAttribute("name", "nick");
    textoNick.value = nick + ' #' + tag;

    const textoRango = document.createElement("p");
    textoRango.classList.add("rango");
    textoRango.setAttribute("type", "text");
    textoRango.setAttribute("name", "rango");
    textoRango.value = rango;

    const textoUsername = document.createElement("input");
    textoUsername.setAttribute("type", "text");
    textoUsername.setAttribute("name", "username");
    textoUsername.classList.add("username");
    textoUsername.value = usename;

    const textoPassword = document.createElement("input");
    textoPassword.setAttribute("type", "text");
    textoPassword.setAttribute("name", "password");
    textoPassword.classList.add("password");
    textoPassword.value = password;

    const infoRango = document.createElement("div");
    infoRango.classList.add("div-cuenta");

    infoRango.append(textoNick);
    infoRango.append(textoRango);
    infoRango.append(textoUsername);
    infoRango.append(textoPassword);

    const divBtn = document.createElement("div");
    divBtn.classList.add("div-btn");

    if (isNuevaCuenta) {
        textoNick.setAttribute("placeholder", "Nick")

        textoUsername.setAttribute("placeholder", "Nombre para el login")
        textoPassword.setAttribute("placeholder", "Contraseña")

        const btnGuardarCuenta = document.createElement("button");
        btnGuardarCuenta.innerHTML = "Guardar cuenta";
        btnGuardarCuenta.classList.add("btn-editar");

        const btnNoGuardarCuenta = document.createElement("button");
        btnNoGuardarCuenta.innerHTML = "No guardar cuenta";
        btnNoGuardarCuenta.classList.add("btn-eliminar");

        const isPublica = document.createElement("input");
        isPublica.setAttribute("type", "checkbox")
        isPublica.classList.add("checkbox-publica");
        isPublica.id = "is-publica";

        const labelPublica = document.createElement("label");
        labelPublica.innerHTML = "¿La cuenta es publica?"

        labelPublica.append(isPublica)

        btnGuardarCuenta.onclick = function () {
            guardarNuevaCuenta(textoNick.value, textoUsername.value, textoPassword.value, isPublica.checked)
        }

        btnNoGuardarCuenta.onclick = function () {
            infoRango.setAttribute("style", "display: none")
        }

        divBtn.append(labelPublica);
        divBtn.append(btnGuardarCuenta);
        divBtn.append(btnNoGuardarCuenta);

        const elementoPadre = document.getElementById("cuentas")
        elementoPadre.insertBefore(infoRango, elementoPadre.firstChild);
        infoRango.append(divBtn);

    } else {
        textoNick.readOnly = true;
        textoRango.readOnly = true;
        textoUsername.readOnly = true;
        textoPassword.readOnly = true;

        const btnEditar = document.createElement("button");
        btnEditar.innerHTML = "Editar cuenta";
        btnEditar.classList.add("btn-guardar-cambios");

        const btnEliminar = document.createElement("button");
        btnEliminar.innerHTML = "Eliminar cuenta";
        btnEliminar.classList.add("btn-deshacer-cambios");

        const btnCopiarPassword = document.createElement("button");
        btnCopiarPassword.innerHTML = "Copiar contraseña";
        btnCopiarPassword.classList.add("btn-password");

        btnEditar.onclick = function () {
            editarCuenta(textoNick, textoUsername, textoPassword, divBtn, btnEditar, btnEliminar, btnCopiarPassword) // Username es el de incio de sesion
            btnEditar.setAttribute("hidden", "")
            btnEliminar.setAttribute("hidden", "")
            btnCopiarPassword.setAttribute("hidden", "")
        }

        btnEliminar.onclick = function () {
            let isBorrar = confirm("¿Estás seguro de que quieres borrar la cuenta?")
            if (isBorrar) {
                modificarCambios(textoNick.value, textoUsername.value, textoPassword.value, true)
                infoRango.remove()
            }
        }

        btnCopiarPassword.onclick = function () {
            navigator.clipboard.writeText(password)
        }

        agregarImgRango(rango, textoRango);

        divBtn.append(btnEditar);
        divBtn.append(btnEliminar);
        divBtn.append(btnCopiarPassword);

        divCuentas.append(infoRango);
        infoRango.append(divBtn);


    }

}

function editarCuenta(textoNick, textoUsername, textoPassword, divBtn, btnEditar, btnEliminar, btnCopiarPassword) {
    textoNick.readOnly = false
    textoUsername.readOnly = false
    textoPassword.readOnly = false

    const btnGuardarCambios = document.createElement("button");
    btnGuardarCambios.innerHTML = "Guardar cambios";
    btnGuardarCambios.classList.add("btn-guardar-cambios");

    const btnDeshacerCambios = document.createElement("button");
    btnDeshacerCambios.innerHTML = "No guardar cambios";
    btnDeshacerCambios.classList.add("btn-deshacer-cambios");

    const isCuentaPublica = document.createElement("input");
    isCuentaPublica.setAttribute("type", "checkbox")
    isCuentaPublica.classList.add("checkbox-publica");
    isCuentaPublica.id = "is-publica";

    const labelPublica = document.createElement("label");
    labelPublica.innerHTML = "¿La cuenta es publica?"

    let partes = textoNick.value.split("#")

    let isChecked = false

    fetch("../server/get-cuenta-publica.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: partes[0] })
    })
        .then(res => res.json())
        .then(data => {

            if (data.isPublica) {
                isCuentaPublica.checked = true;
                isChecked = true
            } else {
                isCuentaPublica.checked = false;
                isChecked = false
            }
        });

    labelPublica.append(isCuentaPublica)

    btnGuardarCambios.onclick = function () {
        let isConfirmarNoGuardar = confirm("¿Estás seguro de SÍ guardar los cambios?", "")

        if (isConfirmarNoGuardar) {
            textoNick.readOnly = true
            textoUsername.readOnly = true
            textoPassword.readOnly = true
            btnGuardarCambios.setAttribute("hidden", "")
            btnDeshacerCambios.setAttribute("hidden", "")
            isCuentaPublica.setAttribute("hidden", "")
            btnEditar.removeAttribute("hidden")
            btnEliminar.removeAttribute("hidden")
            btnCopiarPassword.removeAttribute("hidden")
            const isPublicaChecked = document.getElementById("is-publica").checked 
            labelPublica.remove()   

            modificarCambios(textoNick.value, textoUsername.value, textoPassword.value, false, isPublicaChecked)

        }

    }

    btnDeshacerCambios.onclick = function () {
        let isConfirmarNoGuardar = confirm("¿Estás seguro de NO guardar los cambios?", "")

        if (isConfirmarNoGuardar) {
            textoNick.readOnly = true
            textoUsername.readOnly = true
            textoPassword.readOnly = true
            btnGuardarCambios.setAttribute("hidden", "")
            btnDeshacerCambios.setAttribute("hidden", "")
            btnEditar.removeAttribute("hidden")
            btnEliminar.removeAttribute("hidden")
            btnCopiarPassword.removeAttribute("hidden")

            const divBorrar = document.getElementById("cuentas")
            divBorrar.remove()
            getCuentas(true);
        }

    }

    divBtn.append(labelPublica)
    divBtn.append(btnGuardarCambios)
    divBtn.append(btnDeshacerCambios)
}

function guardarNuevaCuenta(textoNick, textoUsername, textoPassword, isPublica) {
    const parent = document.getElementById("cuentas");
    parent.remove()

    let partes = textoNick.split("#")

    const datosNuevaCuenta = {
        nick: partes[0].trim(),
        tag: partes[1].trim(),
        username: textoUsername.trim(),
        password: textoPassword.trim(),
        isPublica: isPublica,
    }

    fetch('../server/crear-nueva-cuenta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosNuevaCuenta)
    })
        .then(response => response.json())
        .then(data => {
            //console.log('Respuesta:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });

    setTimeout(function () {
        getCuentas(true);
    }, 100);

}

function modificarCambios(textoNick, textoUsername, textoPassword, isEliminar, isPublica) {
    let partes = textoNick.split("#")
    const datosCuenta = {
        nick: partes[0].trim(),
        tag: partes[1].trim(),
        username: textoUsername.trim(),
        password: textoPassword.trim(),
        eliminar: isEliminar,
        isPublica: isPublica
    };

    fetch('../server/guardar-cambios-cuentas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosCuenta)
    })
        .then(response => response.json())
        .then(data => {
            //console.log('Respuesta:', isPublica);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function agregarImgRango(rango, textoNick) {
    const imgRango = document.createElement("img");
    imgRango.className = "img-rango";
    imgRango.setAttribute("title", rango);

    switch (rango) {
        case "Iron 1":
            imgRango.src = "resources/iron1.png";
            break;
        case "Iron 2":
            imgRango.src = "resources/iron2.png";
            break;
        case "Iron 3":
            imgRango.src = "resources/iron3.png";
            break;
        case "Bronze 1":
            imgRango.src = "resources/br1.png";
            break;
        case "Bronze 2":
            imgRango.src = "resources/br2.png";
            break;
        case "Bronze 3":
            imgRango.src = "resources/br3.png";
            break;
        case "Silver 1":
            imgRango.src = "resources/sil1.png";
            break;
        case "Silver 2":
            imgRango.src = "resources/sil2.png";
            break;
        case "Silver 3":
            imgRango.src = "resources/sil3.png";
            break;
        case "Gold 1":
            imgRango.src = "resources/gold1.png";
            break;
        case "Gold 2":
            imgRango.src = "resources/gold2.png";
            break;
        case "Gold 3":
            imgRango.src = "resources/gold3.png";
            break;
        case "Platinum 1":
            imgRango.src = "resources/plat1.png";
            break;
        case "Platinum 2":
            imgRango.src = "resources/plat2.png";
            break;
        case "Platinum 3":
            imgRango.src = "resources/plat3.png";
            break;
        case "Diamond 1":
            imgRango.src = "resources/dia1.png";
            break;
        case "Diamond 2":
            imgRango.src = "resources/dia2.png";
            break;
        case "Diamond 3":
            imgRango.src = "resources/dia3.png";
            break;
        case "Ascendant 1":
            imgRango.src = "resources/asc1.png";
            break;
        case "Ascendant 2":
            imgRango.src = "resources/asc2.png";
            break;
        case "Ascendant 3":
            imgRango.src = "resources/asc3.png";
            break;
        case "Immortal 1":
            imgRango.src = "resources/imm1.png";
            break;
        case "Immortal 2":
            imgRango.src = "resources/imm2.png";
            break;
        case "Immortal 3":
            imgRango.src = "resources/imm3.png";
            break;
        case "Radiant":
            imgRango.src = "resources/radiant.png";
            break;
        default:
            // Opcional: puedes agregar un caso por defecto si lo necesitas
            // imgRango.src = "resources/default.png";
            break;
    }

    textoNick.append(imgRango);
}