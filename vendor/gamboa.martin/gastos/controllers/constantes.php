<?php

namespace gamboamartin\gastos\controllers;

enum constantes: string
{
    case PR_ETAPA_ALTA = 'ALTA';
    case PR_ETAPA_AUTORIZADO = 'AUTORIZADO';
    case PR_ETAPA_RECHAZADO = 'RECHAZADO';
    case PR_ETAPA_FIN = 'FIN';
    case PR_ETAPA_EN_COTIZACION = 'EN COTIZACION';
    case PR_ETAPA_EN_ORDEN_COMPRA = 'EN ORDEN COMPRA';
    case PR_ETAPA_PENDIENTE_PAGO = 'PENDIENTE PAGO';
    case PR_ETAPA_PAGADA = 'PAGADA';
    case GT_TIPO_DEFAULT = 'DEFAULT';
}
