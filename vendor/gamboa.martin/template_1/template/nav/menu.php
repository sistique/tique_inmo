<?php
/** @var stdClass $links_menu viene de links  */
/** @var base\controller\controler $controlador viene de links  */
use config\generales;
use config\views;

$session_id   = (new generales())->session_id;
$titulo_sys   = strtoupper((new views())->titulo_sistema);
$sec_activa   = $_GET['seccion'] ?? '';
$menu_id_act  = (int)($_GET['adm_menu_id'] ?? -1);

// ── Icono por título de menú (adm_menu_titulo) ──────────────────────────────
$iconos_menu = [
    'administraci' => 'bi-gear-wide-connected',
    'general'      => 'bi-list-task',
    'organigrama'  => 'bi-diagram-3',
    'proceso'      => 'bi-arrow-repeat',
    'empresa'      => 'bi-buildings',
    'region'       => 'bi-map',
    'nomina'       => 'bi-cash-coin',
    'factur'       => 'bi-receipt',
    'cliente'      => 'bi-person-check',
    'comprador'    => 'bi-person-check',
    'documento'    => 'bi-file-earmark-text',
    'ubicaci'      => 'bi-house',
    'infonavit'    => 'bi-bank',
    'banco'        => 'bi-bank2',
    'costo'        => 'bi-calculator',
    'prospecto'    => 'bi-person-plus',
    'reporte'      => 'bi-bar-chart-line',
    'configuraci'  => 'bi-sliders',
    'cat'          => 'bi-tags',
    'pago'         => 'bi-credit-card',
    'inmueble'     => 'bi-building',
    'financi'      => 'bi-currency-dollar',
];

// ── Icono por prefijo de sección ────────────────────────────────────────────
$iconos_seccion = [
    'inm_financiero'          => 'bi-currency-dollar',
    'inm_mov_real'            => 'bi-currency-dollar',
    'inm_presupuesto'         => 'bi-calculator',
    'inm_dashboard'           => 'bi-speedometer2',
    'inm_prospecto_ubicacion' => 'bi-geo-alt',
    'inm_prospecto'           => 'bi-person-plus',
    'inm_comprador'           => 'bi-person-check',
    'inm_co_acreditado'       => 'bi-people',
    'inm_conyuge'             => 'bi-heart',
    'inm_beneficiario'        => 'bi-person-lines-fill',
    'inm_avaluo'              => 'bi-clipboard-data',
    'inm_ubicacion'           => 'bi-building',
    'inm_prototipo'           => 'bi-layout-wtf',
    'inm_cheque'              => 'bi-check2-square',
    'inm_complemento'         => 'bi-file-earmark-plus',
    'inm_conf'                => 'bi-sliders',
    'inm_categoria'           => 'bi-tag',
    'inm_tipo'                => 'bi-list-ul',
    'inm_doc'                 => 'bi-file-earmark-text',
    'inm_'                    => 'bi-house',
    'adm_reporte'             => 'bi-bar-chart-line',
    'adm_usuario'             => 'bi-person-gear',
    'adm_grupo'               => 'bi-people-fill',
    'adm_menu'                => 'bi-menu-button-wide',
    'adm_seccion'             => 'bi-grid',
    'adm_accion'              => 'bi-lightning',
    'adm_'                    => 'bi-gear',
    'com_agente'              => 'bi-person-badge',
    'com_prospecto'           => 'bi-megaphone',
    'com_'                    => 'bi-briefcase',
    'bn_'                     => 'bi-bank',
    'dp_'                     => 'bi-geo',
    'cat_'                    => 'bi-tags',
    'doc_'                    => 'bi-folder',
    'fc_'                     => 'bi-receipt',
];

function _sb_icono_menu(string $titulo, array $mapa): string {
    $titulo_low = mb_strtolower($titulo);
    foreach ($mapa as $kw => $icon) {
        if (str_contains($titulo_low, $kw)) return $icon;
    }
    return 'bi-circle';
}

function _sb_icono_seccion(string $seccion, array $mapa): string {
    foreach ($mapa as $prefix => $icon) {
        if (str_starts_with($seccion, $prefix)) return $icon;
    }
    return 'bi-circle';
}
?>

<!-- ===== BRAND ===== -->
<div class="sidebar-header">
    <div class="sidebar-brand">
        <a role="button" class="btn btn-sm btn-outline-secondary me-1"
           href="<?php echo $links_menu->adm_session->inicio ?>">
            <i class="bi bi-buildings-fill sidebar-brand-icon"></i>
            <span class="sidebar-brand-text"><?php echo htmlspecialchars($titulo_sys); ?></span>
        </a>
    </div>
    <!--}<button class="sidebar-collapse-btn" id="sidebar-collapse-btn" title="Colapsar menú">
        <i class="bi bi-chevron-left"></i>
    </button>-->
</div>

<!-- ===== NAV ===== -->
<nav class="sidebar-nav">
    <ul class="sidebar-menu" id="sidebar-menu">

        <?php
        $menu_permitido = $controlador->menu_permitido ?? [];

        foreach ($menu_permitido as $menu):
            if (!is_array($menu)) continue;

            $menu_id    = (int)($menu['adm_menu_id'] ?? 0);
            $menu_label = trim($menu['adm_menu_titulo'] ?? '');
            if ($menu_label === '') continue;

            $secciones  = $menu['adm_secciones'] ?? [];
            $icono_menu = _sb_icono_menu($menu_label, $iconos_menu);

            // ¿Hay secciones para mostrar?
            $tiene_secciones = !empty($secciones);

            // ¿Es el grupo activo? (alguna de sus secciones está activa)
            $grupo_activo = false;
            foreach ($secciones as $sec) {
                if (($sec['adm_seccion_descripcion'] ?? '') === $sec_activa) {
                    $grupo_activo = true;
                    break;
                }
            }
            $open_class = $grupo_activo ? ' open' : '';
        ?>

        <?php if ($tiene_secciones): ?>
        <!-- Grupo colapsable -->
        <li class="sidebar-menu-group<?php echo $open_class; ?>">
            <button class="sidebar-menu-link sidebar-group-toggle" type="button"
                    aria-expanded="<?php echo $grupo_activo ? 'true' : 'false'; ?>"
                    title="<?php echo htmlspecialchars($menu_label); ?>">
                <i class="bi <?php echo $icono_menu; ?> sidebar-menu-icon"></i>
                <span class="sidebar-menu-label"><?php echo htmlspecialchars($menu_label); ?></span>
                <i class="bi bi-chevron-down sidebar-chevron ms-auto"></i>
            </button>

            <ul class="sidebar-submenu">
                <?php foreach ($secciones as $sec):
                    if (!is_array($sec)) continue;
                    $nombre   = $sec['adm_seccion_descripcion'] ?? '';
                    if ($nombre === '') continue;
                    $etiqueta = trim($sec['adm_seccion_etiqueta_label'] ?? '');
                    if ($etiqueta === '') $etiqueta = $nombre;
                    $etiqueta = ucwords(str_replace('_', ' ', $etiqueta));
                    $icono_s  = _sb_icono_seccion($nombre, $iconos_seccion);
                    $link     = $links_menu->$nombre->lista ?? '#';
                    $activo   = ($sec_activa === $nombre) ? ' active' : '';
                ?>
                <li class="sidebar-submenu-item<?php echo $activo; ?>">
                    <a class="sidebar-submenu-link" href="<?php echo htmlspecialchars($link); ?>"
                       title="<?php echo htmlspecialchars($etiqueta); ?>">
                        <i class="bi <?php echo $icono_s; ?> sidebar-submenu-icon"></i>
                        <span><?php echo htmlspecialchars($etiqueta); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>

        <?php else: ?>
        <!-- Menú directo sin submenú -->
        <?php
            $link_dir = "index.php?seccion=adm_session&accion=inicio&session_id={$session_id}&adm_menu_id={$menu_id}";
            $activo_dir = ($menu_id_act === $menu_id) ? ' active' : '';
        ?>
        <li class="sidebar-menu-item<?php echo $activo_dir; ?>">
            <a class="sidebar-menu-link" href="<?php echo $link_dir; ?>"
               title="<?php echo htmlspecialchars($menu_label); ?>">
                <i class="bi <?php echo $icono_menu; ?> sidebar-menu-icon"></i>
                <span class="sidebar-menu-label"><?php echo htmlspecialchars($menu_label); ?></span>
            </a>
        </li>
        <?php endif; ?>

        <?php endforeach; ?>

    </ul>
</nav>

<!-- ===== FOOTER / LOGOUT ===== -->
<div class="sidebar-footer">
    <?php if (isset($_SESSION['activa']) && (int)$_SESSION['activa'] === 1): ?>
    <a href="<?php echo $links_menu->adm_session->logout ?? '#'; ?>"
       class="sidebar-logout" title="Cerrar Sesión">
        <i class="bi bi-box-arrow-left sidebar-menu-icon"></i>
        <span class="sidebar-menu-label">Cerrar Sesión</span>
    </a>
    <?php endif; ?>
</div>

<script>
// Submenu toggle
(function () {
    var toggles = document.querySelectorAll('.sidebar-group-toggle');
    toggles.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var li = this.closest('.sidebar-menu-group');
            var isOpen = li.classList.contains('open');

            // Cerrar todos (acordeón)
            document.querySelectorAll('.sidebar-menu-group.open').forEach(function (el) {
                if (el !== li) el.classList.remove('open');
            });

            li.classList.toggle('open', !isOpen);
        });
    });
})();
</script>

