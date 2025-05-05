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
            required: true,
        }
    },
    messages: {
        codigo: "* Ingrese un código valido",
        descripcion: "* Ingrese una descripción valida"
    }
});



