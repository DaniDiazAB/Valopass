// Index loging

const title = document.getElementById("title")

document.addEventListener('DOMContentLoaded', function() {
     if (!document.cookie.includes("usuario=")) {
        window.location.href = "views/login.html";
    } else {
        getCuentas(); 
    }
});


function getCuentas(){
    fetch("http://localhost/Valopass/server/cuentas.php")
    .then(response => response.json())
    .then(data => {
        const divCuentas = document.createElement("div")
        divCuentas.id = "cuentas"
        title.insertAdjacentElement("afterend", divCuentas)

        data.forEach(cuenta => {
            getRangos(cuenta.nick_cuenta, cuenta.tag_cuenta, cuenta.username_cuenta, cuenta.password_cuenta, divCuentas)
        });
  })
  .catch(error => console.error("Error:", error));


}

function getRangos(nick, tag, usename, password, divCuentas){
    const url = "http://localhost:3000/api/mmr/" + nick + "/" + tag + "/EU";

    fetch(url)
    .then(response => response.text()) 
    .then(data => {

        const divCuenta = document.createElement("div")
        divCuenta.className = "cuenta"
        divCuentas.append(divCuenta)


        let rango = data;
        rango = rango.split(','[0])[0]
        
        const textoNick = document.createElement("h3")
        textoNick.classList.add('nick')
        textoNick.textContent = nick

        const textoRango = document.createElement("h3")
        textoRango.classList.add('rango')
        textoRango.textContent = rango

        agregarImgRango(rango, textoRango);

        const textoUsername = document.createElement("h4")
        textoUsername.textContent = usename
        textoUsername.classList.add('username')

        const textoPassword = document.createElement("h4")
        textoPassword.textContent = password
        textoPassword.classList.add('password')

        const infoRango = document.createElement("div")
        infoRango.classList.add('div-cuenta')
        infoRango.append(textoNick)
        
        infoRango.append(textoRango)

        const infoCuenta = document.createElement("div")
        infoCuenta.classList.add('div-cuenta')
        infoCuenta.append(textoUsername)
        infoCuenta.append(textoPassword)

        divCuenta.append(infoRango)
        divCuenta.append(infoCuenta)



        



    })
    .catch(error => {
        console.error("Error al obtener el texto:", error);
    });


}

function agregarImgRango(rango, textoNick){
    const imgRango = document.createElement("img");
    imgRango.className = "img-rango"

    if (rango === "Iron 1"){
        imgRango.src = "resources/iron1.png";
    }

    if (rango === "Iron 2"){
        imgRango.src = "resources/iron2.png";
    }

    if (rango === "Iron 3"){
        imgRango.src = "resources/iron3.png";
    }

    if (rango === "Bronze 1"){
        imgRango.src = "resources/br1.png";
    }

    if (rango === "Bronze 2"){
        imgRango.src = "resources/br2.png";
    }

    if (rango === "Bronze 3"){
        imgRango.src = "resources/br3.png";
    }

    if (rango === "Silver 1"){
        imgRango.src = "resources/sil1.png";
    }

    if (rango === "Silver 2"){
        imgRango.src = "resources/sil2.png";
    }

    if (rango === "Silver 3"){
        imgRango.src = "resources/sil3.png";
    }

    if (rango === "Gold 1"){
        imgRango.src = "resources/gold1.png";
    }

    if (rango === "Gold 2"){
        imgRango.src = "resources/gold2.png";
    }

    if (rango === "Gold 3"){
        imgRango.src = "resources/gold3.png";
    }

    if (rango === "Platinum 1"){
        imgRango.src = "resources/plat1.png";
    }

    if (rango === "Platinum 2"){
        imgRango.src = "resources/plat2.png";
    }

    if (rango === "Platinum 3"){
        imgRango.src = "resources/plat3.png";
    }

    if (rango === "Diamond 1"){
        imgRango.src = "resources/dia1.png";
    }

    if (rango === "Diamond 2"){
        imgRango.src = "resources/dia2.png";
    }

    if (rango === "Diamond 3"){
        imgRango.src = "resources/dia3.png";
    }

    if (rango === "Ascendant 1"){
        imgRango.src = "resources/asc1.png";
    }

    if (rango === "Ascendant 2"){
        imgRango.src = "resources/asc2.png";
    }

    if (rango === "Ascendant 3"){
        imgRango.src = "resources/asc3.png";
    }

    if (rango === "Immortal 1"){
        imgRango.src = "resources/imm1.png";
    }

    if (rango === "Immortal 2"){
        imgRango.src = "resources/imm2.png";
    }

    if (rango === "Immortal 3"){
        imgRango.src = "resources/imm3.png";
    }

    if (rango === "Radiant"){
        imgRango.src = "resources/radiant.png";
    }

    textoNick.append(imgRango)
}


