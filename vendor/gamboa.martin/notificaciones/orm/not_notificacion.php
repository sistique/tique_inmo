<?php

namespace gamboamartin\notificaciones\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class not_notificacion extends _modelo_parent
{
    public function __construct(PDO $link, array $childrens = array())
    {
        $tabla = 'not_notificacion';
        $columnas = array($tabla => false, 'adm_accion' => $tabla);

        $campos_obligatorios = array('adm_usuario_accion_id', 'adm_usuario_notificado_id', 'adm_accion_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Notificación';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta correo', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    protected function inicializa_campos(array $registros): array
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        $registros['descripcion'] = 'Notificación ' . $registros['codigo'];

        return $registros;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('descripcion')): array|stdClass
    {
        $modifica = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error de validacion', data: $modifica);

        }

        return $modifica;
    }
}