<?php /** @var gamboamartin\organigrama\controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php echo $controlador->url_servicios['dp_pais']['event_change']; ?>
<?php echo $controlador->url_servicios['dp_estado']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_municipio']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_cp']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_colonia_postal']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_calle_pertenece']['event_update']; ?>


let fecha_inicio_operaciones = $("#fecha_inicio_operaciones");
let fecha_ultimo_cambio_sat = $("#fecha_ultimo_cambio_sat");




<script>

    $('#dp_colonia_postal_id').change(function () {
    let selected = $(this).find('option:selected');
    let url = get_url("dp_calle_pertenece", "get_calle_pertenece", {dp_colonia_postal_id: selected.val()});

    get_data(url, function (data) {
    $('#dp_calle_pertenece_entre1_id').empty();
    $('#dp_calle_pertenece_entre2_id').empty();

    integra_new_option($('#dp_calle_pertenece_entre1_id'), 'Seleccione una calle', '-1');
    integra_new_option($('#dp_calle_pertenece_entre2_id'), 'Seleccione una calle', '-1');

    $.each(data.registros, function (index, calle) {

    integra_new_option($('#dp_calle_pertenece_entre1_id'), calle.dp_calle_descripcion, calle.dp_calle_pertenece_id);
    integra_new_option($('#dp_calle_pertenece_entre2_id'), calle.dp_calle_descripcion, calle.dp_calle_pertenece_id);
});

    $('#dp_calle_pertenece_entre1_id').selectpicker('refresh');
    $('#dp_calle_pertenece_entre2_id').selectpicker('refresh');
});



});

    let fecha_inicio_operaciones = $("#fecha_inicio_operaciones");
    let fecha_ultimo_cambio_sat = $("#fecha_ultimo_cambio_sat");



    fecha_inicio_operaciones.change(function () {
    fecha_ultimo_cambio_sat.val(fecha_inicio_operaciones.val());
});
</script>