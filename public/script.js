// Index loging

const title = document.getElementById("title");
const divCuenta = document.createElement("div");
const navBar = document.createElement("div")
navBar.classList.add("nav")



document.addEventListener("DOMContentLoaded", function () {
    if (!document.cookie.includes("usuario=")) {
        window.location.href = "views/login.html";
    } else {
        cargarNavegacion()
        getCuentas();
    }
});

function cargarNavegacion(){

    const perfil = document.createElement("a")
    const cuentas = document.createElement("a")
    const agregar = document.createElement("a")
    const cuentasPublicas = document.createElement("a")
    const cerrarSesion = document.createElement("a")

    perfil.classList.add("enlace-nav")
    perfil.innerHTML = "Mi perfil"

    cuentas.classList.add("enlace-nav")
    cuentas.innerHTML = "Mis cuentas"

    agregar.classList.add("enlace-nav")
    agregar.innerHTML = "Agregar cuenta"

    cuentasPublicas.classList.add("enlace-nav")
    cuentasPublicas.innerHTML = "Cuentas publicas"

    cerrarSesion.classList.add("enlace-nav")
    cerrarSesion.innerHTML = "Cerrar Sesión"

    title.insertAdjacentElement("afterend", navBar);

    navBar.append(perfil)
    navBar.append(cuentas)
    navBar.append(agregar)
    navBar.append(cuentasPublicas)
    navBar.append(cerrarSesion)


}

function getCuentas() {
    fetch("http://localhost/Valopass/server/cuentas.php")
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

function getRangos(nick, tag, usename, password, divCuentas) {
    const url = "http://localhost:3000/api/mmr/" + nick + "/" + tag + "/EU";

    fetch(url)
        .then((response) => response.text())
        .then((data) => {
            divCuenta.className = "cuenta";
            divCuentas.append(divCuenta);

            let rango = data;
            rango = rango.split(","[0])[0];
    
            cargarInputs(rango, nick, tag, usename, password);
        })
        .catch((error) => {
            console.error("Error al obtener el texto:", error);
        });
}

function cargarInputs(rango, nick, tag, usename, password) {
    const textoNick = document.createElement("input");
    textoNick.classList.add("nick");
    textoNick.setAttribute("type", "text");
    textoNick.setAttribute("name", "nick");
    textoNick.readOnly = true;
    textoNick.value = nick + ' #' + tag;

    const textoRango = document.createElement("p");
    textoRango.classList.add("rango");
    textoRango.setAttribute("type", "text");
    textoRango.setAttribute("name", "rango");
    textoRango.readOnly = true;
    textoRango.value = rango;

    const textoUsername = document.createElement("input");
    textoUsername.setAttribute("type", "text");
    textoUsername.setAttribute("name", "username");
    textoUsername.classList.add("username");
    textoUsername.readOnly = true;
    textoUsername.value = usename;

    const textoPassword = document.createElement("input");
    textoPassword.setAttribute("type", "text");
    textoPassword.setAttribute("name", "password");
    textoPassword.readOnly = true;
    textoPassword.classList.add("password");
    textoPassword.value = password;

    const infoRango = document.createElement("div");
    infoRango.classList.add("div-cuenta");

    infoRango.append(textoNick);
    infoRango.append(textoRango);
    infoRango.append(textoUsername);
    infoRango.append(textoPassword);

    const btnEditar = document.createElement("button");
    btnEditar.innerHTML = "Editar cuenta";
    btnEditar.classList.add("btn-editar");

    const btnEliminar = document.createElement("button");
    btnEliminar.innerHTML = "Eliminar cuenta";
    btnEliminar.classList.add("btn-eliminar");

    const btnCopiarPassword = document.createElement("button");
    btnCopiarPassword.innerHTML = "Copiar contraseña";
    btnCopiarPassword.classList.add("btn-password");

    const divBtn = document.createElement("div");
    divBtn.classList.add("div-btn");

    btnEditar.onclick = function () {
        editarCuenta(textoNick, textoUsername, textoPassword, divBtn, btnEditar, btnEliminar, btnCopiarPassword) // Username es el de incio de sesion
        btnEditar.setAttribute("hidden", "")
        btnEliminar.setAttribute("hidden", "")
        btnCopiarPassword.setAttribute("hidden", "")
    }

    btnEliminar.onclick = function(){
        let isBorrar = confirm("¿Estás seguro de que quieres borrar la cuenta?")
        if (isBorrar){
            modificarCambios(textoNick.value, textoUsername.value, textoPassword.value, true)
            infoRango.remove()
        }
    }

    btnCopiarPassword.onclick = function(){
        navigator.clipboard.writeText(password)       
    }

    agregarImgRango(rango, textoRango);

    divBtn.append(btnEditar);
    divBtn.append(btnEliminar);
    divBtn.append(btnCopiarPassword);

    divCuenta.append(infoRango);
    infoRango.append(divBtn);
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

    btnGuardarCambios.onclick = function () {
        let isConfirmarNoGuardar = confirm("¿Estás seguro de SÍ guardar los cambios?", "")

        if (isConfirmarNoGuardar) {
            textoNick.readOnly = true
            textoUsername.readOnly = true
            textoPassword.readOnly = true
            btnGuardarCambios.setAttribute("hidden", "")
            btnDeshacerCambios.setAttribute("hidden", "")
            btnEditar.removeAttribute("hidden")
            btnEliminar.removeAttribute("hidden")
            btnCopiarPassword.removeAttribute("hidden")
            modificarCambios(textoNick.value, textoUsername.value, textoPassword.value, false)

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
        }
    }
    divBtn.append(btnGuardarCambios)
    divBtn.append(btnDeshacerCambios)
}

function modificarCambios(textoNick, textoUsername, textoPassword, isEliminar) {
    let partes = textoNick.split("#")

    const datosCuenta = {
        nick: partes[0],
        tag: partes[1],
        username: textoUsername,
        password: textoPassword,
        eliminar: isEliminar
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
            console.log('Respuesta:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


function agregarImgRango(rango, textoNick) {
    const imgRango = document.createElement("img");
    imgRango.className = "img-rango";

    if (rango === "Iron 1") {
        imgRango.src = "resources/iron1.png";
    }

    if (rango === "Iron 2") {
        imgRango.src = "resources/iron2.png";
    }

    if (rango === "Iron 3") {
        imgRango.src = "resources/iron3.png";
    }

    if (rango === "Bronze 1") {
        imgRango.src = "resources/br1.png";
    }

    if (rango === "Bronze 2") {
        imgRango.src = "resources/br2.png";
    }

    if (rango === "Bronze 3") {
        imgRango.src = "resources/br3.png";
    }

    if (rango === "Silver 1") {
        imgRango.src = "resources/sil1.png";
    }

    if (rango === "Silver 2") {
        imgRango.src = "resources/sil2.png";
    }

    if (rango === "Silver 3") {
        imgRango.src = "resources/sil3.png";
    }

    if (rango === "Gold 1") {
        imgRango.src = "resources/gold1.png";
    }

    if (rango === "Gold 2") {
        imgRango.src = "resources/gold2.png";
    }

    if (rango === "Gold 3") {
        imgRango.src = "resources/gold3.png";
    }

    if (rango === "Platinum 1") {
        imgRango.src = "resources/plat1.png";
    }

    if (rango === "Platinum 2") {
        imgRango.src = "resources/plat2.png";
    }

    if (rango === "Platinum 3") {
        imgRango.src = "resources/plat3.png";
    }

    if (rango === "Diamond 1") {
        imgRango.src = "resources/dia1.png";
    }

    if (rango === "Diamond 2") {
        imgRango.src = "resources/dia2.png";
    }

    if (rango === "Diamond 3") {
        imgRango.src = "resources/dia3.png";
    }

    if (rango === "Ascendant 1") {
        imgRango.src = "resources/asc1.png";
    }

    if (rango === "Ascendant 2") {
        imgRango.src = "resources/asc2.png";
    }

    if (rango === "Ascendant 3") {
        imgRango.src = "resources/asc3.png";
    }

    if (rango === "Immortal 1") {
        imgRango.src = "resources/imm1.png";
    }

    if (rango === "Immortal 2") {
        imgRango.src = "resources/imm2.png";
    }

    if (rango === "Immortal 3") {
        imgRango.src = "resources/imm3.png";
    }

    if (rango === "Radiant") {
        imgRango.src = "resources/radiant.png";
    }

    textoNick.append(imgRango);
}