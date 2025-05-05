let url = getAbsolutePath();
document.write(`<script src="${url}/js/direcciones.js"></script>`)

let session_id = getParameterByName('session_id');

let sl_org_sucursal_id = $("#org_sucursal_id");
let sl_fc_csd_id = $("#fc_csd_id");

sl_org_sucursal_id.change(function(){
    let org_sucursal_id = $(this).val();

    let url = "index.php?seccion=fc_csd&ws=1&accion=get_csd&org_sucursal_id="+org_sucursal_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_fc_csd_id.empty();

        integra_new_option("#fc_csd_id",'Seleccione una CSD','-1');

        $.each(data.registros, function( index, fc_csd) {
            integra_new_option("#fc_csd_id",fc_csd.fc_csd_codigo+' - '+fc_csd.fc_csd_serie+
                ' - '+fc_csd.fc_csd_descripcion_select
                ,fc_csd.fc_csd_id);
        });
        sl_fc_csd_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al no existe un CSD de la sucursal');
        console.log(url);
    });
});
