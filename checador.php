<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_checada;

header("Content-Type: application/json");

$con = new conexion();
$link = conexion::$link;

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Datos no válidos"]);
    exit;
}

try {
    $modelo_checada = new inm_checada(link: $link);
    
    $r_alta_bd = $modelo_checada->alta_registro(registro: $data);
    if (errores::$error) {
        http_response_code(500);
        echo json_encode(["error" => $r_alta_bd]);
    }

    echo json_encode(["status" => "ok"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}