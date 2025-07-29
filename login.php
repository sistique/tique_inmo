<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_empleado;

header("Content-Type: application/json");

$con = new conexion();
$link = conexion::$link;

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Datos no validos"]);
    exit;
}

try {
    $modelo_empleado = new inm_empleado(link: $link);

    $usuario = $modelo_empleado->valida_usuario(password: $data['password'], usuario: $data['usuario']);
    if (errores::$error) {
        http_response_code(500);
        echo json_encode(["error" => $usuario]);
    }
    
    echo json_encode(["status" => $usuario]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e]);
}