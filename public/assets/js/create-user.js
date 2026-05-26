function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggle = document.querySelector(".toggle-password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggle.textContent = "🙈";
      } else {
        passwordInput.type = "password";
        toggle.textContent = "👁️";
      }
    }

    const btnForm = document.getElementById("btn-crear-cuenta")

    const passwordUno = document.getElementById("password")
    const passwordDos = document.getElementById("password-confirmar")

    const mensajePass = document.getElementById("mensaje-pass")
    const mensajeSegura = document.getElementById("mensaje-segura")

    function validarPasswords() {
      
      const passwordSegura = esPasswordSegura(passwordUno.value)
      
      if (passwordSegura) {
        if (passwordUno.value != passwordDos.value) {
          btnForm.disabled = true;
          mensajePass.removeAttribute("hidden")
        } else {
          btnForm.disabled = false;
          mensajePass.setAttribute("hidden", "")

        }
        mensajeSegura.setAttribute("hidden", "")
      } else {
        mensajeSegura.removeAttribute("hidden")
      }
      
    } 

    function esPasswordSegura(password) {
      const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{6,}$/;
      return regex.test(password);
    }

    passwordUno.addEventListener("input", validarPasswords);
    passwordDos.addEventListener("input", validarPasswords);