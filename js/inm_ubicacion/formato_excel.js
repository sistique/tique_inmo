let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

function copiarRegistros() {
    const tbody = document.querySelector("#miTabla tbody");
    let texto = "";

    for (let fila of tbody.rows) {
        let celdas = [];
        for (let celda of fila.cells) {
            celdas.push(celda.innerText.trim());
        }
        texto += celdas.join("\t") + "\n";
    }

    navigator.clipboard.writeText(texto)
        .then(() => alert("Registros copiados. Pega en Excel"))
        .catch(err => console.error("Error al copiar:", err));
}









