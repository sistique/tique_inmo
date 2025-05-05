let sl_cat_sat_tipo_producto = $("#cat_sat_tipo_producto_id");
let sl_cat_sat_division_producto = $("#cat_sat_division_producto_id");

let division = sl_cat_sat_division_producto.find('option:selected');
let codigo_division = division.data(`cat_sat_division_producto_codigo`);

const mask_formato = (cadena) => {
    let salida = "";
    let aux = '';

    for (var i = 0; i < cadena.length; i++) {
        let value = cadena.substring(i, i + 1);
        if (cadena.substring(i, i + 1) === '0'){
            aux = '\\'
        }
        salida += `${aux}${value}`
    }
    return salida;
}

codigo_division = mask_formato(`${codigo_division}`)

var mask = IMask(
    document.getElementById('codigo'),
    {
        mask: `${codigo_division}00`,
        lazy: false,
        placeholderChar: '#'
    }
);

$( ".form-additional" ).validate({
    errorLabelContainer: $("div.error"),
    submitHandler: function(form) {
        form.submit();
    },
    rules: {
        codigo: {
            required: true,
            digits: true
        },
        descripcion: {
            required: true
        }
    },
    messages: {
        cat_sat_tipo_producto_id: "* Seleccione un tipo de producto",
        cat_sat_division_producto_id: "* Seleccione un división de producto",
        codigo: "* Ingrese un código valido",
        descripcion: "* Ingrese una descripción valida"
    }
});

let asigna_divisiones = (cat_sat_tipo_producto_id = '') => {
    let url = get_url("cat_sat_division_producto","get_divisiones", {cat_sat_tipo_producto_id: cat_sat_tipo_producto_id});

    get_data(url, function (data) {
        console.log(url);
        sl_cat_sat_division_producto.empty();

        integra_new_option(sl_cat_sat_division_producto,'Seleccione una division','-1');

        $.each(data.registros, function( index, division ) {
            integra_new_option(sl_cat_sat_division_producto,division.cat_sat_division_producto_descripcion_select,
                division.cat_sat_division_producto_id,"data-cat_sat_division_producto_codigo",division.cat_sat_division_producto_codigo);
        });
        sl_cat_sat_division_producto.selectpicker('refresh');
    });
}

sl_cat_sat_tipo_producto.change(function () {
    let selected = $(this).find('option:selected');
    asigna_divisiones(selected.val());

    mask.Value = ``;
    mask.updateOptions({mask: `0000`});
});

sl_cat_sat_division_producto.change(function () {
    let selected = $(this).find('option:selected');
    let codigo = selected.data(`cat_sat_division_producto_codigo`);
    let mascara = ``;

    mask.Value = "";

    if (codigo === undefined) {
        mascara = `00`;
    } else {
        mascara = mask_formato(`${codigo}`);
    }

    mask.updateOptions({mask: `${mascara}00`});
});