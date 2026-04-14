<?php
/**
 * Script para generar docs/schema.md con la estructura completa de la BD.
 * Ejecutar: php gen_schema_doc.php
 */
require 'init.php';
require 'vendor/autoload.php';

use base\conexion;

$con = new conexion();
$link = conexion::$link;

// --- Get tables ---
$stmt = $link->query("
    SELECT TABLE_NAME, TABLE_ROWS, TABLE_COMMENT
    FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE'
    ORDER BY TABLE_NAME
");
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Get columns ---
$stmt = $link->query("
    SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    ORDER BY TABLE_NAME, ORDINAL_POSITION
");
$all_cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
$columns = [];
foreach ($all_cols as $c) {
    $columns[$c['TABLE_NAME']][] = $c;
}

// --- Get FKs ---
$stmt = $link->query("
    SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL
    ORDER BY TABLE_NAME, COLUMN_NAME
");
$all_fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
$fks = [];
foreach ($all_fks as $fk) {
    $fks[$fk['TABLE_NAME'] . '.' . $fk['COLUMN_NAME']] = $fk['REFERENCED_TABLE_NAME'] . '.' . $fk['REFERENCED_COLUMN_NAME'];
}

// --- Group by prefix ---
$prefixes_map = [
    'cat_sat_' => 'cat_sat', 'adm_' => 'adm', 'dp_' => 'dp', 'com_' => 'com',
    'fc_' => 'fc', 'inm_' => 'inm', 'doc_' => 'doc', 'org_' => 'org',
    'pr_' => 'pr', 'not_' => 'not', 'bn_' => 'bn', 'nom_' => 'nom',
    'em_' => 'em', 'im_' => 'im', 'comi_' => 'comi'
];
$labels = [
    'inm' => 'Inmuebles (inm_*) — DOMINIO PRINCIPAL',
    'adm' => 'Administrador (adm_*)',
    'com' => 'Comercial (com_*)',
    'dp' => 'Dirección Postal (dp_*)',
    'cat_sat' => 'Catálogos SAT (cat_sat_*)',
    'fc' => 'Facturación (fc_*)',
    'org' => 'Organigrama (org_*)',
    'pr' => 'Procesos (pr_*)',
    'doc' => 'Documentos (doc_*)',
    'not' => 'Notificaciones (not_*)',
    'bn' => 'Banco (bn_*)',
    'nom' => 'Nómina (nom_*)',
    'em' => 'Empleados (em_*)',
    'im' => 'IMSS/Registro Patronal (im_*)',
    'comi' => 'Comisiones (comi_*)',
    '_other' => 'Otros',
];
$order = ['inm','adm','com','dp','cat_sat','fc','org','pr','doc','not','bn','nom','em','im','comi','_other'];

$groups = [];
foreach ($tables as $t) {
    $name = $t['TABLE_NAME'];
    $prefix = '_other';
    foreach ($prefixes_map as $p => $g) {
        if (str_starts_with($name, $p)) {
            $prefix = $g;
            break;
        }
    }
    $groups[$prefix][] = $t;
}

// --- Build Markdown ---
$out = [];
$out[] = "# Schema de Base de Datos — tique";
$out[] = "";
$out[] = "**Base de datos:** tique | **Total tablas:** " . count($tables) . " | **Generado:** " . date('Y-m-d');
$out[] = "";
$out[] = "---";
$out[] = "";
$out[] = "## Índice por módulo";
$out[] = "";
foreach ($order as $g) {
    if (!isset($groups[$g])) continue;
    $label = $labels[$g] ?? $g;
    $count = count($groups[$g]);
    $out[] = "- **{$label}** — {$count} tablas";
}
$out[] = "";
$out[] = "---";
$out[] = "";

foreach ($order as $g) {
    if (!isset($groups[$g])) continue;
    $label = $labels[$g] ?? $g;
    $out[] = "## {$label}";
    $out[] = "";

    foreach ($groups[$g] as $t) {
        $tname = $t['TABLE_NAME'];
        $out[] = "### `{$tname}`";
        $out[] = "";
        $out[] = "| Column | Type | Key | FK → | Nullable | Default |";
        $out[] = "|--------|------|-----|------|----------|---------|";

        if (isset($columns[$tname])) {
            foreach ($columns[$tname] as $c) {
                $fk_key = $tname . '.' . $c['COLUMN_NAME'];
                $fk_str = isset($fks[$fk_key]) ? '`' . $fks[$fk_key] . '`' : '';
                $key_str = $c['COLUMN_KEY'] ?: '';
                $default = ($c['COLUMN_DEFAULT'] !== null) ? $c['COLUMN_DEFAULT'] : '';
                $nullable = $c['IS_NULLABLE'] === 'YES' ? '✓' : '';
                $out[] = "| `{$c['COLUMN_NAME']}` | `{$c['COLUMN_TYPE']}` | {$key_str} | {$fk_str} | {$nullable} | {$default} |";
            }
        }
        $out[] = "";
    }
}

$content = implode("\n", $out);

// Ensure docs dir exists
if (!is_dir(__DIR__ . '/docs')) {
    mkdir(__DIR__ . '/docs', 0755, true);
}

file_put_contents(__DIR__ . '/docs/schema.md', $content);

echo "✅ Generado: docs/schema.md\n";
echo "   Tablas: " . count($tables) . "\n";
echo "   Tamaño: " . number_format(strlen($content)) . " chars\n";
echo "   Líneas: " . count($out) . "\n";

