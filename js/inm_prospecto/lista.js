let url = get_url("inm_prospecto", "data_ajax", {});

$(document).ready(function () {
    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) { //'data' contiene los datos de la fila
            //En la columna 1 estamos mostrando el tipo de usuario
            let userTypeColumnData = data[1] || 0;

            if (!filterByUserType(userTypeColumnData)) {
                return false;
            }

            return true;
        }
    );
});

function filterByUserType(userTypeColumnData) {
    let userTypeSelected = $('#userTypeFilter').val();

    //Si la opción seleccionada es 'TODOS', devolvemos 'true' para que pinte la fila
    if (userTypeSelected === "TODOS") {
        return true;
    }

    //La fila sólo se va a pintar si el valor de la columna coincide con el del filtro seleccionado
    return userTypeColumnData === userTypeSelected;
}

function filterTable() {
    $('#myTable').DataTable().draw();
}
