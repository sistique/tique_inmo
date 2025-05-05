$(document).ready(function(){

    let registro_id = getParameterByName('registro_id');

    $("#rechazarBtn").click(function(event){
        event.preventDefault();
        let url = get_url('gt_solicitud', 'rechaza_bd', {registro_id: registro_id}, 0);
        url = remover(url, 'ws');
        $("#action").val(url);
        $("#myForm").attr('action', $("#action").val());
        $("#myForm").submit();
    });

    $("#autorizarBtn").click(function(event){
        event.preventDefault();
        let url = get_url('gt_solicitud', 'autoriza_bd', {registro_id: registro_id}, 0);
        url = remover(url, 'ws');
        $("#action").val(url);
        $("#myForm").attr('action', $("#action").val());
        $("#myForm").submit();
    });

    function remover(url, parameter) {
        var urlParts = url.split('?');
        if (urlParts.length >= 2) {
            var prefix = encodeURIComponent(parameter) + '=';
            var pars = urlParts[1].split(/[&;]/g);

            for (var i = pars.length; i-- > 0;) {
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url = urlParts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    }
});






