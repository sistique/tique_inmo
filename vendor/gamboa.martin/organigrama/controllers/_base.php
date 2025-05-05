<?php
namespace gamboamartin\organigrama\controllers;
use base\controller\controlador_base;
use gamboamartin\errores\errores;
use gamboamartin\system\actions;

use stdClass;
use Throwable;

class _base {

    private errores $error;

    public function __construct(){
        $this->error = new errores();
    }

    /**
     * TOTAL
     * Obtiene los datos de retorno basados en el nombre de la tabla.
     *
     * Esta función recibe el nombre de una tabla y devuelve un objeto que contiene
     * la sección de retorno y el ID de retorno inicializado, utilizando los valores
     * obtenidos mediante otras funciones internas. Retorna este objeto si el nombre
     * de la tabla proporcionado no está vacío, o un mensaje de error si está vacío.
     *
     * @param string $tabla El nombre de la tabla.
     * @return array|stdClass Retorna un objeto que contiene la sección de retorno y el ID de retorno inicializado,
     *                         o un mensaje de error si el nombre de la tabla está vacío.
     *
     * @url https://github.com/gamboamartin/organigrama/wiki/controllers._base.data_retorno.27.0.1
     */
    final public function data_retorno(string $tabla): array|stdClass
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla esta vacia',data:  $tabla, es_final: true);
        }

        $seccion_retorno = $this->seccion_retorno(tabla: $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener seccion_retorno',data:  $seccion_retorno);
        }

        $id_retorno = $this->id_retorno_init();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id_retorno',data:  $id_retorno);
        }
        $data = new stdClass();

        $data->seccion_retorno = $seccion_retorno;
        $data->id_retorno = $id_retorno;

        return $data;

    }

    public function header(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno, bool $ws): array|stdClass
    {
        $retorno = $this->id_retorno(result: $result,retorno:  $retorno);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data retorno',data:  $retorno);
        }

        $return = $this->result(controler: $controler,header:  $header,result:  $result,retorno:  $retorno,ws:  $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $return);
        }
        return $result;
    }


    private function id_retorno(stdClass $result, stdClass $retorno): stdClass
    {
        if((int)$retorno->id_retorno === -1){
            $retorno->id_retorno = $result->registro_id;
        }
        return $retorno;
    }

    /**
     * TOTAL
     * Inicializa el valor de retorno del ID basado en el valor enviado mediante POST.
     *
     * Esta función inicializa el valor de retorno del ID utilizando el valor enviado
     * mediante POST, o asigna un valor predeterminado de -1 si no se proporciona ningún valor.
     *
     * @return int El valor de retorno del ID inicializado.
     * @url https://github.com/gamboamartin/organigrama/wiki/controllers._base.id_retorno_init.27.0.0
     */
    private function id_retorno_init(): int
    {
        $id_retorno = -1;
        if(isset($_POST['id_retorno'])){
            $id_retorno = (int)$_POST['id_retorno'];
        }
        return $id_retorno;
    }

    private function result(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno, bool $ws): array|stdClass
    {
        $retorno_header = $this->result_header(controler: $controler, header: $header,result:  $result,retorno_data:  $retorno, ws: $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $retorno_header);
        }

        $retorno_ws = $this->result_ws(result: $result, ws: $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $retorno_ws);
        }
        return $result;
    }

    private function result_header(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno_data, bool $ws){
        if($header){
            $retorno = (new actions())->retorno_alta_bd(link: $controler->link,registro_id:$retorno_data->id_retorno,
                seccion: $retorno_data->seccion_retorno, siguiente_view: $result->siguiente_view);
            if(errores::$error){
                return $controler->retorno_error(mensaje: 'Error al dar de alta registro', data: $result, header:  true,
                    ws: $ws);
            }
            header('Location:'.$retorno);
            exit;
        }
        return $result;
    }

    private function result_ws(stdClass $result, bool $ws){
        if($ws){
            header('Content-Type: application/json');
            try {
                echo json_encode($result, JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                $error = $this->error->error(mensaje: 'Error al dar salida',data:  $e);
                print_r($error);
                exit;
            }

            exit;
        }
        return $result;
    }

    /**
     * TOTAL
     * Esta función se utiliza para obtener la sección de retorno a partir de una tabla dada y una solicitud POST.
     *
     * @param string $tabla Nombre de la tabla. Este valor se recorta y se verifica si está vacío.
     * @return string|array Si el campo 'seccion_retorno' está configurado en la solicitud POST, este se convierte en el valor de retorno.
     *                     De lo contrario, el nombre de la tabla se convierte en el valor de retorno.
     *                     En caso de una tabla vacía, devuelve un array que representa un error.
     *
     * @throws errores si el argumento $tabla está vacío.
     * @version 19.0.0
     * @url https://github.com/gamboamartin/organigrama/wiki/controllers._base.seccion_retorno.27.0.0
     */
    private function seccion_retorno(string $tabla): string|array
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla esta vacia',data:  $tabla, es_final: true);
        }
        $seccion_retorno = $tabla;
        if(isset($_POST['seccion_retorno'])){
            $seccion_retorno = $_POST['seccion_retorno'];
        }
        return $seccion_retorno;
    }
}
