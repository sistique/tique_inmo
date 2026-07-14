<?php /** @var stdClass $data */
/** @var base\controller\ $controlador */
use config\generales;
use config\views;
use gamboamartin\system\links_menu;

$path_base_template = (new views())->ruta_templates;
$links_menu = (new links_menu(link:$controlador->link, registro_id: -1))->links;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?php echo (new views())->titulo_sistema; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <?php include $path_base_template.'css.php'; ?>
    <?php echo $data->css_custom->css; ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="app-layout-body">
<div id="fb-root"></div>

<div class="app-layout">

    <!-- Backdrop para mobile (click fuera cierra el sidebar) -->
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

    <?php if($data->menu): ?>
    <!-- ======== SIDEBAR ======== -->
    <aside class="app-sidebar" id="app-sidebar">
        <?php include $path_base_template.'nav/menu.php' ?>
    </aside>
    <?php endif; ?>

    <!-- ======== CONTENT ======== -->
    <div class="app-body<?php echo $data->menu ? '' : ' app-body-full'; ?>" id="app-body">

        <?php if($data->menu): ?>
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebar-toggle-btn" title="Menú">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="app-topbar-breadcrumb d-none d-md-block">
                <?php
                $sec = $_GET['seccion'] ?? '';
                $acc = $_GET['accion']  ?? '';
                if ($sec !== '') {
                    $sec_label = ucwords(str_replace(['inm_', 'adm_', 'com_', 'dp_', 'bn_', 'cat_', 'fc_', '_'], ['', '', '', '', '', '', '', ' '], $sec));
                    $acc_label = ucwords(str_replace('_', ' ', $acc));
                    echo '<i class="bi bi-chevron-right text-muted me-1" style="font-size:small"></i>';
                    echo '<span class="text-muted" style="font-size: small">' . htmlspecialchars($sec_label);
                    if ($acc_label !== '') echo ' <span class="text-muted mx-1">/</span> ' . htmlspecialchars($acc_label);
                    echo '</span>';
                }
                ?>
            </span>
            <div class="app-topbar-right">
                <?php if(isset($_SESSION['activa']) && (int)$_SESSION['activa'] === 1): ?>
                    <!--<a role="button" class="btn btn-sm btn-outline-secondary me-1"
                       href="<?php //echo $links_menu->adm_session->inicio ?>">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>-->
                    <a role="button" class="btn btn-sm btn-outline-danger salida"
                       href="<?php echo $links_menu->adm_session->logout ?>">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                <?php endif; ?>
            </div>
        </header>
        <?php endif; ?>

        <main class="app-main">
            <div class="app-main-inner">
                <?php include($data->include_action); ?>
            </div>
        </main>

        <footer class="app-footer">
            <?php include $path_base_template.'footer/_footer.php' ?>
        </footer>
    </div><!-- /.app-body -->

</div><!-- /.app-layout -->

<a class="btn btn-scoll-up color-secondary" id="btn-scroll-up"></a>

<?php include $path_base_template.'java.php'; ?>
<?php
if($data->js_view_aplica_include){
    include $data->js_view;
} else {
    echo $data->js_view;
}
?>
<?php if (isset($controlador->datatables)):?>
    <?php foreach ($controlador->datatables as $datatable) {
        $objeto = json_encode($datatable);
        print_r("<script> datatable($objeto.identificador, $objeto.columns, $objeto.columnDefs, $objeto.data, $objeto.in, $objeto.dom) </script>");
    } ?>
<?php endif;?>

<script>
(function () {
    var MOBILE_BP   = 768;   // px — off-canvas
    var TABLET_BP   = 900;   // px — hover-expand

    var sidebar     = document.getElementById('app-sidebar');
    var appBody     = document.getElementById('app-body');
    var toggleBtn   = document.getElementById('sidebar-toggle-btn');
    var collapseBtn = document.getElementById('sidebar-collapse-btn');
    var backdrop    = document.getElementById('sidebar-backdrop');

    if (!sidebar) return;

    /* ── Detecta breakpoint actual ─────────────────────────── */
    function isMobile()  { return window.innerWidth <= MOBILE_BP; }
    function isTablet()  { return window.innerWidth > MOBILE_BP && window.innerWidth <= TABLET_BP; }
    function isDesktop() { return window.innerWidth > TABLET_BP; }

    /* ── Abre sidebar en mobile (overlay) ──────────────────── */
    function openMobile() {
        sidebar.classList.add('mobile-open');
        document.body.classList.add('sidebar-open');
        if (backdrop) {
            backdrop.style.display = 'block';
            // forzar reflow para activar la transición
            backdrop.offsetHeight;
            backdrop.classList.add('visible');
        }
    }

    /* ── Cierra sidebar en mobile ───────────────────────────── */
    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        document.body.classList.remove('sidebar-open');
        if (backdrop) {
            backdrop.classList.remove('visible');
            setTimeout(function () {
                if (!backdrop.classList.contains('visible')) {
                    backdrop.style.display = 'none';
                }
            }, 300);
        }
    }

    /* ── Colapsa/expande en desktop ─────────────────────────── */
    function toggleDesktop() {
        var collapsed = sidebar.classList.toggle('collapsed');
        if (appBody) appBody.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem('sb_collapsed', collapsed ? '1' : '0');
    }

    /* ── Click en botón hamburguesa (topbar) ─────────────────── */
    function onToggleClick() {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
        } else {
            toggleDesktop();
        }
    }

    if (toggleBtn)   toggleBtn.addEventListener('click', onToggleClick);
    if (collapseBtn) collapseBtn.addEventListener('click', toggleDesktop);

    /* ── Backdrop click → cerrar mobile ─────────────────────── */
    if (backdrop) {
        backdrop.addEventListener('click', closeMobile);
    }

    /* ── Tecla ESC → cerrar mobile ───────────────────────────── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isMobile()) closeMobile();
    });

    /* ── Swipe left para cerrar en mobile ───────────────────── */
    var touchStartX = 0;
    var touchStartY = 0;

    sidebar.addEventListener('touchstart', function (e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    sidebar.addEventListener('touchend', function (e) {
        if (!isMobile()) return;
        var dx = e.changedTouches[0].clientX - touchStartX;
        var dy = Math.abs(e.changedTouches[0].clientY - touchStartY);
        // Swipe left ≥ 60px y más horizontal que vertical
        if (dx < -60 && dy < 80) closeMobile();
    }, { passive: true });

    /* ── Swipe right en borde izquierdo para abrir ──────────── */
    document.addEventListener('touchstart', function (e) {
        if (!isMobile()) return;
        if (e.touches[0].clientX < 24) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }
    }, { passive: true });

    document.addEventListener('touchend', function (e) {
        if (!isMobile()) return;
        if (touchStartX > 24) return;
        var dx = e.changedTouches[0].clientX - touchStartX;
        var dy = Math.abs(e.changedTouches[0].clientY - touchStartY);
        if (dx > 60 && dy < 80) openMobile();
    }, { passive: true });

    /* ── Restaurar estado al cargar (solo desktop) ──────────── */
    function applyInitialState() {
        if (isMobile() || isTablet()) {
            // Asegurar que no quede colapsado visualmente en mobile/tablet
            sidebar.classList.remove('collapsed');
            if (appBody) appBody.classList.remove('sidebar-collapsed');
        } else {
            // Desktop: restaurar preferencia guardada
            if (localStorage.getItem('sb_collapsed') === '1') {
                sidebar.classList.add('collapsed');
                if (appBody) appBody.classList.add('sidebar-collapsed');
            }
        }
    }

    applyInitialState();

    /* ── Reajustar al cambiar tamaño de ventana ─────────────── */
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (!isMobile()) {
                closeMobile(); // limpiar estado mobile si se agranda
            }
            applyInitialState();
        }, 150);
    });

})();
</script>
</body>
</html>
