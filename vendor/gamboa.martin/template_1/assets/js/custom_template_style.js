"use strict";
function custom_template_style(){
    var boxed = false; 
    var hide_palette = false;

    if(boxed) {
        $('.custom-palette-box input[name="type-site"][value="boxed"]').attr('checked','checked');
        $('body').addClass('boxed');
    }

    if(hide_palette) {
        $('.custom-palette').css('display','none');
    }
}

/** AJUSTE INPUT DOCS **/

const inputs = document.querySelectorAll(".archivo-sec");

// Configurar worker PDF.js una sola vez
pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

// Seleccionamos TODAS las dropzones presentes en la página
const dropZones = document.querySelectorAll(".drop-zone");

dropZones.forEach(dropZone => {
    // Para cada dropzone, obtenemos sus elementos internos específicos
    const input = dropZone.querySelector(".archivo-sec");
    const listaPreviews = dropZone.querySelector(".lista-previews");

    // Cada dropzone mantiene su propia lista de archivos en memoria
    let archivosLista = [];

    if (!input || !listaPreviews) return;

    // 1. Abrir explorador de archivos al hacer clic en la dropzone
    dropZone.addEventListener("click", () => {
        input.click();
    });

    // 2. Evento al seleccionar archivos desde el explorador
    input.addEventListener("change", (e) => {
        const archivos = Array.from(e.target.files);
        if (archivos.length > 0) {
            procesarArchivos(archivos);
        }
    });

    // 3. Eventos Drag & Drop para esta dropzone específica
    dropZone.addEventListener("dragenter", e => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragover", e => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", e => {
        e.preventDefault();
        dropZone.classList.remove("dragover");

        const archivosSueltos = Array.from(e.dataTransfer.files);
        if (archivosSueltos.length > 0) {
            procesarArchivos(archivosSueltos);
        }
    });

    // Procesa y agrega los archivos a esta dropzone
    function procesarArchivos(nuevosArchivos) {
        const permitidos = ["application/pdf", "image/jpeg", "image/png"];

        nuevosArchivos.forEach(file => {
            if (!permitidos.includes(file.type)) {
                alert(`El archivo "${file.name}" no es permitido.`);
                return;
            }

            const existe = archivosLista.some(a => a.name === file.name && a.size === file.size);
            if (existe) return;

            archivosLista.push(file);
            crearCardPreview(file);
        });

        sincronizarInputFiles();
    }

    // Genera la tarjeta dentro de la lista de vistas previas de esta dropzone
    async function crearCardPreview(file) {
        const idUnico = "card-" + Math.random().toString(36).substring(2, 9);
        const tamaño = formatearBytes(file.size);

        const card = document.createElement("div");
        card.className = "preview-card";
        card.id = idUnico;

        // Evitar que hacer clic en la tarjeta abra el explorador de archivos
        card.addEventListener("click", (e) => {
            e.stopPropagation();
        });

        card.innerHTML = `
            <button type="button" class="btn-eliminar" title="Eliminar documento">×</button>
            <div class="preview-imagen">
                <span class="pdf-loader">Cargando...</span>
            </div>
            <div class="preview-info">
                <h4 title="${file.name}">${file.name}</h4>
                <p>${tamaño}</p>
            </div>
        `;

        listaPreviews.appendChild(card);

        // Evento eliminar
        card.querySelector(".btn-eliminar").addEventListener("click", (e) => {
            e.stopPropagation();
            eliminarArchivo(file, card);
        });

        const contenedorImagen = card.querySelector(".preview-imagen");

        if (file.type === "image/jpeg" || file.type === "image/png") {
            const url = URL.createObjectURL(file);
            const img = document.createElement("img");
            img.src = url;
            img.onload = () => URL.revokeObjectURL(url);
            contenedorImagen.innerHTML = "";
            contenedorImagen.appendChild(img);
        } else if (file.type === "application/pdf") {
            await generarPreviewPDF(file, contenedorImagen);
        }
    }

    // Eliminar un archivo de la lista de esta dropzone
    function eliminarArchivo(file, elementoDom) {
        archivosLista = archivosLista.filter(a => !(a.name === file.name && a.size === file.size));
        elementoDom.remove();
        sincronizarInputFiles();
    }

    // Sincronizar el input de esta dropzone específica
    function sincronizarInputFiles() {
        const dataTransfer = new DataTransfer();
        archivosLista.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }
});

// Renderizar primera página de PDF (Función global reutilizable)
async function generarPreviewPDF(file, contenedor) {
    try {
        const arrayBuffer = await file.arrayBuffer();
        const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
        const pagina = await pdf.getPage(1);

        const viewport = pagina.getViewport({ scale: 0.8 });
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");

        canvas.width = viewport.width;
        canvas.height = viewport.height;

        await pagina.render({
            canvasContext: context,
            viewport: viewport
        }).promise;

        const img = document.createElement("img");
        img.src = canvas.toDataURL("image/png");

        contenedor.innerHTML = "";
        contenedor.appendChild(img);
    } catch (error) {
        contenedor.innerHTML = `<span class="pdf-loader">Vista previa no disponible</span>`;
        console.error(error);
    }
}

// Formatear bytes (Función global)
function formatearBytes(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const tamaños = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + tamaños[i];
}