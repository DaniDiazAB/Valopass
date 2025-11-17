# ValoPass

ValoPass es una aplicación web desarrollada con **JavaScript** y **PHP Vanilla** cuyo objetivo es gestionar cuentas de Valorant de forma sencilla y centralizada.  
Este proyecto **no está pensado aún para un uso real**, sigue en desarrollo activo, y todo su **CSS** así como este **README** han sido generados íntegramente por IA.

---

## 🚀 Live Demo

Puedes acceder a la versión de demostración en:

**[https://danidiaz.site/valopass/](https://danidiaz.site/valopass/)**

> ⚠️ Debido a que es una *Live Demo* pública, para evitar ataques y cumplir con la legislación:
> - **No se pueden registrar nuevos usuarios**
> - **No se pueden crear nuevas cuentas**
>
> Para entrar en la demo, tienes disponible este usuario:
> - **Usuario:** `Dani`  
> - **Contraseña:** `ABCdfg456@_`

---

## 🛠️ Tecnologías utilizadas

- **PHP Vanilla**  
- **JavaScript Vanilla**  
- **CSS generado automáticamente por IA**  
- **Composer** (para la gestión de dependencias y envío de emails)  

---

## 🔐 Seguridad y gestión de contraseñas

ValoPass incluye diferentes niveles de seguridad, tanto para usuarios como para cuentas gestionadas:

### ✔ Recuperación de contraseñas (solo versión real)
Si un usuario pierde su contraseña, puede solicitar una nueva.  
La aplicación enviará una nueva contraseña por correo electrónico.  
> *(En la Live Demo esta funcionalidad está deshabilitada)*

### ✔ Encriptación de contraseñas de cuentas
Las contraseñas de las *cuentas guardadas* se encriptan en la base de datos.  
Cuando el usuario las consulta, se **desencriptan temporalmente** para mostrarlas.

### ✔ Hash seguro para contraseñas de usuarios
Las contraseñas de los usuarios **se almacenan con hash** en la base de datos mediante funciones seguras de PHP (ej. `password_hash`).

---

## 📦 Manual de instalación

Descarga haciendo click aquí un PDF para instalar el proyecto, ya sea en localhost o en un servidor público:

https://danidiaz.site/valopass/manualinstalacion.pdf

---

### 1️⃣ Clonar el proyecto
```bash
git clone https://github.com/tu-repo/valopass.git
cd valopass

---

## Contribuciones

Este proyecto está abierto a mejoras. Si quieres contribuir:

1. Haz un fork del repositorio.
2. Crea una nueva rama con tus cambios:
   ```bash
   git checkout -b mejora/nueva-funcion
   ```
3. Envía un pull request.

---

