<?php /** @var gamboamartin\comercial\controllers\controlador_com_tipo_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<style>
    .option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 4px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 8px;
    }
    .option input {
        appearance: none;
        -webkit-appearance: none;
        margin: 0;
        width: 22px; height: 22px;
        flex: none;
        border: 2px solid var(--muted);
        border-radius: 50%;
        display: grid; place-items: center;
        cursor: pointer;
        transition: border-color .15s;
        padding: 0px;
        background: darkgray;
    }

    .option input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        margin: 0;
        padding: 0;
        width: 22px;
        height: 22px;
        flex: none;
        border: 2px solid #6b7280;
        border-radius: 50%;
        background: #fff;
        cursor: pointer;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }

    .option input[type="radio"]:checked {
        border-color: #1f5fd1;
        background: #1f5fd1;
        box-shadow: inset 0 0 0 4px #fff; /* anillo blanco = punto azul al centro */
    }

    .option input[type="radio"]:focus-visible {
        outline: 3px solid #1f5fd1;
        outline-offset: 3px;
    }

    /* Aviso */
    .notice {
        display: flex;
        gap: 10px;
        margin: 10px 0;
        padding: 14px !important;
        background-color: #D6D6D6;
        border: 1px solid #b8bec7;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.45;
        color: #374151;
    }

    .notice svg { width: 20px; height: 20px; flex: none; margin-top: 1px; color: #374151; }

    @media (prefers-reduced-motion: reduce) {
        * { transition: none !important; }
    }
</style>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_importa_previo_muestra_bd; ?>" class="form-additional">
                        <fieldset>
                            <legend>¿Qué hacer con registros existentes?</legend>

                            <label class="option">
                                <input type="radio" name="estrategia" value="actualizar" checked>
                                <span>Actualizar</span>
                            </label>
                            <label class="option">
                                <input type="radio" name="estrategia" value="omitir">
                                <span>Omitir</span>
                            </label>
                        </fieldset>

                        <div class="notice" role="note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7.5v5.5"/>
                                <circle cx="12" cy="16.5" r=".6" fill="currentColor"/>
                            </svg>
                            <span id="notice-text"></span>
                        </div>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="Importa" name="btn_action_next">Confirmar e Iniciar Importación</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

