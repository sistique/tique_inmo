let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_dp_pais = $("#dp_pais_id");
let sl_dp_estado = $("#dp_estado_id");
let sl_dp_municipio = $("#dp_municipio_id");
let sl_dp_cp = $("#dp_cp_id");
let sl_colonia_postal = $("#dp_colonia_postal_id");
let sl_dp_calle_pertenece = $("#dp_calle_pertenece_id");

let asigna_estados = (dp_pais_id = '', dp_estado_id = "") => {
    let url = get_url("dp_estado","get_estado", {dp_pais_id: dp_pais_id});

    get_data(url, function (data) {
        sl_dp_estado.empty();
        sl_dp_municipio.empty();
        sl_dp_cp.empty();
        sl_colonia_postal.empty();
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_dp_estado,'Seleccione un estado','-1');
        integra_new_option(sl_dp_municipio,'Seleccione un municipio','-1');
        integra_new_option(sl_dp_cp,'Seleccione un codigo postal','-1');
        integra_new_option(sl_colonia_postal,'Seleccione una colonia postal','-1');
        integra_new_option(sl_dp_calle_pertenece,'Seleccione una calle','-1');

        $.each(data.registros, function( index, dp_estado ) {
            integra_new_option(sl_dp_estado,dp_estado.dp_estado_descripcion,dp_estado.dp_estado_id);
        });
        sl_dp_estado.val(dp_estado_id)
        sl_dp_estado.selectpicker('refresh');
        sl_dp_municipio.selectpicker('refresh');
        sl_dp_cp.selectpicker('refresh');
        sl_colonia_postal.selectpicker('refresh');
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

let asigna_municipios = (dp_estado_id = '',dp_municipio_id = '') => {
    let url = get_url("dp_municipio","get_municipio", {dp_estado_id: dp_estado_id});

    get_data(url, function (data) {
        sl_dp_municipio.empty();
        sl_dp_cp.empty();
        sl_colonia_postal.empty();
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_dp_municipio,'Seleccione un municipio','-1');
        integra_new_option(sl_dp_cp,'Seleccione un codigo postal','-1');
        integra_new_option(sl_colonia_postal,'Seleccione una colonia postal','-1');
        integra_new_option(sl_dp_calle_pertenece,'Seleccione una calle','-1');

        $.each(data.registros, function( index, dp_municipio ) {
            integra_new_option(sl_dp_municipio,dp_municipio.dp_municipio_descripcion,dp_municipio.dp_municipio_id);
        });
        sl_dp_municipio.val(dp_municipio_id)
        sl_dp_municipio.selectpicker('refresh');
        sl_dp_cp.selectpicker('refresh');
        sl_colonia_postal.selectpicker('refresh');
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

let asigna_codigos_postales = (dp_municipio_id = '', dp_cp_id) => {
    let url = get_url("dp_cp","get_cp", {dp_municipio_id: dp_municipio_id});

    get_data(url, function (data) {
        sl_dp_cp.empty();
        sl_colonia_postal.empty();
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_dp_cp,'Seleccione un codigo postal','-1');
        integra_new_option(sl_colonia_postal,'Seleccione una colonia postal','-1');
        integra_new_option(sl_dp_calle_pertenece,'Seleccione una calle','-1');

        $.each(data.registros, function( index, dp_cp ) {
            integra_new_option(sl_dp_cp,dp_cp.dp_cp_descripcion,dp_cp.dp_cp_id);
        });
        sl_dp_cp.val(dp_cp_id)
        sl_dp_cp.selectpicker('refresh');
        sl_colonia_postal.selectpicker('refresh');
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

let asigna_colonias_postales = (dp_cp_id = '',dp_colonia_postal_id = '') => {
    let url = get_url("dp_colonia_postal","get_colonia_postal", {dp_cp_id: dp_cp_id});

    get_data(url, function (data) {
        sl_colonia_postal.empty();
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_colonia_postal,'Seleccione una colonia postal','-1');
        integra_new_option(sl_dp_calle_pertenece,'Seleccione una calle','-1');

        $.each(data.registros, function( index, dp_colonia_postal ) {
            integra_new_option(sl_colonia_postal,dp_colonia_postal.dp_colonia_descripcion,dp_colonia_postal.dp_colonia_postal_id);
        });
        sl_colonia_postal.val(dp_colonia_postal_id)
        sl_colonia_postal.selectpicker('refresh');
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

let asigna_calles = (dp_colonia_postal = '', dp_calle_pertenece_id = '') => {
    let url = get_url("dp_calle_pertenece","get_calle_pertenece", {dp_colonia_postal_id: 627});

    get_data(url, function (data) {
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_dp_calle_pertenece,'Seleccione una calle','-1');

        $.each(data.registros, function( index, calle ) {
            integra_new_option(sl_dp_calle_pertenece,calle.dp_calle_pertenece_descripcion_select,calle.dp_calle_pertenece_id);
        });
        sl_dp_calle_pertenece.val(dp_calle_pertenece_id)
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

sl_dp_pais.change(function () {
    let selected = $(this).find('option:selected');
    asigna_estados(selected.val());
});

sl_dp_estado.change(function () {
    let selected = $(this).find('option:selected');
    asigna_municipios(selected.val());
});

sl_dp_municipio.change(function () {
    let selected = $(this).find('option:selected');
    asigna_codigos_postales(selected.val());
});

sl_dp_cp.change(function () {
    let selected = $(this).find('option:selected');
    asigna_colonias_postales(selected.val());
});

sl_colonia_postal.change(function () {
    let selected = $(this).find('option:selected');
    asigna_calles(selected.val());
});


document.getElementById('documento').addEventListener('change', function (event) {
    var file = event.target.files[0];

    if (!file) {
        alert('No se seleccionó ningún archivo.');
        event.target.value = '';
        return;
    }

    if (file.type !== 'application/pdf') {
        alert('El archivo seleccionado no es un PDF.');
        event.target.value = '';
        return;
    }

    var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');
    $('body').append(loaderOverlay);

    var formData = new FormData();
    formData.append('documento', this.files[0]);

    let url = get_url("gt_proveedor", "leer_qr", {registro_id: -1});

    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            if(result.status === 'error'){
                alert(result.mensaje);
                loaderOverlay.remove();
                return;
            }

            data = result.data;
            console.log(data);
            /*persona = data.datos_identificacion;

            let url = get_url("cat_sat_tipo_persona", "get_tipo_persona", {tipo_persona: data.tipo_persona });
            get_data(url, function (data_tp) {
                cat_sat_tipo_persona_id_sl.val(data_tp.cat_sat_tipo_persona_id);
                cat_sat_tipo_persona_id_sl.selectpicker('refresh');
            });

            let url_rf = get_url("cat_sat_regimen_fiscal", "get_regimen_fiscal",
                {regimen_fiscal: data.datos_fiscales.regimen });
            get_data(url_rf, function (data_rf) {
                cat_sat_regimen_fiscal_id_sl.val(data_rf.cat_sat_regimen_fiscal_id);
                cat_sat_regimen_fiscal_id_sl.selectpicker('refresh');
            });

            let url_ubi = get_url("dp_municipio", "get_ubicacion_sat",
                {municipio: data.datos_ubicacion.municipio_o_delegacion});

            get_data(url_ubi, function (data_mun) {

                dp_pais_final_id_sl.val(data_mun.dp_pais_id);
                dp_pais_final_id_sl.selectpicker('refresh');

                let url_est = get_url("dp_estado","get_estado", {dp_pais_id: data_mun.dp_pais_id});

                get_data(url_est, function (data_est) {
                    dp_estado_final_id_sl.empty();
                    integra_new_option(dp_estado_final_id_sl,'Seleccione un estado','-1');

                    $.each(data_est.registros, function( index, dp_estado ) {
                        integra_new_option(dp_estado_final_id_sl,dp_estado.dp_estado_descripcion,dp_estado.dp_estado_id,
                            "data-dp_estado_predeterminado",dp_estado.dp_estado_predeterminado);
                    });

                    dp_estado_final_id_sl.val(data_mun.dp_estado_id);
                    dp_estado_final_id_sl.selectpicker('refresh');
                });

                let url_mun = get_url("dp_municipio","get_municipio", {dp_estado_id: data_mun.dp_estado_id});

                get_data(url_mun, function (data_mund) {
                    dp_municipio_final_id_sl.empty();

                    integra_new_option(dp_municipio_final_id_sl,'Seleccione un municipio','-1');

                    $.each(data_mund.registros, function( index, dp_municipio ) {
                        integra_new_option(dp_municipio_final_id_sl,dp_municipio.dp_municipio_descripcion,dp_municipio.dp_municipio_id,
                            "data-dp_municipio_predeterminado",dp_municipio.dp_municipio_predeterminado);
                    });

                    dp_municipio_final_id_sl.val(data_mun.dp_municipio_id);
                    dp_municipio_final_id_sl.selectpicker('refresh');
                });
            });

            let razon_social = "";

            if (data.tipo_persona === 'PERSONA FISICA') {
                razon_social = persona.nombre + ' ' + persona.apellido_paterno + ' ' + persona.apellido_materno;
            } else if (data.tipo_persona === 'PERSONA MORAL') {
                razon_social = persona.denominacion_o_razon_social;
            }

            txt_codigo.val(data.rfc);
            txt_rfc.val(data.rfc);
            txt_razon_social.val(razon_social);
            txt_tipo_persona.val(data.tipo_persona);
            txt_regimen_fiscal.val(data.datos_fiscales.regimen);
            txt_estado.val(data.datos_ubicacion.entidad_federativa);
            txt_municipio.val(data.datos_ubicacion.municipio_o_delegacion);
            txt_cp.val(data.datos_ubicacion.cp);
            txt_colonia.val(data.datos_ubicacion.colonia);
            txt_calle.val(data.datos_ubicacion.nombre_de_la_vialidad);
            txt_numero_exterior.val(data.datos_ubicacion.numero_exterior);
            txt_numero_interior.val(data.datos_ubicacion.numero_interior);

            loaderOverlay.remove();*/
        })
        .catch(error => {
            alert('Error al leer el documento.');
            console.error("Error procesando la respuesta:", error);
            loaderOverlay.remove();
        });
});


