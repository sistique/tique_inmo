document.addEventListener("DOMContentLoaded", function () {
    var alertDanger = document.querySelector('.alert-danger');

    if (alertDanger) {
        setTimeout(function () {
            alertDanger.style.display = 'none';
        }, 5000);
    }
});
