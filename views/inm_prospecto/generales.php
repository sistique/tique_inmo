<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_prospecto $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php
/**
 * Devuelve el valor o un guión si está vacío / no definido.
 */
$val = static function (mixed $v, string $prefijo = ''): string {
    if (is_object($v)) {
        $campo = $prefijo ? $prefijo : '';
        $v = $campo !== '' ? ($v->$campo ?? '') : '';
    }
    $v = trim((string)($v ?? ''));
    return $v !== '' && $v !== '0' ? htmlspecialchars($v) : '<span class="text-muted">—</span>';
};

/** Retorna el objeto/array del registro o null si no tiene datos reales */
$conyuge_tiene_datos = !empty((string)($controlador->registro->inm_conyuge->inm_conyuge_nombre ?? ''));
?>

<main class="main section-color-primary">
    <div class="container-fluid">

        <?php include (new views())->ruta_templates . 'head/title.php'; ?>

        <?php
        /* ============================================================
         * 1. DERECHOHABIENTE
         * ============================================================ */
        $p = $controlador->registro->inm_prospecto;
        ?>
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-user"></i> DERECHOHABIENTE</h5>
            </div>
            <div class="card-body">

                <!-- Datos personales -->
                <h6 class="text-secondary border-bottom pb-1 mb-2">Datos personales</h6>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre completo</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_nombre_completo ?? '') ?></dd>

                            <dt class="col-sm-5">Fecha de nacimiento</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_lugar_fecha_nac ?? '') ?></dd>

                            <dt class="col-sm-5">Edad</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_edad ?? '') ?> AÑOS</dd>

                            <dt class="col-sm-5">Género</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_genero ?? '') ?></dd>

                            <dt class="col-sm-5">Estado civil</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_estado_civil_descripcion ?? '') ?></dd>

                            <dt class="col-sm-5">Nacionalidad</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_nacionalidad_descripcion ?? '') ?></dd>

                            <dt class="col-sm-5">Ocupación</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_ocupacion_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">NSS</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_nss ?? '') ?></dd>

                            <dt class="col-sm-5">CURP</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_curp ?? '') ?></dd>

                            <dt class="col-sm-5">RFC</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_rfc ?? '') ?></dd>

                            <dt class="col-sm-5">Con discapacidad</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_con_discapacidad ?? '') ?></dd>

                            <dt class="col-sm-5">Tipo discapacidad</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_tipo_discapacidad_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Contacto -->
                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Contacto</h6>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Celular</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_cel_com ?? '') ?></dd>

                            <dt class="col-sm-5">Tel. casa</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_telefono_casa ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Email</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_correo_com ?? '') ?></dd>

                            <dt class="col-sm-5">Red social</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_liga_red_social ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Empresa -->
                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Datos laborales</h6>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Empresa</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_nombre_empresa_patron ?? '') ?></dd>

                            <dt class="col-sm-5">Reg. patronal</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_nrp_nep ?? '') ?></dd>

                            <dt class="col-sm-5">Tel. empresa</dt>
                            <dd class="col-sm-7">
                                <?php
                                $lada = trim((string)($p->inm_prospecto_lada_nep ?? ''));
                                $num  = trim((string)($p->inm_prospecto_numero_nep ?? ''));
                                $ext  = trim((string)($p->inm_prospecto_extension_nep ?? ''));
                                $tel_emp = trim("$lada $num" . ($ext ? " Ext.$ext" : ''));
                                echo $tel_emp !== '' ? htmlspecialchars($tel_emp) : '<span class="text-muted">—</span>';
                                ?>
                            </dd>

                            <dt class="col-sm-5">Email empresa</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_correo_empresa ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Sindicato</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_sindicato_descripcion ?? '') ?></dd>

                            <dt class="col-sm-5">Área</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_area_empresa ?? '') ?></dd>

                            <dt class="col-sm-5">Dirección empresa</dt>
                            <dd class="col-sm-7"><?= $val($p->inm_prospecto_direccion_empresa ?? '') ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Crédito -->
                <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Crédito</h6>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Producto INFONAVIT</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_producto_infonavit_descripcion ?? '') ?></dd>

                            <dt class="col-sm-6">Tipo crédito</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_attr_tipo_credito_descripcion ?? '') ?></dd>

                            <dt class="col-sm-6">Destino crédito</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_destino_credito_descripcion ?? '') ?></dd>

                            <dt class="col-sm-6">Institución hipotecaria</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_institucion_hipotecaria_descripcion ?? '') ?></dd>

                            <dt class="col-sm-6">Segundo crédito</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_prospecto_es_segundo_credito ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Núm. crédito</dt>
                            <dd class="col-sm-6"><?= $val($p->inm_prospecto_numero_credito ?? '') ?></dd>

                            <dt class="col-sm-6">Monto solicitado</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($p->inm_prospecto_monto_credito_solicitado_dh ?? 0), 2) ?></dd>

                            <dt class="col-sm-6">Sub cuenta</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($p->inm_prospecto_sub_cuenta ?? 0), 2) ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">Monto final</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($p->inm_prospecto_monto_final ?? 0), 2) ?></dd>

                            <dt class="col-sm-6">Descuento</dt>
                            <dd class="col-sm-6">$<?= number_format((float)($p->inm_prospecto_descuento ?? 0), 2) ?></dd>

                            <dt class="col-sm-6">Puntos</dt>
                            <dd class="col-sm-6"><?= number_format((float)($p->inm_prospecto_puntos ?? 0), 2) ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Observaciones -->
                <?php $obs = trim((string)($p->inm_prospecto_observaciones ?? '')); ?>
                <?php if ($obs !== ''): ?>
                    <h6 class="text-secondary border-bottom pb-1 mb-2 mt-3">Observaciones</h6>
                    <p class="mb-0"><?= htmlspecialchars($obs) ?></p>
                <?php endif; ?>

            </div><!-- /card-body -->
        </div><!-- /card derechohabiente -->


        <?php
        /* ============================================================
         * 2. CO-ACREDITADOS
         * ============================================================ */
        ?>
        <?php if (!empty($controlador->registro->inm_beneficiarios)): ?>
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fa fa-users"></i> CO-ACREDITADOS</h5>
            </div>
            <div class="card-body">
                <?php foreach ($controlador->registro->inm_beneficiarios as $idx => $ca): ?>
                <<?= $idx > 0 ? 'div class="mt-3 pt-3 border-top"' : 'div' ?>>

                    <!-- Datos personales -->
                    <h6 class="text-secondary border-bottom pb-1 mb-2">
                        Co-acreditado <?= $idx + 1 ?>:
                        <strong><?= htmlspecialchars(
                            trim(($ca['inm_co_acreditado_nombre'] ?? '') . ' ' .
                                 ($ca['inm_co_acreditado_apellido_paterno'] ?? '') . ' ' .
                                 ($ca['inm_co_acreditado_apellido_materno'] ?? ''))
                        ) ?></strong>
                    </h6>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">NSS</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_nss'] ?? '') ?></dd>

                                <dt class="col-sm-5">CURP</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_curp'] ?? '') ?></dd>

                                <dt class="col-sm-5">RFC</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_rfc'] ?? '') ?></dd>

                                <dt class="col-sm-5">Género</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_genero'] ?? '') ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Celular</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_celular'] ?? '') ?></dd>

                                <dt class="col-sm-5">Teléfono</dt>
                                <dd class="col-sm-7">
                                    <?php
                                    $lada_ca = trim((string)($ca['inm_co_acreditado_lada'] ?? ''));
                                    $num_ca  = trim((string)($ca['inm_co_acreditado_numero'] ?? ''));
                                    $tel_ca  = trim("$lada_ca $num_ca");
                                    echo $tel_ca !== '' ? htmlspecialchars($tel_ca) : '<span class="text-muted">—</span>';
                                    ?>
                                </dd>

                                <dt class="col-sm-5">Correo</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_correo'] ?? '') ?></dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Empresa co-acreditado -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Empresa</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_nombre_empresa'] ?? '') ?></dd>

                                <dt class="col-sm-5">Razón social</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_razon_social'] ?? '') ?></dd>

                                <dt class="col-sm-5">Reg. patronal</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_nrp'] ?? '') ?></dd>

                                <dt class="col-sm-5">Tel. empresa</dt>
                                <dd class="col-sm-7">
                                    <?php
                                    $lada_nep_ca = trim((string)($ca['inm_co_acreditado_lada_nep'] ?? ''));
                                    $num_nep_ca  = trim((string)($ca['inm_co_acreditado_numero_nep'] ?? ''));
                                    $ext_nep_ca  = trim((string)($ca['inm_co_acreditado_extension_nep'] ?? ''));
                                    $tel_nep_ca  = trim("$lada_nep_ca $num_nep_ca" . ($ext_nep_ca ? " Ext.$ext_nep_ca" : ''));
                                    echo $tel_nep_ca !== '' ? htmlspecialchars($tel_nep_ca) : '<span class="text-muted">—</span>';
                                    ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Núm. crédito</dt>
                                <dd class="col-sm-7"><?= $val($ca['inm_co_acreditado_numero_credito'] ?? '') ?></dd>

                                <dt class="col-sm-5">Adeudo hipoteca</dt>
                                <dd class="col-sm-7">$<?= number_format((float)($ca['inm_co_acreditado_adeudo_hipoteca'] ?? 0), 2) ?></dd>
                            </dl>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>


        <?php
        /* ============================================================
         * 3. CÓNYUGE
         * ============================================================ */
        $cy = $controlador->registro->inm_conyuge;
        ?>
        <?php if ($conyuge_tiene_datos): ?>
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fa fa-heart"></i> CÓNYUGE</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre completo</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_nombre_completo ?? '') ?></dd>

                            <dt class="col-sm-5">Lugar y fecha nac.</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_lugar_fecha_nac ?? '') ?></dd>

                            <dt class="col-sm-5">Edad</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_edad ?? '') ?></dd>

                            <dt class="col-sm-5">Estado civil</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_estado_civil ?? '') ?></dd>

                            <dt class="col-sm-5">Nacionalidad</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_nacionalidad_descripcion ?? '') ?></dd>

                            <dt class="col-sm-5">Ocupación</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_ocupacion_descripcion ?? '') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">CURP</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_curp ?? '') ?></dd>

                            <dt class="col-sm-5">RFC</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_rfc ?? '') ?></dd>

                            <dt class="col-sm-5">Tel. casa</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_telefono_casa ?? '') ?></dd>

                            <dt class="col-sm-5">Celular</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_telefono_celular ?? '') ?></dd>

                            <dt class="col-sm-5">Núm. crédito</dt>
                            <dd class="col-sm-7"><?= $val($cy->inm_conyuge_numero_credito ?? '') ?></dd>

                            <dt class="col-sm-5">Adeudo hipoteca</dt>
                            <dd class="col-sm-7">$<?= number_format((float)($cy->inm_conyuge_adeudo_hipoteca ?? 0), 2) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>


        <?php
        /* ============================================================
         * 4. BENEFICIARIOS (agrupados por tipo)
         * ============================================================ */
        $hay_beneficiarios = false;
        foreach ($controlador->registro->inm_tipo_beneficiarios as $tb) {
            if (!empty($tb->inm_beneficiarios)) { $hay_beneficiarios = true; break; }
        }
        ?>
        <?php if ($hay_beneficiarios): ?>
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-child"></i> BENEFICIARIOS</h5>
            </div>
            <div class="card-body">
                <!--<p class="text-muted small mb-3">
                    Solo pueden designar como beneficiario a padres, hijos y/o esposa(o).
                    En caso de unión libre no se puede designar al cónyuge como beneficiario.
                    Cuando el crédito es conyugal, ambos deben designar uno o dos beneficiarios.
                </p>-->

                <?php foreach ($controlador->registro->inm_tipo_beneficiarios as $inm_tipo_beneficiario): ?>
                    <?php if (empty($inm_tipo_beneficiario->inm_beneficiarios)) continue; ?>
                    <h6 class="text-secondary border-bottom pb-1 mb-2">
                        <?= htmlspecialchars($inm_tipo_beneficiario->inm_tipo_beneficiario_descripcion ?? '') ?>
                    </h6>
                    <table class="table table-sm table-striped mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellido paterno</th>
                                <th>Apellido materno</th>
                                <th>Parentesco</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($inm_tipo_beneficiario->inm_beneficiarios as $i => $ben): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $val($ben->inm_beneficiario_nombre ?? '') ?></td>
                                <td><?= $val($ben->inm_beneficiario_apellido_paterno ?? '') ?></td>
                                <td><?= $val($ben->inm_beneficiario_apellido_materno ?? '') ?></td>
                                <td><?= $val($ben->inm_parentesco_descripcion ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>


        <?php
        /* ============================================================
         * 5. REFERENCIAS
         * ============================================================ */
        ?>
        <?php if (!empty($controlador->registro->inm_referencias)): ?>
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fa fa-address-book"></i> REFERENCIAS</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre completo</th>
                            <th>Parentesco</th>
                            <th>Celular</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($controlador->registro->inm_referencias as $i => $ref): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $val($ref->inm_referencia_prospecto_nombre_completo ?? '') ?></td>
                            <td><?= $val($ref->inm_parentesco_descripcion ?? '') ?></td>
                            <td><?= $val($ref->inm_referencia_prospecto_celular ?? '') ?></td>
                            <td><?= $val($ref->inm_referencia_prospecto_telefono ?? '') ?></td>
                            <td>
                                <?php
                                $calle_ref = trim((string)($ref->inm_referencia_calle ?? ''));
                                $next_ref  = trim((string)($ref->inm_referencia_numero_exterior ?? ''));
                                $nint_ref  = trim((string)($ref->inm_referencia_numero_interior ?? ''));
                                $col_ref   = trim((string)($ref->dp_colonia_postal_descripcion ?? ''));
                                $dir_parts = array_filter([$calle_ref, $next_ref, $nint_ref ? "Int.$nint_ref" : '', $col_ref]);
                                echo $dir_parts ? htmlspecialchars(implode(', ', $dir_parts)) : '<span class="text-muted">—</span>';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /container-fluid -->
    <br>
</main>

