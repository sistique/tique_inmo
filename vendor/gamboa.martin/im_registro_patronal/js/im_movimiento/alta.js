let url = getAbsolutePath();

let session_id = getParameterByName('session_id');

let sl_em_empleado = $("#em_empleado_id");
let sl_im_tipo_movimiento = $('#im_tipo_movimiento_id');
let txt_salario_diario = $('#salario_diario');
let txt_factor_integracion = $('#factor_integracion');
let txt_salario_diario_integrado = $('#salario_diario_integrado');
let txt_fecha_inicio_rel_laboral = $('#fecha');

sl_im_tipo_movimiento.change(function (event) {
    let tipo_mov = $("#im_tipo_movimiento_id option:selected").text();

    if (tipo_mov === 'BAJA' || tipo_mov === 'REINGRESO') {
        txt_salario_diario.prop('disabled', true);
        txt_salario_diario_integrado.prop('disabled', true);
    }
    else{
        txt_salario_diario.prop('disabled', false);
        txt_salario_diario_integrado.prop('disabled', false);
    }
});

txt_salario_diario.change(function (){
    let salario_diario = $(this).val();
    let factor = txt_factor_integracion.val();

    let res = salario_diario * factor;
    txt_salario_diario_integrado.val(res.toFixed(2));
});

let getData = async (url, acciones) => {
    fetch(url)
        .then(response => response.json())
        .then(data => acciones(data))
        .catch(err => {
            alert(err.message);
            console.error("ERROR: ", err.message)
        });
}

