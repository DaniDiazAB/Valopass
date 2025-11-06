# Proyecto Web

Este proyecto es una aplicación web que utiliza **PHP** para la lógica de servidor y **MySQL** como base de datos.  
El **CSS** y este READ.ME que encontrarás en este repositorio fueron generado con ayuda de **Inteligencia Artificial (IA)**.  
Aunque esto permitió avanzar rápido, **todavía faltan algunos puntos clave por completar y mejorar**, por lo que este repositorio debe considerarse una **versión en desarrollo**.

# AVISO DEL LIVE DEMO
IMPORTANTE: El uso de la aplicación en el live demo no funciona correctamente y realmente nunca fue utilizado con un propósito de uso real. Este proyecto es únicamente una demostración técnica y educativa. Por esta razón, el proyecto cuenta con un documento .HTML de aviso al usuario que explica las limitaciones y el propósito educativo de esta demostración.

---

## Características principales

- Aplicación web con conexión a base de datos MySQL.
- Estructura modular que separa el cliente (frontend) del servidor (backend).
- Estilos generados parcialmente con IA (pueden necesitar ajustes).
- Página de muestra incluida para referencia visual y de estructura.
- Fácil de probar en local con una configuración mínima.

---

## Requisitos previos

Antes de instalar y probar el proyecto, asegúrate de tener:

- [PHP 7.4+](https://www.php.net/downloads) o superior
- [MySQL 5.7+](https://dev.mysql.com/downloads/) o superior
- Servidor web (por ejemplo [XAMPP](https://www.apachefriends.org/) o [WAMP](https://www.wampserver.com/))
- Navegador web moderno (Chrome, Firefox, Edge, etc.)

---

## Configuración inicial

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/usuario/proyecto-web.git
   ```

2. **Configurar la base de datos**  
   - Crear una nueva base de datos en MySQL.
   - Importar el archivo `database.sql` (si existe en el proyecto).
   - Editar el archivo `Server/db.php` y colocar tus credenciales de conexión:
     ```php
     $host = "localhost";  // Cambiar si usas un host distinto
     $user = "usuario";    // Tu usuario de MySQL
     $pass = "contraseña"; // Tu contraseña de MySQL
     $db   = "basedatos";  // El nombre de tu base de datos
     ```

3. **Activar la ejecución en local**  
   - Abrir el archivo principal de configuración.
   - Buscar la línea con el comentario:
     ```php
     // PARA USO LOCAL
     ```
   - **Descomentar esa línea** y **comentar la línea anterior**, para que la aplicación funcione correctamente en tu entorno local.

---

## Ejecución en local

1. Levanta tu servidor Apache/MySQL (ej. con XAMPP/WAMP).
2. Accede desde tu navegador a:
   ```
   http://localhost/proyecto-web/
   ```

---

## Página de muestra

Para ver este proyecto puedes acceder a: https://danidiaz.site/valopass/public/views/login.html
Usuario: Demo
Contraseña: UsuarioDemo

---

## Limitaciones actuales

- Faltan implementar algunas funciones clave (pendientes de desarrollo).
- Los estilos generados automáticamente pueden necesitar refactorización.
- La seguridad no ha sido auditada (no usar en producción sin revisiones).
- La documentación es inicial y se ampliará a medida que el proyecto crezca.
- En la Pre-Release puedes ver las siguientes mejoras: https://github.com/DaniDiazAB/Valopass/releases/tag/pre-relase

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

## Notas finales

Este proyecto debe entenderse como una **base inicial**:  
sirve para aprender, probar o construir sobre él, pero **no está listo para usarse directamente en un entorno de producción**.

---
