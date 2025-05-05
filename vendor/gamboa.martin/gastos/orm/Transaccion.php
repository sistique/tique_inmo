<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class Transaccion
{
    public modelo $modelo;
    public errores $error;

    /**
     * Constructor privado.
     *
     * @param modelo $modelo La tabla a la que pertenece la transacción.
     * @param errores $error El manejador de errores.
     */
    private function __construct(modelo $modelo)
    {
        $this->modelo = $modelo;
        $this->error = $modelo->error;
    }

    public static function of(modelo $modelo): Transaccion
    {
        return new Transaccion($modelo);
    }

    public function existe(array $filtro): array|stdClass
    {
        $existe = $this->modelo->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al filtrar {$this->modelo->tabla}", data: $existe);
        }

        return $existe;
    }


    /**
     * Crea una nueva instancia de Transaccion con la conexión proporcionada.
     *
     * @param modelo $modelo La tabla a la que pertenece la transacción.
     * @param errores $error El manejador de errores.
     *
     * @example $transaccion = Transaccion::getInstance($tabla, $error);
     */
    public static function getInstance(modelo $modelo, errores $error): self
    {
        return new self($modelo, $error);
    }

    /**
     * Filtra los registros de la tabla según un campo y un valor.
     *
     * @param string $campo El campo por el que se va a filtrar.
     * @param int $valor El valor por el que se va a filtrar.
     * @return array|stdClass Retorna los registros filtrados.
     * En caso de error, retorna un objeto de tipo stdClass con el error.
     */
    public function get_registros(string $campo, int $valor): array|stdClass
    {
        $tabla = $this->modelo->tabla;
        $filtro["$tabla.$campo"] = $valor;
        $registros = $this->modelo->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al filtrar $tabla", data: $registros);
        }

        return $registros;
    }


}