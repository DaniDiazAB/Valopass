const btnBuscar = document.getElementById("buscar")

const puntos = document.getElementById("puntos")

btnBuscar.addEventListener("click", function(){
    getRangos();
})

function getRangos(){
    const nick = document.getElementById("nick")
    const tag = document.getElementById("tag")


     // URL de la página que devuelve el texto
    const url = "http://localhost:3000/api/mmr/" + nick.value + "/" + tag.value + "/EU";

    // Usando fetch para obtener el contenido
    fetch(url)
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {
        // Aquí "data" contiene el texto devuelto por la página
        let miTexto = data;
        console.log(miTexto.split(','[0])[0]);


    })
    .catch(error => {
        console.error("Error al obtener el texto:", error);
    });


}

// hRXyxcLYXebuZ2C  