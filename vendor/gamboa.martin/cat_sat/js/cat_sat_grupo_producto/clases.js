let sl_cat_sat_grupo_producto = $("#cat_sat_grupo_producto_id");
let input = $("#codigo");

let grupo = sl_cat_sat_grupo_producto.find('option:selected');
let codigo_grupo = grupo.data(`cat_sat_grupo_producto_codigo`);

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

codigo_grupo = mask_formato(`${codigo_grupo}`)

var mask = IMask(
    document.getElementById('codigo'),
    {
        mask: `${codigo_grupo}00`,
        lazy: false,
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
            required: true,
        }
    },
    messages: {
        codigo: "* Ingrese un código valido",
        descripcion: "* Ingrese una descripción valida"
    }
});



