# Rehearsal-Cloud-Web

## Descripción

**Rehearsal-Cloud-Web** es una aplicación web para la gestión y carga de pistas musicales, orientada a líderes de alabanza y músicos. Permite subir canciones, visualizar el catálogo, gestionar usuarios y descargar archivos asociados a cada track.

## Propósito

El propósito del sitio es facilitar la administración y acceso a los datos que se encuentran dentro del API del proyecto de moviles asi como emular el comportamiento de una APP que tiene tambien acceso web para sus usuarios

- Subir canciones con archivos ZIP y carátulas.
- Visualizar y descargar todas las canciones registradas.



## Requisitos

- **XAMPP** (o cualquier stack PHP similar)
- **PHP 8.0+**
- **Composer** (opcional, si agregas dependencias externas)
- **API Backend** .NET corriendo y accesible desde la web

## Instalación y Ejecución

1. **Clona o copia este repositorio** dentro de la carpeta `htdocs` de tu instalación de XAMPP:

   ```
   C:\xampp\htdocs\Rehearsal-Cloud-Web
   ```

2. **Asegúrate de que XAMPP esté corriendo** (al menos Apache y MySQL si usas base de datos).

3. **Configura la URL de la API**  
   El archivo de configuración de la URL base de la API está en:

   ```
   WebMoviles/data/ApiHandler.php
   ```

   Busca la línea:

   ```php
   private const BASE_URL = "http://localhost:5198/api";
   ```

   Si tu API corre en otra URL o puerto, **modifica este valor** según corresponda.

4. **Accede al sitio desde tu navegador**:

   ```
   http://localhost/Rehearsal-Cloud-Web/WebMoviles/
   ```

   Por defecto, serás redirigido a la pantalla de login. Ahi podras crear un usuario tanto para el sitio web como para el app

5. **Carga de archivos**  
   Los archivos subidos (ZIP e imágenes) se almacenan en la carpeta:

   ```
   WebMoviles/uploads/
   ```

   Asegúrate de que esta carpeta tenga permisos de escritura.

## Estructura principal

- `WebMoviles/view/` — Vistas HTML y PHP del sitio.
- `WebMoviles/action/` — Lógica de backend para formularios y acciones.
- `WebMoviles/business/` — Lógica de negocio.
- `WebMoviles/data/` — Acceso a la API y configuración.
- `WebMoviles/css/` — Hojas de estilo.
- `WebMoviles/js/` — Scripts JavaScript.
- `WebMoviles/uploads/` — Archivos subidos por los usuarios.

## Notas adicionales

- Si la API no está corriendo o la URL es incorrecta, el sitio no podrá mostrar ni cargar canciones.
- Puedes personalizar los estilos en `WebMoviles/css/`.
- Si necesitas soporte, utiliza el botón de ayuda integrado en la navegación.

---

**¡Listo! Ahora puedes comenzar a usar Rehearsal-Cloud-Web en tu entorno local.**