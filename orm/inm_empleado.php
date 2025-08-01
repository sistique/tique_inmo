<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use PDO;


class inm_empleado extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_empleado';
        $columnas = array($tabla=>false,'adm_usuario'=>$tabla,'inm_horario'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Empleado';
    }


    public function valida_usuario(string $password, string $usuario){
        if($usuario === ''){
            return $this->error->error(mensaje: 'El usuario no puede ir vacio',data: $usuario);
        }

        if($password === ''){
            return $this->error->error(mensaje: 'El $password no puede ir vacio',data: $password);
        }

        $filtro['adm_usuario.user'] = $usuario;
        $filtro['adm_usuario.password'] = $password;
        $filtro['adm_usuario.status'] = 'activo';

        $r_usuario = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener usuario',data: $r_usuario);
        }

        if((int)$r_usuario->n_registros === 0){
            return $this->error->error(mensaje: 'Error al validar usuario y pass ',data: $usuario);
        }
        if((int)$r_usuario->n_registros > 1){
            return $this->error->error(mensaje: 'Error al validar usuario y pass ',data: $usuario);
        }

        return $r_usuario->registros[0];
    }
}