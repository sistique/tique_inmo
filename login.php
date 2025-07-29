<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require "init.php";
require 'vendor/autoload.php';

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_empleado;

$con = new conexion();
$link = conexion::$link;

$usuario = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $modelo_empleado = new inm_empleado(link: $link);
    $r_usuario = $modelo_empleado->valida_usuario(password: $password, usuario: $usuario);
    if (errores::$error) {
        http_response_code(401);
        echo json_encode(["success" => false, "error" => $r_usuario]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "inm_empleado_id" => $r_usuario['inm_empleado_id']
    ]);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
    exit;
}