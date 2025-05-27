jQuery.validator.setDefaults({
    debug: true,
    success: "valid"
});


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