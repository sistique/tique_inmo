# AGENTS.md — tique_inmo (Administración de Inmuebles)

## Architecture Overview

PHP 8.3 MVC app built on the `gamboamartin` framework ecosystem. Entry point is `index.php` → `init.php` → `base\controller\init` which routes via `$_GET['seccion']` and `$_GET['accion']` to the matching controller class and method. Configuration lives in `config/` (plain PHP classes, no `.env` files). Database is MySQL (`tique` db, `config/database.php`). Locale is `es_MX`, timezone `America/Mexico_City`.

## Core Domain Entities & Data Flow

The business pipeline is: **Prospecto → Prospecto+Ubicación → Comprador → Ubicación (property)**. Each entity has its own status bitácora (`inm_bitacora_status_*`) and process tracking (`inm_*_proceso`). Key relationships are managed through `inm_rel_*` bridge tables (e.g., `inm_rel_ubi_comp` links a buyer to a property).

- **`inm_ubicacion`**: Physical property. Linked to postal address (`dp_*`), prototype, credit type, agent, status.
- **`inm_prospecto`**: Lead/prospect. Links to `com_agente`, `com_tipo_prospecto`.
- **`inm_comprador`**: Buyer. Heavy entity with INFONAVIT credit fields, SAT fiscal data, bank accounts.

## Project Structure Conventions

| Directory | Namespace | Purpose |
|-----------|-----------|---------|
| `controllers/` | `gamboamartin\inmuebles\controllers` | Controller classes. File naming: `controlador_{entity}.php` |
| `orm/` | `gamboamartin\inmuebles\models` | Models (ORM). File naming: `{entity}.php` |
| `templates/directivas/` | `gamboamartin\inmuebles\html` | HTML renderers. File naming: `{entity}_html.php` |
| `templates/inputs/` | — | Blade-like input partials per entity, e.g. `inm_ubicacion/alta.php` |
| `config/` | `config` | `generales.php`, `database.php`, `views.php`, `pac.php` (CFDI stamping) |
| `instalacion/` | `gamboamartin\inmuebles\instalacion` | DB schema migrations via `_instalacion->create_table_new()` |
| `tests/` | `gamboamartin\inmuebles\tests` | PHPUnit tests, organized in `controllers/` and `orm/` subdirs |

## Naming & Coding Patterns

- **Controllers** extend `gamboamartin\system\_ctl_base`. Name format: `controlador_{table_name}` (e.g., `controlador_inm_ubicacion`).
- **Models** extend `_modelo_parent` (via chain like `_base → _inm_ubicaciones → inm_ubicacion`). Constructor defines `$columnas` (join map), `$campos_obligatorios`, `$columnas_extra` (SQL expressions), and `$atributos_criticos`.
- **Helper/trait-like classes** prefixed with `_` (e.g., `_keys_selects.php`, `_pdf.php`, `_base.php`, `_dropbox.php`) contain reusable logic split by concern, NOT abstract base classes.
- **Error handling**: Always use `gamboamartin\errores\errores`. Pattern is: call method → check `errores::$error` static flag → propagate with `$this->error->error(mensaje: '...', data: $result)`. Never use try/catch for business logic.
- **Named arguments** are used throughout (`mensaje:`, `data:`, `link:`, etc.). Always use them when calling framework methods.

## Error Handling Pattern (Critical)

```php
$result = $modelo->alta_bd();
if(errores::$error){
    return $this->error->error(mensaje: 'Error al insertar', data: $result);
}
```
Every method call that can fail MUST be followed by this check. The static `errores::$error` flag is global — reset it with `errores::$error = false;` at the start of test methods.

## Model Constructor Pattern

```php
public function __construct(PDO $link) {
    $tabla = 'inm_ubicacion';
    $columnas = array($tabla => false, 'dp_colonia_postal' => $tabla, ...); // JOIN map
    $campos_obligatorios = array('inm_tipo_ubicacion_id');
    parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas, ...);
    $this->NAMESPACE = __NAMESPACE__;
}
```

## External Integrations

- **Dropbox**: File storage via `orm/_dropbox.php` (REST API, token auto-refresh from `inm_token_dropbox` table). Config in `config/generales.php`.
- **CFDI 4.0 / PAC**: Invoice stamping via `gamboa.martin/facturacion` + `gamboa.martin/xml_cfdi_4`. PAC config in `config/pac.php` (Facturalo provider).
- **Email notifications**: `orm/_email.php` via `gamboa.martin/notificaciones` + PHPMailer.
- **PDF generation**: `controllers/_pdf.php` using FPDI/FPDF for template-based forms (solicitudes INFONAVIT, avalúos).

## Database & Installation

- Run `__inicializa.php` to bootstrap schema. It installs modules in dependency order: administrador → cat_sat → organigrama → comercial → facturacion → inmuebles. All within a single transaction.
- Migrations in `instalacion/instalacion.php` use `_instalacion->create_table_new()` and `$init->campos_double()` — not SQL files.
- No phpunit.xml in project root; tests use `gamboa.martin/test` base class which auto-connects via `paths_conf` pointing to config files.

## Testing

- Tests extend `gamboamartin\test\test` and use `gamboamartin\test\liberator` to access private methods.
- Test data setup uses `tests/base_test.php` which provides `alta_inm_*()` factory methods and `del_inm_*()` teardown chains (respecting FK order).
- Tests require `$_GET['seccion']`, `$_GET['accion']`, `$_SESSION['grupo_id']`, `$_SESSION['usuario_id']`, `$_GET['session_id']` to be set.
- Config paths in tests point to `/var/www/html/inmuebles/config/` (different from runtime path).

## Key Dependencies (gamboamartin ecosystem)

All `gamboa.martin/*` packages follow the same MVC + error-handling conventions. Key ones:
- `administrador`: User/group/permission system (`adm_*` tables)
- `system`: Base controller (`_ctl_base`), routing, `links_menu`
- `direccion_postal`: Mexican postal address hierarchy (`dp_pais → dp_estado → dp_municipio → dp_cp → dp_colonia_postal → dp_calle_pertenece`)
- `comercial`: Agents, prospects, clients (`com_*` tables)
- `facturacion`: CFDI invoicing (`fc_*` tables)
- `proceso`: Workflow/process state machine (`pr_*` tables)

