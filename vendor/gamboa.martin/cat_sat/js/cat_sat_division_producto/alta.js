var mask = IMask(
    document.getElementById('codigo'),
    {
        mask: `00`,
        lazy: false,
        placeholderChar: '#'
    }
);

$(".form-additional").validate({
    errorLabelContainer: $("div.error"),
    submitHandler: function (form) {
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
        codigo: "* Ingrese un código valido",
        descripcion: "* Ingrese una descripción valida"
    }
});


