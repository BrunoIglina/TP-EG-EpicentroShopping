# Plan de Implementación: mvc-view-controllers

## Overview

Refactorización arquitectural MVC: introducir controladores de lectura (GET) por módulo en `private/logic/`, extender `public/index.php` como front controller único, y limpiar todos los `require` a `private/` de las vistas. Se eliminan también los archivos problemáticos `darAltaPromos.php` y `reportesDueño.php`.

## Tasks

- [x] 1. Eliminar archivos problemáticos
  - Eliminar `public/darAltaPromos.php` (duplicado funcional de `dueno_promocion_agregar`, con form roto)
  - Eliminar `public/reportesDueño.php` (SQL directo + PDF mezclados; funcionalidad cubierta por `dueno/reportes.php` y `private/logic/reports/`)
  - _Requirements: 1.1, 1.2_

- [x] 2. Crear `private/logic/locales.read.php`
  - Implementar el switch sobre `$vista` para los casos: `admin_locales`, `admin_local_agregar`, `admin_local_editar`, `landing`, `locales`, `promociones`
  - Para `admin_locales`: calcular `$page`, `$offset`, asignar `$locales = get_all_locales(...)`, `$total_pages`
  - Para `admin_local_agregar`: asignar `$duenos = get_all_dueños()` e incluir `rubros.php` para `$rubros`
  - Para `admin_local_editar`: validar `$_GET['id']`, asignar `$local`, `$duenos`, `$rubros`; redirigir a `admin_locales` si id inválido o local no encontrado
  - Para `landing`: asignar `$locales = get_locales_solicitados()`
  - Para `locales`: asignar `$locales = get_all_locales()`, `$rubros` (via `rubros.php`)
  - Para `promociones`: validar `$_GET['local_id']`, asignar `$local`, `$promos`, `$total_pages`, `$page`, `$categorias`
  - Garantizar que todas las variables sean array vacío o valor por defecto si la query falla (nunca undefined)
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 3. Crear `private/logic/novedades.read.php`
  - Implementar el switch sobre `$vista` para los casos: `admin_novedades`, `admin_novedad_agregar`, `admin_novedad_editar`, `novedades`
  - Para `admin_novedades`: calcular `$page`, `$offset`, asignar `$novedades`, `$total_pages`
  - Para `admin_novedad_agregar`: asignar `$categorias = get_categorias()`
  - Para `admin_novedad_editar`: validar `$_GET['id']`, asignar `$novedad`, `$categorias`; redirigir si id inválido o novedad no encontrada
  - Para `novedades`: asignar `$novedades` filtradas por usuario/tipo/categoría, `$total_pages`, `$page`
  - Garantizar valores por defecto si la query falla
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 4. Crear `private/logic/promociones.read.php`
  - Implementar el switch sobre `$vista` para los casos: `admin_promociones`, `dueno_promociones`, `dueno_solicitudes`, `dueno_reportes`, `cliente_promociones`
  - Para `admin_promociones`: calcular `$page`, `$offset`, asignar `$promociones`, `$total_pages`
  - Para `dueno_promociones`: usar `$_SESSION['user_id']`, asignar `$promociones`, `$total_pages`, `$page`
  - Para `dueno_solicitudes`: usar `$_SESSION['user_id']`, asignar `$solicitudes`, `$total_pages`, `$page`
  - Para `dueno_reportes`: asignar `$reporte = get_reporte_promos_dueno($_SESSION['user_id'])`
  - Para `cliente_promociones`: usar `$_SESSION['user_id']`, asignar `$mis_promociones`, `$total_pages`, `$page`
  - Garantizar valores por defecto si la query falla
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 5. Crear `private/logic/usuarios.read.php`
  - Implementar el switch sobre `$vista` para los casos: `admin_aprobar_clientes`, `admin_aprobar_duenos`, `cliente_perfil`, `cliente_mod_perfil`
  - Para `admin_aprobar_clientes`: calcular `$page`, `$offset`, asignar `$clientes`, `$total_pages`
  - Para `admin_aprobar_duenos`: calcular `$page`, `$offset`, asignar `$duenos`, `$total_pages`
  - Para `cliente_perfil`: asignar `$user = get_usuario($_SESSION['user_id'])`; redirigir a login si no existe
  - Para `cliente_mod_perfil`: asignar `$user = get_usuario($_SESSION['user_id'])`; redirigir a login si no existe
  - Garantizar valores por defecto si la query falla
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 6. Checkpoint — Verificar que los 4 read controllers existen y tienen sintaxis válida
  - Confirmar que los archivos `locales.read.php`, `novedades.read.php`, `promociones.read.php`, `usuarios.read.php` están en `private/logic/`
  - Verificar que cada archivo cubre todos los casos de vista documentados en el diseño
  - Preguntar al usuario si hay dudas antes de continuar

- [x] 7. Extender `public/index.php` — agregar read controllers al dispatcher GET
  - Para cada `case` del switch GET que tenga un read controller asociado, agregar el `require_once` del read controller **antes** del `require_once` de la vista
  - Casos a modificar: `landing`, `locales`, `novedades`, `admin_locales`, `admin_local_agregar`, `admin_local_editar`, `admin_novedades`, `admin_novedad_agregar`, `admin_novedad_editar`, `admin_promociones`, `admin_aprobar_clientes`, `admin_aprobar_duenos`, `promociones`, `dueno_promociones`, `dueno_solicitudes`, `dueno_reportes`, `cliente_perfil`, `cliente_promociones`, `cliente_mod_perfil`
  - Patrón por case: `require_once __DIR__ . '/../private/logic/{modulo}.read.php';` seguido del require de la vista
  - _Requirements: 3.1, 3.2_

- [x] 8. Limpiar vistas del módulo Admin — eliminar requires a `private/`
  - `public/admin/locales.php`: eliminar `require_once` a `locales.queries.php` y `usuarios.queries.php`; eliminar el bloque de cálculo de `$page`, `$offset`, `$locales`, `$total_locales`, `$total_pages` (ya vienen del read controller)
  - `public/admin/local_agregar.php`: eliminar `require_once` a `usuarios.queries.php` y `rubros.php`; eliminar `$dueños = get_all_dueños()`
  - `public/admin/local_editar.php`: eliminar `require_once` a `locales.queries.php`, `usuarios.queries.php` y `rubros.php`; eliminar el bloque de lógica de obtención de `$local` y `$dueños` (ya vienen del read controller)
  - `public/admin/novedades.php`: eliminar `require_once` a `novedades.queries.php`; eliminar bloque de cálculo de paginación y `$novedades`
  - `public/admin/novedad_agregar.php`: eliminar `require_once` a `usuarios.queries.php`; eliminar `$categorias = get_categorias()`
  - `public/admin/novedad_editar.php`: eliminar `require_once` a `usuarios.queries.php` y `novedades.queries.php`; eliminar bloque de obtención de `$novedad` y `$categorias`
  - `public/admin/promociones.php`: eliminar `require_once` a `promociones.queries.php`; eliminar bloque de paginación y `$promociones`
  - `public/admin/aprobar_clientes.php`: eliminar `require_once` a `usuarios.queries.php`; eliminar bloque de paginación y `$clientes`
  - `public/admin/aprobar_duenos.php`: eliminar `require_once` a `usuarios.queries.php`; eliminar bloque de paginación y `$duenos`
  - _Requirements: 4.1, 4.2_

- [x] 9. Limpiar vistas del módulo Dueño y Cliente — eliminar requires a `private/`
  - `public/dueno/promociones.php`: eliminar `require_once` a `promociones.queries.php` y `locales.queries.php`; eliminar bloque de cálculo de `$promociones`, `$total_rows`, `$total_pages`, `$page`
  - `public/dueno/solicitudes.php`: eliminar `require_once` a `promociones.queries.php` y `locales.queries.php`; eliminar bloque de cálculo de `$solicitudes`, `$total_items`, `$total_pages`
  - `public/dueno/reportes.php`: eliminar `require_once` a `promociones.queries.php`; eliminar `$reporte = get_reporte_promos_dueno(...)`
  - `public/dueno/promocion_agregar.php`: eliminar `require_once` a `locales.queries.php`; eliminar `$locales = get_locales_por_dueno(...)`
  - `public/client/promociones.php`: eliminar `require_once` a `rubros.php`, `locales.queries.php`, `promociones.queries.php`, `usuarios.queries.php`; eliminar bloque de obtención de `$local`, `$categorias`, `$promos`, `$total_pages`
  - `public/client/mis_promociones.php`: eliminar `require_once` a `promociones.queries.php`; eliminar bloque de `$promos`, `$total_rows`, `$total_pages`
  - `public/client/miperfil.php`: eliminar `require_once` a `usuarios.queries.php`; eliminar `$user = get_usuario(...)` y la llamada inline a `get_total_promociones_usadas_cliente()` (mover al read controller como `$total_promos_usadas`)
  - `public/client/mod_perfil.php`: eliminar `require_once` a `usuarios.queries.php` y `email.php`; eliminar `$user = get_usuario(...)`; mover la lógica POST de envío de código al read controller o mantenerla en la vista solo si no hace queries (evaluar caso a caso)
  - _Requirements: 4.1, 4.2_

- [x] 10. Limpiar vistas públicas — eliminar requires a `private/`
  - `public/landing.php`: eliminar `require_once` a `locales.queries.php` y `rubros.php`; eliminar `$locales = get_locales_solicitados()`
  - `public/locales.php`: eliminar `require_once` a `locales.queries.php` y `rubros.php`; eliminar `$locales = get_all_locales()`
  - `public/novedades.php`: eliminar `require_once` a `novedades.queries.php`; eliminar bloque de obtención de `$novedades` y paginación
  - _Requirements: 4.1, 4.2_

- [x] 11. Checkpoint final — Verificar integridad del refactor
  - Confirmar que ningún archivo en `public/` contiene `require_once` o `require` apuntando a `private/logic/queries/` ni a `private/config/`
  - Confirmar que `darAltaPromos.php` y `reportesDueño.php` no existen en `public/`
  - Confirmar que cada case del switch GET en `index.php` que necesita datos tiene su read controller correspondiente
  - Preguntar al usuario si hay dudas antes de dar por finalizado

## Notes

- Los read controllers se ejecutan en el scope de `index.php` via `require_once`, por lo que las variables quedan disponibles directamente para la vista
- La verificación de sesión/rol permanece en `index.php` (o en las vistas que ya la tienen) — los read controllers no verifican sesión
- `private/config/rubros.php` solo se incluye desde los read controllers, nunca desde las vistas
- `public/client/mod_perfil.php` tiene lógica POST mezclada — en la tarea 9 se evalúa si esa lógica se mantiene en la vista (no hace queries directas) o se mueve; el `require` a `email.php` se elimina de la vista y se incluye desde el read controller si es necesario
