<?php /** @var gamboamartin\organigrama\controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>


<?php echo $controlador->url_servicios['dp_pais']['event_change']; ?>

<?php echo $controlador->url_servicios['dp_estado']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_municipio']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_cp']['event_full']; ?>
<?php echo $controlador->url_servicios['dp_colonia_postal']['event_full']; ?>

<?php echo $controlador->url_servicios['dp_calle_pertenece']['event_update']; ?>
<script>
    $("#org_empresa_id").change(function(){
        let selected = $(this).find('option:selected');
        fecha_inicio_operaciones = selected.data('org_empresa_fecha_inicio_operaciones');
        exterior = selected.data('org_empresa_exterior');

        if(fecha_inicio_operaciones !== '0000-00-00'){
            $("#fecha_inicio_operaciones").val(fecha_inicio_operaciones);
        }

    });

    $("#org_empresa_id").change(function(){
        let selected = $(this).find('option:selected');
        let dp_pais_id_val = selected.data('dp_pais_id');
        let dp_estado_id_val = selected.data('dp_estado_id');
        let dp_municipio_id_val = selected.data('dp_municipio_id');
        let dp_cp_id_val = selected.data('dp_cp_id');
        let dp_colonia_postal_id_val = selected.data('dp_colonia_postal_id');
        let dp_calle_pertenece_id_val = selected.data('dp_calle_pertenece_id');

        sl_dp_pais.val(dp_pais_id_val);
        sl_dp_pais.selectpicker('refresh');

        asigna_dp_estado(dp_pais_id_val,dp_estado_id_val);

        asigna_dp_municipio(dp_estado_id_val,dp_municipio_id_val);

        asigna_dp_cp(dp_municipio_id_val,dp_cp_id_val);

        asigna_dp_colonia_postal(dp_cp_id_val,dp_colonia_postal_id_val);

        asigna_dp_calle_pertenece(dp_colonia_postal_id_val,dp_calle_pertenece_id_val);


    });


</script>