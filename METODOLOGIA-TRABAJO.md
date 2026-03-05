# Roadmap y Metodología: Epicentro Shopping

## Objetivo del Proyecto

Migrar la aplicación de una estructura procedural clásica a una arquitectura de **Front Controller (MVC-lite)** con un único punto de entrada (`index.php`). Esto garantizará mayor seguridad, escalabilidad y evitará conflictos al juntar el código.

## 🗺️ Las 4 Fases de Implementación

1. **El Enrutador Central (`index.php`):** Actuará como la única puerta de entrada. Derivará las peticiones GET a las vistas y las POST a los controladores.
2. **"Enmudecer" las Vistas (Carpeta `public/`):** Eliminar toda la lógica SQL y PHP pesado de las vistas (admin, dueno, cliente). Quedarán solo como plantillas HTML.
3. **Creación de Controladores (`private/logic/`):** Mudar la lógica extraída a archivos seguros.
4. **Refactorización de la Navegación:** Actualizar todos los enlaces internos (`href`) para que apunten al Router (ej: `index.php?vista=admin_locales`).

## 🤝 Metodología de Trabajo en Equipo

- **Módulo Administrador (Bruno):** \* Responsable de las vistas en `public/admin/` y del `admin_controller.php`.
- **Módulo Dueño (Luciano):** \* Responsable de las vistas en `public/dueno/` y del `dueno_controller.php`.
- **Módulo Cliente (Santiago):** \* Responsable de las vistas en `public/cliente/` y del `cliente_controller.php`.

## ⚠️ Reglas Estrictas de Desarrollo

1. **El Router es sagrado:** Si necesitan agregar una página nueva, primero se avisa por el grupo para agregar la ruta al `switch` del `index.php`.
2. **Cero SQL en la calle:** Terminantemente prohibido usar `$conn->prepare()` o conectarse a la base de datos adentro de la carpeta `public/`.
3. **Rutas limpias:** Como ahora todo corre desde `index.php`, los llamados a los CSS e imágenes se hacen desde la raíz (ej: `href="css/style.css"`), olvidándose de los `../`.
