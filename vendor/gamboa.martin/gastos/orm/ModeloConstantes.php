<?php

namespace gamboamartin\gastos\models;

enum ModeloConstantes: string
{
    case PR_PROCESO_SOLICITUD = 'SOLICITUD';
    case PR_PROCESO_REQUISICION = 'REQUISICION';
    case PR_PROCESO_COTIZACION = 'COTIZACION';
    case PR_PROCESO_ORDEN_COMPRA = 'ORDEN COMPRA';
}
