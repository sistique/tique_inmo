<?php
namespace gamboamartin\organigrama\links\secciones;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use stdClass;
use PDO;

class link_org_empresa extends links_menu {
    public stdClass $links;
    

    private function link_org_empresa_alta(): array|string
    {
        $org_empresa_alta = $this->org_empresa_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener link de org_empresa alta', data: $org_empresa_alta);
        }

        $org_empresa_alta.="&session_id=$this->session_id";
        return $org_empresa_alta;
    }

    /**
     * @param int $registro_id
     * @return array|string
     */
    private function link_org_empresa_ubicacion(int $registro_id): array|string
    {
        $org_empresa_ubicacion = $this->org_empresa_ubicacion(registro_id:$registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener link de org_empresa ubicacion', data: $org_empresa_ubicacion);
        }

        $org_empresa_ubicacion.="&session_id=$this->session_id";
        return $org_empresa_ubicacion;
    }

    /**
     * Genera un link con llama seccion org sucursal accion alta_sucursal_bd con el registro aplicado de emepresa
     * @param PDO $link
     * @param int $org_empresa_id identificador de empresa
     * @return string
     * @version 0.251.34
     */
    final public function link_org_sucursal_alta_bd(PDO $link, int $org_empresa_id): string
    {

        $link = $this->link_con_id(accion:'alta_sucursal_bd', link: $link,registro_id: $org_empresa_id,seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }

        return $link;
    }

    public function link_org_departamento_alta_bd(PDO $link, int $org_empresa_id): string
    {

        $link = $this->link_con_id(accion:'alta_departamento_bd',link: $link, registro_id: $org_empresa_id,seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }

        return $link;
    }

    /**
     * Genera un link de registro patronal
     * @param int $org_empresa_id Empresa seleccionada
     * @return string
     * @version 0.272.35
     */
    public function link_im_registro_patronal_alta_bd(PDO $link, int $org_empresa_id): string
    {

        $link = $this->link_con_id(accion:'alta_registro_patronal_bd', link: $link, registro_id: $org_empresa_id,
            seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }

        return $link;
    }


    /**
     * Genera un link de sucursal modifica
     * @param PDO $link
     * @param int $org_empresa_id Empresa
     * @param int $org_sucursal_id sucursal ligada a empresa
     * @return string
     * @version 0.265.35
     */
    public function link_org_sucursal_modifica_bd(PDO $link, int $org_empresa_id, int $org_sucursal_id): string
    {
        $link = $this->link_con_id(accion:'modifica_sucursal_bd',link: $link, registro_id: $org_empresa_id,seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }
        $link .= '&org_sucursal_id='.$org_sucursal_id;

        return $link;
    }

    /**
     * Genera un link de tipo empresa
     * @param PDO $link Conexion a base de datos
     * @param int $org_empresa_id Empresa id
     * @param int $org_departamento_id departamento id
     * @return string
     * @version 5.32.9
     */
    final public function link_org_departamento_modifica_bd(PDO $link, int $org_empresa_id,
                                                            int $org_departamento_id): string
    {
        $link = $this->link_con_id(accion:'modifica_departamento_bd', link: $link,registro_id: $org_empresa_id,
            seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }
        $link .= '&org_departamento_id='.$org_departamento_id;

        return $link;
    }

    /**
     * @param PDO $link
     * @param int $org_empresa_id
     * @param int $im_registro_patronal_id
     * @return string
     */
    public function link_im_registro_patronal_modifica_bd(PDO $link, int $org_empresa_id, int $im_registro_patronal_id): string
    {
        $link = $this->link_con_id(accion:'modifica_registro_patronal_bd', link: $link, registro_id: $org_empresa_id,seccion:  'org_empresa');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $link);
        }
        $link .= '&im_registro_patronal_id='.$im_registro_patronal_id;

        return $link;
    }

    /**
     * Integra los links de una empresa
     * @param PDO $link Conexion a la base de datos
     * @param int $registro_id Registro en proceso
     * @return stdClass|array
     */
    protected function links(PDO $link, int $registro_id): stdClass|array
    {

        $links =  parent::links(link: $link,registro_id: $registro_id); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar links', data: $links);
        }

        $org_empresa_alta = $this->link_org_empresa_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $org_empresa_alta);
        }

        if(!isset($this->links->org_empresa)) {
            $this->links->org_empresa = new stdClass();
        }

        $this->links->org_empresa->nueva_empresa = $org_empresa_alta;

        $org_empresa_ubicacion = $this->link_org_empresa_ubicacion(registro_id: $registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $org_empresa_ubicacion);
        }

        $this->links->org_empresa->ubicacion = $org_empresa_ubicacion;



        return $links;
    }

    /**
     * Genera un link a empresa alta sin session_id
     * @return string Un link de tipo seccion org_empresa accion alta
     * @version 0.6.0
     */
    private function org_empresa_alta(): string
    {
        return "./index.php?seccion=org_empresa&accion=alta";
    }

    /**
     * Genera un link de tipo ubicacion
     * @param int $registro_id Registro identificador
     * @version 0.85.22
     * @verfuncion 0.1.0
     * @author mgamboa
     * @fecha 2022-07-30 13:38
     * @return string
     */
    private function org_empresa_ubicacion(int $registro_id): string
    {
        return "./index.php?seccion=org_empresa&accion=ubicacion&registro_id=$registro_id";
    }


}
