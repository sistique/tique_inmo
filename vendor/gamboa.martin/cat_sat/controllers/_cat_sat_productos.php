<?php
namespace gamboamartin\cat_sat\controllers;
use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use PDO;
use stdClass;

class _cat_sat_productos extends _cat_sat_base{



    final protected function init(stdClass $paths_conf): array|stdClass
    {
        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            return  $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
        }

        $init_controladores = $this->init_controladores(paths_conf: $paths_conf);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar controladores', data: $init_controladores);
        }

        $init_links = $this->init_links();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
        }
        $data = new stdClass();
        $data->configuraciones = $configuraciones;
        $data->init_controladores = $init_controladores;
        $data->init_links = $init_links;

        $this->lista_get_data = true;

        return $data;
    }

    /**
     * Inicializa los links de la entidad en proceso esta funcion debe ser declarada en los childs
     * @return array|string
     */
    private function init_links(): array|string
    {

        return '';
    }

    /**
     * Inicializa las configuraciones esta funcion debe ser declarada en los childs
     * @return controler
     */
    private function init_configuraciones(): controler
    {
        return $this;
    }

    /**
     * Inicializa controladores relacionados a la entidad esta funcion debe ser declarada en los childs
     * @param stdClass $paths_conf
     * @return controler
     */
    private function init_controladores(stdClass $paths_conf): controler
    {

        return $this;
    }

    /**
     * Inicializa los elementos de tipo datatable esta funcion debe ser declarada en los childs
     * @return stdClass
     * @version 7.40.1
     */
    private function init_datatable(): stdClass
    {
        return new stdClass();
    }

    /**
     * inicializa los elementos del controlador
     * @param PDO $link Conexion a la base de datos
     * @return array|stdClass
     * @version 7.45.2
     */
    final protected function init_parent(PDO $link): array|stdClass
    {
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
        }
        $data = new stdClass();
        $data->obj_link = $obj_link;
        $data->datatables = $datatables;
        return $data;
    }

}
