let sl_cat_sat_clase_producto = $("#cat_sat_clase_producto_id");
let input = $("#codigo");

let clase = sl_cat_sat_clase_producto.find('option:selected');
let codigo_clase = clase.data(`cat_sat_clase_producto_codigo`);

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

codigo_clase = mask_formato(`${codigo_clase}`)

var mask = IMask(
    document.getElementById('codigo'),
    {
        mask: `${codigo_clase}00`,
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

