const title = document.getElementById("title")


document.addEventListener('DOMContentLoaded', function() {
  getCuentas(); 
});

function getCuentas(){
    fetch("http://localhost/Valopass/server/cuentas.php")
  .then(response => response.json())
  .then(data => {
    const divCuentas = document.createElement("div")
    divCuentas.id = "cuentas"
    title.insertAdjacentElement("afterend", divCuentas)



    data.forEach(cuenta => {
      getRangos(cuenta.nick_cuenta, cuenta.tag_cuenta, divCuentas)
    });
  })
  .catch(error => console.error("Error:", error));


}

function getRangos(nick, tag, divCuentas){
    const url = "http://localhost:3000/api/mmr/" + nick + "/" + tag + "/EU";

    fetch(url)
    .then(response => response.text()) 
    .then(data => {


        let miTexto = data;
        
        const textoNick = document.createElement("h3")
        textoNick.textContent = nick + ' - ' + miTexto.split(','[0])[0]
        textoNick.classList.add('nick')

        /* REUSAR PARA LA CONTRASEÑA
        const nuevoParrafo = document.createElement("h4")
        nuevoParrafo.textContent = miTexto.split(','[0])[0]
        nuevoParrafo.classList.add('rango')
        */

        divCuentas.append(textoNick)


    })
    .catch(error => {
        console.error("Error al obtener el texto:", error);
    });


}

// hRXyxcLYXebuZ2C  