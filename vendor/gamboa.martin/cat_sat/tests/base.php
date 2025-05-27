<?php

namespace gamboamartin\cat_sat\tests;

use base\orm\modelo_base;
use gamboamartin\cat_sat\models\cat_sat_clase_producto;
use gamboamartin\cat_sat\models\cat_sat_division_producto;
use gamboamartin\cat_sat\models\cat_sat_grupo_producto;
use gamboamartin\cat_sat\models\cat_sat_producto;
use gamboamartin\cat_sat\models\cat_sat_tipo_producto;
use gamboamartin\errores\errores;
use PDO;

class base
{

    public function alta_cat_sat_clase_producto(PDO $link, int $id = 1, string $codigo = "1", string $descripcion = "1",
                                                int $cat_sat_tipo_producto_id = 1, int $cat_sat_division_producto_id = 1,
                                                int $cat_sat_grupo_producto_id = 1): array|int
    {
        $existe = (new cat_sat_clase_producto($link))->existe_by_id(registro_id: $id);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe clase producto', data: $existe);
        }

        if ($existe) {
            return $id;
        }

        $cat_sat_tipo_producto_id = $this->alta_cat_sat_tipo_producto(link: $link, id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar tipo producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_division_producto_id = $this->alta_cat_sat_division_producto(link: $link, id: $cat_sat_division_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar division producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_grupo_producto_id = $this->alta_cat_sat_grupo_producto(link: $link, id: $cat_sat_grupo_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id, cat_sat_division_producto_id: $cat_sat_division_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar grupo producto', $cat_sat_tipo_producto_id);
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion;
        $registro['alias'] = $codigo;
        $registro['codigo_bis'] = $codigo;
        $registro['cat_sat_grupo_producto_id'] = $cat_sat_grupo_producto_id;

        $alta = (new cat_sat_division_producto($link))->alta_registro($registro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar clase producto', data: $alta);
        }

        return $alta->registro_id;
    }

    public function alta_cat_sat_division_producto(PDO $link, int $id = 1, string $codigo = "1", string $descripcion = "1",
                                                   int $cat_sat_tipo_producto_id = 1): array|int
    {
        $existe = (new cat_sat_division_producto($link))->existe_by_id(registro_id: $id);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe division producto', data: $existe);
        }

        if ($existe) {
            return $id;
        }

        $cat_sat_tipo_producto_id = $this->alta_cat_sat_tipo_producto(link: $link, id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar tipo producto', $cat_sat_tipo_producto_id);
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion;
        $registro['alias'] = $codigo;
        $registro['codigo_bis'] = $codigo;
        $registro['cat_sat_tipo_producto_id'] = $cat_sat_tipo_producto_id;

        $alta = (new cat_sat_division_producto($link))->alta_registro($registro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar division producto', data: $alta);
        }

        return $alta->registro_id;
    }

    public function alta_cat_sat_grupo_producto(PDO $link, int $id = 1, string $codigo = "1", string $descripcion = "1",
                                                int $cat_sat_tipo_producto_id = 1, int $cat_sat_division_producto_id = 1):
    array|int
    {
        $existe = (new cat_sat_grupo_producto($link))->existe_by_id(registro_id: $id);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe grupo producto', data: $existe);
        }

        if ($existe) {
            return $id;
        }

        $cat_sat_tipo_producto_id = $this->alta_cat_sat_tipo_producto(link: $link, id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar tipo producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_division_producto_id = $this->alta_cat_sat_division_producto(link: $link, id: $cat_sat_division_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar division producto', $cat_sat_tipo_producto_id);
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion;
        $registro['alias'] = $codigo;
        $registro['codigo_bis'] = $codigo;
        $registro['cat_sat_division_producto_id'] = $cat_sat_division_producto_id;

        $alta = (new cat_sat_division_producto($link))->alta_registro($registro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar grupo producto', data: $alta);
        }

        return $alta->registro_id;
    }

    public function alta_cat_sat_producto(PDO $link, int $id = 1, string $codigo = "1", string $descripcion = "1",
                                          int $cat_sat_tipo_producto_id = 1, int $cat_sat_division_producto_id = 1,
                                          int $cat_sat_grupo_producto_id = 1, int $cat_sat_clase_producto_id = 1):
    array|int
    {
        $existe = (new cat_sat_producto($link))->existe_by_id(registro_id: $id);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe producto', data: $existe);
        }

        if ($existe) {
            return $id;
        }

        $cat_sat_tipo_producto_id = $this->alta_cat_sat_tipo_producto(link: $link, id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar tipo producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_division_producto_id = $this->alta_cat_sat_division_producto(link: $link, id: $cat_sat_division_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar division producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_grupo_producto_id = $this->alta_cat_sat_grupo_producto(link: $link, id: $cat_sat_grupo_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id, cat_sat_division_producto_id: $cat_sat_division_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar grupo producto', $cat_sat_tipo_producto_id);
        }

        $cat_sat_clase_producto_id = $this->alta_cat_sat_clase_producto(link: $link, id: $cat_sat_clase_producto_id,
            cat_sat_tipo_producto_id: $cat_sat_tipo_producto_id, cat_sat_division_producto_id: $cat_sat_division_producto_id,
            cat_sat_grupo_producto_id: $cat_sat_grupo_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al insertar grupo producto', $cat_sat_tipo_producto_id);
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion;
        $registro['alias'] = $codigo;
        $registro['codigo_bis'] = $codigo;
        $registro['cat_sat_clase_producto_id'] = $cat_sat_clase_producto_id;

        $alta = (new cat_sat_division_producto($link))->alta_registro($registro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar clase producto', data: $alta);
        }

        return $alta->registro_id;
    }

    public function alta_cat_sat_tipo_producto(PDO $link, int $id = 1, string $codigo = "1", string $descripcion = "1"):
    array|int
    {
        $existe = (new cat_sat_tipo_producto($link))->existe_by_id(registro_id: $id);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe tipo producto', data: $existe);
        }

        if ($existe) {
            return $id;
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion;
        $registro['alias'] = $codigo;
        $registro['codigo_bis'] = $codigo;

        $alta = (new cat_sat_tipo_producto($link))->alta_registro($registro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar tipo producto', data: $alta);
        }

        return $alta->registro_id;
    }

    public function delete(PDO $link, string $name_model, int $id): array
    {
        $entidad = explode("\\", $name_model)[3];

        $model = (new modelo_base($link))
            ->genera_modelo(modelo: $name_model)
            ->elimina_con_filtro_and(filtro: array($entidad . ".id" => $id));
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al eliminar ' . $name_model, data: $model);
        }

        return $model;
    }

}