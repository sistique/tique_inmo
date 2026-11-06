<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_comprador $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php
$val = static function (mixed $value): string {
    $value = trim((string)($value ?? ''));
    return $value !== '' && $value !== '0'
        ? htmlspecialchars($value)
        : '<span class="text-muted">—</span>';
};
$comprador = $controlador->registro->inm_comprador;
$conyuge = $controlador->registro->inm_conyuge;
$conyuge_tiene_datos = !empty((string)($conyuge->inm_conyuge_nombre ?? ''));
?>

<main class="main section-color-primary">
    <div class="container-fluid">
        <?php include (new views())->ruta_templates . 'head/title.php'; ?>

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-user"></i> COMPRADOR</h5>
            </div>
            <div class="card-body">
                <h6 class="text-secondary border-bottom pb-1 mb-2">Datos personales</h6>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre completo</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_nombre_completo ?? '') ?></dd>
                            <dt class="col-sm-5">Fecha de nacimiento</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_lugar_fecha_nac ?? '') ?></dd>
                            <dt class="col-sm-5">Edad</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_edad ?? '') ?> AÑOS</dd>
                            <dt class="col-sm-5">Género</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_genero ?? '') ?></dd>
                            <dt class="col-sm-5">Estado civil</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_estado_civil_descripcion ?? '') ?></dd>
                            <dt class="col-sm-5">Nacionalidad</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_nacionalidad_descripcion ?? '') ?></dd>
                            <dt class="col-sm-5">Ocupación</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_ocupacion_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">NSS</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_nss ?? '') ?></dd>
                            <dt class="col-sm-5">CURP</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_curp ?? '') ?></dd>
                            <dt class="col-sm-5">Con discapacidad</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_con_discapacidad ?? '') ?></dd>
                            <dt class="col-sm-5">Tipo discapacidad</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_tipo_discapacidad_descripcion ?? '') ?></dd>
                            <dt class="col-sm-5">Persona con discapacidad</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_persona_discapacidad_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Contacto</h6>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Celular</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_cel_com ?? '') ?></dd>
                            <dt class="col-sm-5">Tel. casa</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_telefono_casa ?? '') ?></dd>
                            <dt class="col-sm-5">Email</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_correo_com ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Datos laborales</h6>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Empresa</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_nombre_empresa_patron ?? '') ?></dd>
                            <dt class="col-sm-5">Reg. patronal</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_nrp_nep ?? '') ?></dd>
                            <dt class="col-sm-5">Tel. empresa</dt>
                            <dd class="col-sm-7">
                                <?php
                                $lada = trim((string)($comprador->inm_comprador_lada_nep ?? ''));
                                $numero = trim((string)($comprador->inm_comprador_numero_nep ?? ''));
                                $extension = trim((string)($comprador->inm_comprador_extension_nep ?? ''));
                                echo $val(trim("$lada $numero" . ($extension !== '' ? " Ext.$extension" : '')));
                                ?>
                            </dd>
                            <dt class="col-sm-5">Email empresa</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_comprador_correo_empresa ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Sindicato</dt>
                            <dd class="col-sm-7"><?= $val($comprador->inm_sindicato_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Crédito</h6>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Producto INFONAVIT</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_producto_infonavit_descripcion ?? '') ?></dd>
                            <dt class="col-sm-6">Tipo crédito</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_attr_tipo_credito_descripcion ?? '') ?></dd>
                            <dt class="col-sm-6">Destino crédito</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_destino_credito_descripcion ?? '') ?></dd>
                            <dt class="col-sm-6">Institución hipotecaria</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_institucion_hipotecaria_descripcion ?? '') ?></dd>
                            <dt class="col-sm-6">Segundo crédito</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_comprador_es_segundo_credito ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Núm. crédito</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_comprador_numero_credito ?? '') ?></dd>
                            <dt class="col-sm-6">Monto solicitado</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($comprador->inm_comprador_monto_credito_solicitado_dh ?? 0), 2) ?></dd>
                            <dt class="col-sm-6">Sub cuenta</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($comprador->inm_comprador_sub_cuenta ?? 0), 2) ?></dd>
                            <dt class="col-sm-6">Ahorro voluntario</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($comprador->inm_comprador_monto_ahorro_voluntario ?? 0), 2) ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Monto final</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($comprador->inm_comprador_monto_final ?? 0), 2) ?></dd>
                            <dt class="col-sm-6">Descuento</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($comprador->inm_comprador_descuento ?? 0), 2) ?></dd>
                            <dt class="col-sm-6">Puntos</dt>
                            <dd class="col-sm-6"><?= number_format((float)($comprador->inm_comprador_puntos ?? 0), 2) ?></dd>
                            <dt class="col-sm-6">Plazo crédito S.C.</dt>
                            <dd class="col-sm-6"><?= $val($comprador->inm_plazo_credito_sc_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($controlador->registro->inm_co_acreditados)): ?>
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fa fa-users"></i> CO-ACREDITADOS</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($controlador->registro->inm_co_acreditados as $index => $co_acreditado): ?>
                        <?php if ($index > 0): ?><hr><?php endif; ?>
                        <h6 class="text-secondary border-bottom pb-1 mb-2">
                            Co-acreditado <?= $index + 1 ?>:
                            <?= $val(trim(($co_acreditado['inm_co_acreditado_nombre'] ?? '') . ' '
                                . ($co_acreditado['inm_co_acreditado_apellido_paterno'] ?? '') . ' '
                                . ($co_acreditado['inm_co_acreditado_apellido_materno'] ?? ''))) ?>
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">NSS</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_nss'] ?? '') ?></dd>
                                    <dt class="col-sm-5">CURP</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_curp'] ?? '') ?></dd>
                                    <dt class="col-sm-5">RFC</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_rfc'] ?? '') ?></dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">Celular</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_celular'] ?? '') ?></dd>
                                    <dt class="col-sm-5">Correo</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_correo'] ?? '') ?></dd>
                                    <dt class="col-sm-5">Empresa</dt>
                                    <dd class="col-sm-7"><?= $val($co_acreditado['inm_co_acreditado_nombre_empresa'] ?? '') ?></dd>
                                </dl>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($conyuge_tiene_datos): ?>
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fa fa-heart"></i> CÓNYUGE</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Nombre completo</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_nombre_completo ?? '') ?></dd>
                                <dt class="col-sm-5">Lugar y fecha nac.</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_lugar_fecha_nac ?? '') ?></dd>
                                <dt class="col-sm-5">Edad</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_edad ?? '') ?></dd>
                                <dt class="col-sm-5">Estado civil</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_estado_civil ?? '') ?></dd>
                                <dt class="col-sm-5">Nacionalidad</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_nacionalidad_descripcion ?? '') ?></dd>
                                <dt class="col-sm-5">Ocupación</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_ocupacion_descripcion ?? '') ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">CURP</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_curp ?? '') ?></dd>
                                <dt class="col-sm-5">RFC</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_rfc ?? '') ?></dd>
                                <dt class="col-sm-5">Tel. casa</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_telefono_casa ?? '') ?></dd>
                                <dt class="col-sm-5">Celular</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_telefono_celular ?? '') ?></dd>
                                <dt class="col-sm-5">Núm. crédito</dt>
                                <dd class="col-sm-7"><?= $val($conyuge->inm_conyuge_numero_credito ?? '') ?></dd>
                                <dt class="col-sm-5">Adeudo hipoteca</dt>
                                <dd class="col-sm-7">$<?= number_format((float)($conyuge->inm_conyuge_adeudo_hipoteca ?? 0), 2) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        $hay_beneficiarios = false;
        foreach ($controlador->registro->inm_tipo_beneficiarios as $tipo_beneficiario) {
            if (!empty($tipo_beneficiario->inm_beneficiarios)) {
                $hay_beneficiarios = true;
                break;
            }
        }
        ?>
        <?php if ($hay_beneficiarios): ?>
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fa fa-child"></i> BENEFICIARIOS</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($controlador->registro->inm_tipo_beneficiarios as $tipo_beneficiario): ?>
                        <?php if (empty($tipo_beneficiario->inm_beneficiarios)) continue; ?>
                        <h6 class="text-secondary border-bottom pb-1 mb-2">
                            <?= $val($tipo_beneficiario->inm_tipo_beneficiario_descripcion ?? '') ?>
                        </h6>
                        <table class="table table-sm table-striped mb-3">
                            <thead class="table-light">
                            <tr>
                                <th>#</th><th>Nombre</th><th>Apellido paterno</th><th>Apellido materno</th><th>Parentesco</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($tipo_beneficiario->inm_beneficiarios as $index => $beneficiario): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $val($beneficiario->inm_beneficiario_nombre ?? '') ?></td>
                                    <td><?= $val($beneficiario->inm_beneficiario_apellido_paterno ?? '') ?></td>
                                    <td><?= $val($beneficiario->inm_beneficiario_apellido_materno ?? '') ?></td>
                                    <td><?= $val($beneficiario->inm_parentesco_descripcion ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($controlador->registro->inm_referencias)): ?>
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fa fa-address-book"></i> REFERENCIAS</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                        <tr><th>#</th><th>Nombre completo</th><th>Parentesco</th><th>Celular</th><th>Teléfono</th><th>Dirección</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($controlador->registro->inm_referencias as $index => $referencia): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $val($referencia->inm_referencia_comprador_nombre_completo ?? '') ?></td>
                                <td><?= $val($referencia->inm_parentesco_descripcion ?? '') ?></td>
                                <td><?= $val($referencia->inm_referencia_comprador_celular ?? '') ?></td>
                                <td><?= $val($referencia->inm_referencia_comprador_telefono ?? '') ?></td>
                                <td>
                                    <?php
                                    $direccion = array_filter(array(
                                        trim((string)($referencia->inm_referencia_calle ?? '')),
                                        trim((string)($referencia->inm_referencia_numero_exterior ?? '')),
                                        !empty($referencia->inm_referencia_numero_interior)
                                            ? 'Int.' . $referencia->inm_referencia_numero_interior : '',
                                        trim((string)($referencia->dp_colonia_postal_descripcion ?? ''))
                                    ));
                                    echo $direccion ? htmlspecialchars(implode(', ', $direccion)) : $val('');
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <br>
</main>
