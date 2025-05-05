<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use gamboamartin\system\links_menu; ?>
<a href="<?php echo (new links_menu($controlador->link, registro_id: -1))->links->adm_menu->alta; ?>" class="btn btn-info"><i class="icon-edit"></i>
   Nuevo menu
</a>