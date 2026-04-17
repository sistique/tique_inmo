# AGENTS.md — tique_inmo (Administración de Inmuebles)

## Arquitectura General

Aplicación PHP 8.3 MVC construida sobre el ecosistema de framework `gamboamartin`. El punto de entrada es `index.php` → `init.php` → `base\controller\init`, que rutea mediante `$_GET['seccion']` y `$_GET['accion']` hacia la clase controladora y método correspondiente. La configuración está en `config/` (clases PHP planas, sin archivos `.env`). Base de datos MySQL (db `tique`, `config/database.php`). Locale `es_MX`, zona horaria `America/Mexico_City`.

## Entidades de Dominio y Flujo de Datos

El pipeline de negocio es: **Prospecto → Prospecto+Ubicación → Comprador → Ubicación (propiedad)**. Cada entidad tiene su propia bitácora de status (`inm_bitacora_status_*`) y seguimiento de proceso (`inm_*_proceso`). Las relaciones clave se gestionan con tablas puente `inm_rel_*` (ej: `inm_rel_ubi_comp` vincula comprador con propiedad).

## Módulo Financiero (Presupuestos y Contabilidad Real)

Dos flujos de datos paralelos — **NUNCA se mezclan** en la misma tabla:
- **`inm_presupuesto`**: Ingresos/egresos proyectados mensuales por categoría. Columnas calculadas (`columnas_extra`) auto-calculan totales reales, diferencia y % cumplimiento mediante subconsultas contra `inm_mov_real`.
- **`inm_mov_real`**: Movimientos financieros reales (ingresos y egresos). `anio`/`mes` se auto-derivan de `fecha` al insertar.
- **`inm_categoria_financiera`**: Catálogo de categorías financieras (mapea a cuentas contables vía `cuenta_contable`).
- **`inm_tipo_movimiento`**: Catálogo de tipos de movimiento con bandera `es_ingreso` (`activo`=ingreso, `inactivo`=egreso).

Acciones clave: `dashboard` (KPIs + gráficas Chart.js), `comparativa` (tabla presupuesto vs real), `reporte_mensual` (desglose mensual). Ver `docs/MODULO_FINANCIERO.md` para consultas SQL y referencia completa.

## Referencia del Esquema de Base de Datos

- `docs/DATABASE.md` — Vista general de todas las tablas por módulo con relaciones
- `docs/schema_completo.sql` — Dump DDL completo
- `docs/schema_inm_tables.md` — Detalle columna por columna de todas las tablas `inm_*`
- `docs/schema_soporte_tables.md` — Detalle columna por columna de tablas de soporte (adm, com, fc, etc.)
- `docs/schema_foreign_keys.md` — Mapa de llaves foráneas

Entidades principales:
- **`inm_ubicacion`**: Propiedad física. Vinculada a dirección postal (`dp_*`), prototipo, tipo de crédito, agente, status.
- **`inm_prospecto`**: Lead/prospecto. Vinculado a `com_agente`, `com_tipo_prospecto`.
- **`inm_comprador`**: Comprador. Entidad pesada con campos de crédito INFONAVIT, datos fiscales SAT, cuentas bancarias.

## Convenciones de Estructura del Proyecto

| Directorio | Namespace | Propósito |
|-----------|-----------|---------|
| `controllers/` | `gamboamartin\inmuebles\controllers` | Clases controladoras. Nombrado: `controlador_{entidad}.php` |
| `orm/` | `gamboamartin\inmuebles\models` | Modelos (ORM). Nombrado: `{entidad}.php` |
| `templates/directivas/` | `gamboamartin\inmuebles\html` | Renderizadores HTML. Nombrado: `{entidad}_html.php` |
| `templates/inputs/` | — | Parciales de inputs por entidad, ej: `inm_ubicacion/alta.php` |
| `config/` | `config` | `generales.php`, `database.php`, `views.php`, `pac.php` (timbrado CFDI) |
| `instalacion/` | `gamboamartin\inmuebles\instalacion` | Migraciones de esquema BD vía `_instalacion->create_table_new()` |
| `tests/` | `gamboamartin\inmuebles\tests` | Pruebas PHPUnit, organizadas en subdirectorios `controllers/` y `orm/` |

## Patrones de Nombrado y Codificación

- **Controladores** extienden `gamboamartin\system\_ctl_base`. Formato: `controlador_{nombre_tabla}` (ej: `controlador_inm_ubicacion`).
- **Modelos** extienden `_modelo_parent` (mediante cadena como `_base → _inm_ubicaciones → inm_ubicacion`). El constructor define `$columnas` (mapa de JOINs), `$campos_obligatorios`, `$columnas_extra` (expresiones SQL) y `$atributos_criticos`.
- **Clases helper/trait** con prefijo `_` (ej: `_keys_selects.php`, `_pdf.php`, `_base.php`, `_dropbox.php`) contienen lógica reutilizable separada por responsabilidad, NO son clases base abstractas.
- **Manejo de errores**: Siempre usar `gamboamartin\errores\errores`. El patrón es: llamar método → verificar bandera estática `errores::$error` → propagar con `$this->error->error(mensaje: '...', data: $result)`. Nunca usar try/catch para lógica de negocio.
- **Argumentos nombrados** se usan en todo el código (`mensaje:`, `data:`, `link:`, etc.). Siempre usarlos al llamar métodos del framework.

## Patrón de Manejo de Errores (Crítico)

```php
$result = $modelo->alta_bd();
if(errores::$error){
    return $this->error->error(mensaje: 'Error al insertar', data: $result);
}
```
Cada llamada a método que pueda fallar DEBE ir seguida de esta verificación. La bandera estática `errores::$error` es global — reiniciarla con `errores::$error = false;` al inicio de métodos de prueba.

## Patrón del Constructor del Modelo

```php
public function __construct(PDO $link) {
    $tabla = 'inm_ubicacion';
    $columnas = array($tabla => false, 'dp_colonia_postal' => $tabla, ...); // Mapa de JOINs
    $campos_obligatorios = array('inm_tipo_ubicacion_id');
    parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas, ...);
    $this->NAMESPACE = __NAMESPACE__;
}
```

## Integraciones Externas

- **Dropbox**: Almacenamiento de archivos vía `orm/_dropbox.php` (API REST, auto-refresh de token desde tabla `inm_token_dropbox`). Config en `config/generales.php`.
- **CFDI 4.0 / PAC**: Timbrado de facturas vía `gamboa.martin/facturacion` + `gamboa.martin/xml_cfdi_4`. Config del PAC en `config/pac.php` (proveedor Facturalo).
- **Notificaciones por email**: `orm/_email.php` vía `gamboa.martin/notificaciones` + PHPMailer.
- **Generación de PDF**: `controllers/_pdf.php` usando FPDI/FPDF para formularios con plantilla (solicitudes INFONAVIT, avalúos).

## Base de Datos e Instalación

- Ejecutar `__inicializa.php` para crear el esquema. Instala módulos en orden de dependencias: administrador → cat_sat → organigrama → comercial → facturacion → inmuebles. Todo dentro de una sola transacción.
- Las migraciones en `instalacion/instalacion.php` usan `_instalacion->create_table_new()` y `$init->campos_double()` — no archivos SQL.
- No hay `phpunit.xml` en la raíz del proyecto; las pruebas usan la clase base `gamboa.martin/test` que se conecta automáticamente vía `paths_conf` apuntando a archivos de configuración.

## Pruebas

- Las pruebas extienden `gamboamartin\test\test` y usan `gamboamartin\test\liberator` para acceder a métodos privados.
- La preparación de datos de prueba usa `tests/base_test.php` que proporciona métodos factory `alta_inm_*()` y cadenas de limpieza `del_inm_*()` (respetando orden de FKs).
- Las pruebas requieren que estén definidos `$_GET['seccion']`, `$_GET['accion']`, `$_SESSION['grupo_id']`, `$_SESSION['usuario_id']`, `$_GET['session_id']`.
- Las rutas de config en pruebas apuntan a `/var/www/html/inmuebles/config/` (diferente de la ruta en tiempo de ejecución).

## Dependencias Clave (ecosistema gamboamartin)

Todos los paquetes `gamboa.martin/*` siguen las mismas convenciones MVC + manejo de errores. Los principales son:
- `administrador`: Sistema de usuarios/grupos/permisos (tablas `adm_*`)
- `system`: Controlador base (`_ctl_base`), ruteo, `links_menu`
- `direccion_postal`: Jerarquía de dirección postal mexicana (`dp_pais → dp_estado → dp_municipio → dp_cp → dp_colonia_postal → dp_calle_pertenece`)
- `comercial`: Agentes, prospectos, clientes (tablas `com_*`)
- `facturacion`: Facturación CFDI (tablas `fc_*`)
- `proceso`: Máquina de estados de flujo de trabajo/procesos (tablas `pr_*`)
