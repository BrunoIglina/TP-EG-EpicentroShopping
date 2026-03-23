# Plan de Implementación: Refactorización de `private/`

## Overview

Reorganización del módulo `private/logic/` para eliminar redundancias, consolidar las capas de acceso a datos en `queries/`, y limpiar los controladores de SQL directo y patrones frágiles (`ob_start`). Sin nuevas funcionalidades.

## Tasks

- [x] 1. Eliminar archivos legacy sin dependencias activas
  - Borrar `private/logic/aceptar_clientes.php`
  - Borrar `private/logic/aceptar_dueños.php`
  - Borrar `private/logic/scripts/generar_hash.php`
  - Verificar con grep que ningún archivo en `public/` los referencia antes de borrar
  - _Requirements: Paso 1 del diseño_

- [x] 2. Crear la carpeta `queries/` con los 4 archivos consolidados
  - [x] 2.1 Crear `private/logic/queries/usuarios.queries.php`
    - Absorber todas las funciones de `functions/functions_usuarios.php`
    - Absorber las funciones SQL de `crud/usuarios.php` (sin redirecciones ni `$_SESSION`)
    - Exponer la interfaz definida en el diseño: `get_usuario`, `registrar_cliente_query`, `aprobar_usuario_query`, etc.
    - _Requirements: Paso 2, interfaz `queries/usuarios.queries.php` del diseño_
  - [x] 2.2 Crear `private/logic/queries/locales.queries.php`
    - Absorber `functions/functions_locales.php`
    - Absorber funciones SQL de `crud/locales.php`
    - Exponer: `get_all_locales`, `crear_local_query`, `actualizar_local_query`, `eliminar_local_query`, etc.
    - _Requirements: Paso 2, interfaz `queries/locales.queries.php` del diseño_
  - [x] 2.3 Crear `private/logic/queries/promociones.queries.php`
    - Absorber `functions/functions_promociones.php` y la parte de promociones de `functions/functions_dueno.php`
    - Absorber funciones SQL de `crud/promociones.php`
    - Resolver la duplicación de `get_promociones_dueno`: mantener la versión de `functions_dueno.php`, eliminar la de `functions_promociones.php`
    - Exponer: `get_promociones_dueno`, `crear_promocion_query`, `eliminar_promocion_dueno`, `aprobar_promocion_query`, etc.
    - _Requirements: Paso 2, interfaz `queries/promociones.queries.php` del diseño_
  - [x] 2.4 Crear `private/logic/queries/novedades.queries.php`
    - Absorber `functions/functions_novedades.php`
    - Absorber funciones SQL de `crud/novedades.php`
    - Exponer: `get_all_novedades`, `crear_novedad_query`, `actualizar_novedad_query`, `eliminar_novedad_query`, etc.
    - _Requirements: Paso 2, interfaz `queries/novedades.queries.php` del diseño_

- [x] 3. Refactorizar `private/logic/admin.controller.php`
  - Reemplazar cada bloque `ob_start()` + `include(crud/...)` + `ob_get_clean()` por llamadas directas a las funciones de `queries/`
  - Agregar `require_once` a los archivos de `queries/` necesarios
  - Eliminar los `require_once` a `crud/` y `functions/` que queden sin uso
  - Mantener la verificación de `$_SESSION['user_tipo']` al inicio de cada función
  - _Requirements: Paso 3 del diseño — eliminar patrón `ob_start`_

- [x] 4. Refactorizar `private/logic/dueno.controller.php`
  - Mover cada query SQL directo (`$conn->prepare(...)`) a la función correspondiente en `queries/promociones.queries.php`
  - Reemplazar el SQL inline por llamadas a esas funciones
  - Agregar `require_once` a `queries/promociones.queries.php` y `queries/locales.queries.php` según corresponda
  - _Requirements: Paso 4 del diseño — eliminar SQL directo en controladores_

- [x] 5. Refactorizar `private/logic/auth.controller.php`
  - Reemplazar el bloque `ob_start()` + `include(crud/usuarios.php)` en `procesar_registro()` por llamadas directas a `registrar_cliente_query()` o `registrar_dueno_query()`
  - Agregar `require_once` a `queries/usuarios.queries.php`
  - Eliminar el `require_once` a `crud/usuarios.php`
  - _Requirements: Paso 5 del diseño — eliminar `ob_start` en auth_

- [x] 6. Actualizar todos los `require_once` en `public/`
  - [x] 6.1 Actualizar vistas de `public/admin/`
    - `aprobar_clientes.php`, `aprobar_duenos.php`: cambiar `functions/functions_usuarios.php` → `queries/usuarios.queries.php`
    - `locales.php`, `local_agregar.php`, `local_editar.php`: cambiar a `queries/locales.queries.php`
    - `novedades.php`, `novedad_agregar.php`, `novedad_editar.php`: cambiar a `queries/novedades.queries.php`
    - `promociones.php`: cambiar a `queries/promociones.queries.php`
    - _Requirements: Paso 6 del diseño_
  - [x] 6.2 Actualizar vistas de `public/dueno/`
    - `promociones.php`, `solicitudes.php`, `reportes.php`: cambiar `functions/functions_dueno.php` → `queries/promociones.queries.php`
    - _Requirements: Paso 6 del diseño_
  - [x] 6.3 Actualizar vistas de `public/client/`
    - `miperfil.php`, `mod_perfil.php`: cambiar `functions/functions_usuarios.php` → `queries/usuarios.queries.php`
    - `mis_promociones.php`, `promociones.php`: cambiar `functions/functions_promociones.php` → `queries/promociones.queries.php`
    - _Requirements: Paso 6 del diseño_
  - [x] 6.4 Actualizar vistas raíz de `public/`
    - `locales.php`: cambiar `functions/functions_locales.php` → `queries/locales.queries.php`
    - `novedades.php`: cambiar `functions/functions_novedades.php` → `queries/novedades.queries.php`
    - Verificar `landing.php`, `index.php` y cualquier otro archivo con requires a `functions/` o `crud/`
    - _Requirements: Paso 6 del diseño_

- [x] 7. Checkpoint — verificar que todos los requires estén actualizados
  - Ejecutar `grep -r "functions_\|/crud/" public/` y confirmar que no hay resultados
  - Ejecutar `grep -r "functions_\|/crud/" private/logic/*.controller.php` y confirmar que no hay resultados
  - Solo continuar al paso 8 si ambas búsquedas dan vacío

- [x] 8. Eliminar las carpetas `functions/` y `crud/`
  - Borrar `private/logic/functions/` completa (5 archivos)
  - Borrar `private/logic/crud/` completa (4 archivos)
  - _Requirements: Paso 7 del diseño — solo ejecutar después del checkpoint_

- [x] 9. Deprecar `public/router.php`
  - Verificar con `grep -r "router\.php" public/` que ninguna vista lo referencia
  - Borrar `public/router.php`
  - _Requirements: Paso 8 del diseño_

## Notes

- El orden de los pasos es estricto: no eliminar `functions/` ni `crud/` hasta que el paso 7 (checkpoint) esté verde
- Las funciones de `queries/` no deben contener `$_SESSION`, `header()`, ni `$_POST`/`$_GET` — esa lógica pertenece al controlador
- Para funciones de escritura simples retornar `bool`; para operaciones con validaciones de negocio retornar `['success' => bool, 'message' => string]`
- Mantener `error_log()` donde ya existe; no agregar ni quitar
- `reports/check_promociones.php`, `reports/generarInforme.php` y `helpers/procesar_contacto.php` tienen su propio `session_start()` porque son endpoints directos — no tocarlos
