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

document.addEventListener("DOMContentLoaded", () => {
  const banner = document.getElementById("cookie-banner");
  const acceptBtn = document.getElementById("acceptCookies");
  const rejectBtn = document.getElementById("rejectCookies");
  /*  
      const consent = localStorage.getItem("cookieConsent");

      if (!consent) banner.classList.remove("hidden");

      acceptBtn.addEventListener("click", () => {
        localStorage.setItem("cookieConsent", "accepted");
        banner.classList.add("hidden");
      });

      rejectBtn.addEventListener("click", () => {
        localStorage.setItem("cookieConsent", "rejected");
        banner.classList.add("hidden");
        const divLogin = document.getElementById("div-login")
        divLogin.setAttribute("hidden", "")
      });
      */
});