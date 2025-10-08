<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\errores\errores;
use gamboamartin\plugins\imagen;
use gamboamartin\plugins\pdf;
use gamboamartin\plugins\web;
use PDO;
use stdClass;

class gt_proveedor extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_proveedor';
        $columnas = array($tabla => false, 'cat_sat_regimen_fiscal' => $tabla, 'gt_tipo_proveedor' => $tabla,
            'dp_colonia_postal' => $tabla, 'dp_cp' => 'dp_colonia_postal', 'dp_municipio' => 'dp_cp',
            'dp_estado' => 'dp_municipio', 'dp_pais' => 'dp_estado');
        $campos_obligatorios = array('gt_tipo_proveedor_id', 'dp_colonia_postal_id', 'cat_sat_regimen_fiscal_id',
            'rfc', 'exterior', 'telefono_1', 'contacto_1', 'pagina_web');

        $no_duplicados = array();

        $tipo_campos['telefono_1'] = 'telefono_mx';
        $tipo_campos['telefono_2'] = 'telefono_mx';
        $tipo_campos['telefono_3'] = 'telefono_mx';


        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar autorizante', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    protected function inicializa_campos(array $registros): array
    {
        $keys = array('dp_colonia_postal_id', 'cat_sat_regimen_fiscal_id', 'gt_tipo_proveedor_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar campos', data: $valida);
        }

        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        $registros['codigo'] .= " - " . $registros['rfc'];
        $registros['descripcion'] = $registros['razon_social'];
        $registros['descripcion_select'] = $registros['razon_social'] . " " . $registros['rfc'];

        return $registros;
    }

    public function leer_codigo_qr(): array|stdClass
    {
        if (!array_key_exists('documento', $_FILES)) {
            return $this->error->error(mensaje: 'Error no existe documento', data: $_FILES);
        }

        $directorio_destino = 'archivos/temporales/pdf/cliente_'. $_GET['registro_id'].'/';

        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $nombre_archivo = basename($_FILES['documento']['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;

        if (!move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_destino)) {
            return $this->error->error(mensaje: 'Error al mover archivo', data: $_FILES);
        }

        $nombre_directorio_imagen = 'archivos/temporales/imagenes/cliente_'.$_GET['registro_id'].'/';

        $contenido = (new pdf())->leer_pdf(directorio: $nombre_directorio_imagen, prefijo_imagen: "imagen",
            ruta_pdf: $ruta_destino);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer pdf', data: $contenido);
        }

        $ruta_qr = (new imagen())->obtener_qr($contenido['imagenes']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener qr', data: $ruta_qr);
        }

        $url = (new imagen())->leer_codigo_qr(ruta_qr: $ruta_qr);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer código QR', data: $url);
        }

        $directorio_borrado = (new doc_documento($this->link))->borrar_directorio($directorio_destino);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al borrar directorio', data: $directorio_borrado);
        }

        $directorio_borrado = (new doc_documento($this->link))->borrar_directorio($nombre_directorio_imagen);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al borrar directorio', data: $directorio_borrado);
        }

        $contenido = (new web())->leer_contenido(url: $url);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer contenido', data: $contenido);
        }

        /*$contenido_formateado = $this->contenido_web_formateado(html: $contenido);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al formatear contenido', data: $contenido_formateado);
        }

        $contenido_formateado = $this->contenido_anexar_campos(contenido: $contenido_formateado);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al anexar campos', data: $contenido_formateado);
        }

        return get_object_vars($contenido_formateado);*/
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $registro = $this->inicializa_campos(registros: $registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $registro);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar autorizante', data: $r_modifica_bd);
        }
        return $r_modifica_bd;
    }

    /**
     * Calcula el total de saldos de cotización para una etapa específica.
     *
     * @param array $registros Un array de registros de cotización.
     * @param string $etapa La etapa para la cual se desea calcular el total de saldos.
     * @return float El total de saldos de cotización para la etapa especificada.
     */
    private function calculo_total_saldos_cotizacion(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_cotizacion_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_cotizacion_id'])
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_cotizacion_producto($this->link),
                campo: 'gt_cotizacion_id',
                id: $id,
                campo_Total: 'gt_cotizacion_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Calcula el total de saldos de orden de compra para una etapa específica.
     *
     * @param array $registros Un array de registros de orden de compra.
     * @param string $etapa La etapa para la cual se desea calcular el total de saldos.
     * @return float El total de saldos de orden de compra para la etapa especificada.
     */
    private function calculo_total_saldos_orden_compra(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_cotizacion_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_cotizacion_id'])
            ->flatMap(fn($cotizacion_id) => $this->get_orden_compra_cotizacion($cotizacion_id))
            ->filter(fn($orden_compra_id) => $orden_compra_id > -1)
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_orden_compra_producto($this->link),
                campo: 'gt_orden_compra_id',
                id: $id,
                campo_Total: 'gt_orden_compra_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Obtiene los productos de una tabla según un campo y un ID especificados.
     *
     * @param modelo $tabla El modelo de la tabla de la base de datos.
     * @param string $campo El nombre del campo utilizado para filtrar los datos.
     * @param int $id El ID utilizado para filtrar los datos.
     * @param string $campo_Total El nombre del campo que contiene el total de los productos.
     * @return array|stdClass Un array o un objeto stdClass que contiene los datos de los productos.
     * Si se produce un error durante la obtención de los datos, se devuelve un objeto de error.
     */
    public function get_productos(modelo $tabla, string $campo, int $id, string $campo_Total): array|stdClass
    {
        $filtro = [$campo => $id];
        $datos = $tabla->filtro_and(
            columnas: [$campo_Total],
            filtro: $filtro
        );
        if (errores::$error) {
            return $this->error->error('Error al obtener los datos', $datos);
        }

        return Stream::of($datos->registros)
            ->map(fn($registro) => $registro[$campo_Total])
            ->toArray();
    }

    /**
     * Obtiene el ID de la orden de compra asociada a una cotización específica.
     *
     * @param int $gt_cotizacion_id El ID de la cotización.
     * @return int El ID de la orden de compra asociada a la cotización, o -1 si no se encuentra.
     * Si se produce un error durante la obtención de los datos, se devuelve un objeto de error.
     */
    public function get_orden_compra_cotizacion(int $gt_cotizacion_id): int
    {
        $filtro = ['gt_orden_compra_cotizacion.gt_cotizacion_id' => $gt_cotizacion_id];
        $orden = (new gt_orden_compra_cotizacion($this->link))->filtro_and(
            columnas: ['gt_orden_compra_id'],
            filtro: $filtro
        );
        if (errores::$error) {
            return $this->error->error('Error filtrar orden compra cotizacion', $orden);
        }

        return Stream::of($orden->registros)
            ->map(fn($registro) => $registro['gt_orden_compra_id'])
            ->findFirst() ?? -1;
    }

    /**
     * Calcula el total de saldos de cotización para un proveedor específico, dividido por etapa.
     *
     * @param int $gt_proveedor_id El ID del proveedor para el cual se desea calcular el total de saldos.
     * @return array|stdClass Un array o un objeto stdClass que contiene los totales de saldos de cotización.
     * Si se produce un error durante la obtención de los datos, se devuelve un objeto de error.
     */
    public function total_saldos_cotizacion(int $gt_proveedor_id): array|stdClass
    {
        $cotizaciones = Transaccion::getInstance(new gt_cotizacion($this->link), $this->error)
            ->get_registros('gt_proveedor_id', $gt_proveedor_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener cotizaciones', data: $cotizaciones);
        }

        $total_alta = $this->calculo_total_saldos_cotizacion(registros: $cotizaciones->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de cotizacion en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_cotizacion(registros: $cotizaciones->registros,
            etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de cotizacion autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }

    /**
     * Calcula el total de saldos de orden de compra para un proveedor específico, dividido por etapa.
     *
     * @param int $gt_proveedor_id El ID del proveedor para el cual se desea calcular el total de saldos.
     * @return array|stdClass Un array o un objeto stdClass que contiene los totales de saldos de orden de compra.
     * Si se produce un error durante la obtención de los datos, se devuelve un objeto de error.
     */
    public function total_saldos_orden_compra(int $gt_proveedor_id): array|stdClass
    {
        $cotizaciones = Transaccion::getInstance(new gt_cotizacion($this->link), $this->error)
            ->get_registros('gt_proveedor_id', $gt_proveedor_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener cotizaciones', data: $cotizaciones);
        }

        $total_alta = $this->calculo_total_saldos_orden_compra(registros: $cotizaciones->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de orden de compra en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_orden_compra(registros: $cotizaciones->registros, etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de orden de compra autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }
}