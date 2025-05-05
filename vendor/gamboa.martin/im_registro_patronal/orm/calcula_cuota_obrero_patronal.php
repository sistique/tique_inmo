<?php

namespace gamboamartin\im_registro_patronal\models;
use gamboamartin\errores\errores;
use gamboamartin\template\html;
use gamboamartin\validacion\validacion;
use html\im_rcv_html;
use PDO;
use stdClass;
use gamboamartin\im_registro_patronal\models\im_uma;

class calcula_cuota_obrero_patronal{

    private errores $error;
    public float $porc_riesgo_trabajo = 0;
    public float $porc_enf_mat_cuota_fija = 20.4;
    public float $porc_enf_mat_cuota_adicional = 1.1;
    public float $porc_enf_mat_gastos_medicos = 1.05;
    public float $porc_enf_mat_pres_dinero = 0.7;
    public float $porc_invalidez_vida = 1.75;
    public float $porc_guarderia_prestaciones_sociales = 1;
    public float $porc_retiro = 2;
    public float $porc_ceav = 3.15;
    public float $porc_credito_vivienda = 5;

    public array $uma = array(2020=>86.88,2021=>89.62,2022=>96.22);

    public string $fecha = '';
    public string $year = '';

    public float $monto_uma = 0.0;
    public float $n_dias = 0.0;
    public float $sbc = 0.0;

    public stdClass $cuotas;

    public float $cuota_riesgo_trabajo = 0.0;
    public float $cuota_enf_mat_cuota_fija = 0.0;
    public float $cuota_enf_mat_cuota_adicional = 0.0;
    public float $cuota_enf_mat_gastos_medicos = 0.0;
    public float $cuota_enf_mat_pres_dinero = 0.0;
    public float $cuota_invalidez_vida = 0.0;
    public float $cuota_guarderia_prestaciones_sociales = 0.0;
    public float $cuota_retiro = 0.0;
    public float $cuota_ceav = 0.0;
    public float $cuota_credito_vivienda = 0.0;

    public float $total= 0.0;


    public function __construct(){
        $this->error = new errores();
        $this->cuotas = new stdClass();
    }

    private function calcula(PDO $link): bool|array
    {
        /**
         * UMA PARA DATABASE AJUSTAR
         */
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $im_uma = (new im_uma($link))->get_uma(fecha: $this->fecha);
        if(errores::$error){
            return $this->error->error('Error al obtener registros de UMA', $im_uma);
        }
        if($im_uma->n_registros <= 0){
            return $this->error->error('Error no exsite registro de UMA', $im_uma);
        }

        $im_rcv = (new im_rcv($link))->filtro_por_montos(monto: $this->sbc);
        if(errores::$error){
            return $this->error->error('Error al obtener registros por monto', $im_rcv);
        }
        if($im_rcv->n_registros <= 0){
            return $this->error->error('Error no exsite registro de monto', $im_rcv);
        }

        if(!isset($im_uma->registros[0]['im_uma_monto'])){
            return $this->error->error('Error el uma no tiene monto asignado', $im_uma);
        }
        if(is_null($im_uma->registros[0]['im_uma_monto'])){
            return $this->error->error('Error el uma no tiene monto asignado', $im_uma);
        }

        $this->monto_uma = $im_uma->registros[0]['im_uma_monto'];

        $riesgo_de_trabajo = $this->riesgo_de_trabajo();
        if(errores::$error){
            return $this->error->error('Error al obtener riesgo_de_trabajo', $riesgo_de_trabajo);
        }
        $this->cuotas->riesgo_de_trabajo = $riesgo_de_trabajo;

        $enf_mat_cuota_fija = $this->enf_mat_cuota_fija();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_cuota_fija', $enf_mat_cuota_fija);
        }
        $this->cuotas->enf_mat_cuota_fija = $enf_mat_cuota_fija;

        $enf_mat_cuota_adicional = $this->enf_mat_cuota_adicional();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_cuota_adicional', $enf_mat_cuota_adicional);
        }
        $this->cuotas->enf_mat_cuota_adicional = $enf_mat_cuota_adicional;

        $enf_mat_gastos_medicos = $this->enf_mat_gastos_medicos();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_gastos_medicos', $enf_mat_gastos_medicos);
        }
        $this->cuotas->enf_mat_gastos_medicos = $enf_mat_gastos_medicos;

        $enf_mat_pres_dinero = $this->enf_mat_pres_dinero();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_pres_dinero', $enf_mat_pres_dinero);
        }
        $this->cuotas->enf_mat_pres_dinero = $enf_mat_pres_dinero;

        $invalidez_vida = $this->invalidez_vida();
        if(errores::$error){
            return $this->error->error('Error al obtener invalidez_vida', $invalidez_vida);
        }
        $this->cuotas->invalidez_vida = $invalidez_vida;

        $guarderia_prestaciones_sociales = $this->guarderia_prestaciones_sociales();
        if(errores::$error){
            return $this->error->error('Error al obtener guarderia_prestaciones_sociales',
                $guarderia_prestaciones_sociales);
        }
        $this->cuotas->guarderia_prestaciones_sociales = $guarderia_prestaciones_sociales;

        $retiro = $this->retiro();
        if(errores::$error){
            return $this->error->error('Error al obtener retiro', $retiro);
        }
        $this->cuotas->retiro = $retiro;

        $ceav = $this->ceav(porcentaje_ceav: $im_rcv->registros[0]['im_rcv_factor']);
        if(errores::$error){
            return $this->error->error('Error al obtener ceav', $ceav);
        }
        $this->cuotas->ceav = $ceav;

        $credito_vivienda = $this->credito_vivienda();
        if(errores::$error){
            return $this->error->error('Error al obtener credito_vivienda', $credito_vivienda);
        }
        $this->cuotas->credito_vivienda = $credito_vivienda;

        $total = $this->total();
        if(errores::$error){
            return $this->error->error('Error al obtener total', $total);
        }

        return true;
    }

    public function cuota_obrero_patronal(float $porc_riesgo_trabajo, string $fecha, float $n_dias, float $sbc,
                                          PDO $link){
        $valida = $this->valida_cuota(fecha: $fecha,n_dias:  $n_dias, porc_riesgo_trabajo: $porc_riesgo_trabajo,
            sbc: $sbc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar', data: $valida);
        }

        $this->porc_riesgo_trabajo = $porc_riesgo_trabajo;
        $this->fecha = $fecha;
        $this->n_dias = $n_dias;
        $this->sbc = $sbc;

        $calculo = $this->calcula(link: $link);
        if(errores::$error){
            return $this->error->error('Error al obtener calcular', $calculo);
        }

        return $calculo;
    }

    private function ceav(int|float $porcentaje_ceav){

        $this->porc_ceav = $porcentaje_ceav;

        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_ceav * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_ceav = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_ceav;
    }

    private function credito_vivienda(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_credito_vivienda * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_credito_vivienda = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_credito_vivienda;
    }

    private function enf_mat_cuota_fija(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_enf_mat_cuota_fija * $this->monto_uma,6);
        $total_cuota = round($cuota_diaria * $this->n_dias,6);
        $this->cuota_enf_mat_cuota_fija = round($total_cuota/100,6);

        return $this->cuota_enf_mat_cuota_fija;
    }

    private function enf_mat_cuota_adicional(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $excedente = 0;
        $tres_umas = round($this->monto_uma * 3,2);
        if($this->sbc > $tres_umas){
            $excedente = round($this->sbc - $tres_umas,2);
        }

        $cuota_diaria = round($this->porc_enf_mat_cuota_adicional * $excedente,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_enf_mat_cuota_adicional = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_enf_mat_cuota_adicional;
    }

    private function enf_mat_gastos_medicos(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_enf_mat_gastos_medicos * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_enf_mat_gastos_medicos = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_enf_mat_gastos_medicos;
    }

    private function enf_mat_pres_dinero(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_enf_mat_pres_dinero * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_enf_mat_pres_dinero = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_enf_mat_pres_dinero;
    }

    private function guarderia_prestaciones_sociales(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_guarderia_prestaciones_sociales * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_guarderia_prestaciones_sociales = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_guarderia_prestaciones_sociales;
    }

    private function invalidez_vida(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_invalidez_vida * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_invalidez_vida = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_invalidez_vida;
    }

    private function retiro(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_retiro * $this->sbc,6);
        $cuota_diaria = round($cuota_diaria/100,6);
        $this->cuota_retiro = round($cuota_diaria * $this->n_dias,6);

        return $this->cuota_retiro;
    }

    private function riesgo_de_trabajo(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria =  round($this->sbc * $this->n_dias ,6);
        $res = round($cuota_diaria * $this->porc_riesgo_trabajo,6);
        $this->cuota_riesgo_trabajo = round($res/100,6);

        return $this->cuota_riesgo_trabajo;
    }

    private function total(){
        if($this->cuota_riesgo_trabajo<=0.0){
            return $this->error->error('Error cuota_riesgo_trabajo debe ser mayor a 0',
                $this->cuota_riesgo_trabajo);
        }
        if($this->cuota_enf_mat_cuota_fija<=0.0){
            return $this->error->error('Error cuota_enf_mat_cuota_fija debe ser mayor a 0',
                $this->cuota_enf_mat_cuota_fija);
        }
        if($this->cuota_enf_mat_gastos_medicos<=0.0){
            return $this->error->error('Error cuota_enf_mat_gastos_medicos debe ser mayor a 0',
                $this->cuota_enf_mat_gastos_medicos);
        }
        if($this->cuota_enf_mat_pres_dinero<0.0){
            return $this->error->error('Error cuota_enf_mat_pres_dinero debe ser mayor a 0',
                $this->cuota_enf_mat_pres_dinero);
        }
        if($this->cuota_invalidez_vida<=0.0){
            return $this->error->error('Error cuota_invalidez_vida debe ser mayor a 0',
                $this->cuota_invalidez_vida);
        }
        if($this->cuota_guarderia_prestaciones_sociales<0.0){
            return $this->error->error('Error cuota_guarderia_prestaciones_sociales debe ser mayor a 0',
                $this->cuota_guarderia_prestaciones_sociales);
        }
        if($this->cuota_retiro<0.0){
            return $this->error->error('Error cuota_retiro debe ser mayor a 0', $this->cuota_retiro);
        }
        if($this->cuota_ceav<0.0){
            return $this->error->error('Error cuota_ceav debe ser mayor a 0', $this->cuota_ceav);
        }
        if($this->cuota_credito_vivienda<0.0){
            return $this->error->error('Error cuota_credito_vivienda debe ser mayor a 0',
                $this->cuota_credito_vivienda);
        }

        $this->total = $this->cuota_riesgo_trabajo + $this->cuota_enf_mat_cuota_fija +
            $this->cuota_enf_mat_cuota_adicional + $this->cuota_enf_mat_gastos_medicos +
            $this->cuota_enf_mat_pres_dinero + $this->cuota_invalidez_vida +
            $this->cuota_guarderia_prestaciones_sociales + $this->cuota_retiro + $this->cuota_ceav +
            $this->cuota_credito_vivienda;

        $this->total = round($this->total,2);

        return $this->total;
    }

    private function valida_parametros(){
        if($this->porc_riesgo_trabajo<=0){
            return $this->error->error('Error riesgo de trabajo debe ser mayor a 0', $this->porc_riesgo_trabajo);
        }
        if($this->sbc<=0){
            return $this->error->error('Error sbc debe ser mayor a 0', $this->sbc);
        }
        if($this->n_dias<=0){
            return $this->error->error('Error n_dias debe ser mayor a 0', $this->n_dias);
        }

        return true;
    }

    private function valida_cuota(string $fecha, float $n_dias, float $porc_riesgo_trabajo, float $sbc): bool|array
    {
        $valida = (new validacion())->valida_fecha(fecha: $fecha);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar fecha', data: $valida);
        }

        if($porc_riesgo_trabajo<=0.0){
            return $this->error->error(mensaje: 'Error al validar porc_riesgo_trabajo', data: $porc_riesgo_trabajo);
        }
        if($n_dias<=0.0){
            return $this->error->error(mensaje: 'Error al validar n_dias', data: $n_dias);
        }
        if($sbc<=0.0){
            return $this->error->error(mensaje: 'Error al validar sbc', data: $sbc);
        }
        return true;
    }
}
