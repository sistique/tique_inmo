<?
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Responder OPTIONS para preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_checada;

$con = new conexion();
$link = conexion::$link;

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Datos no validos"]);
    exit;
}

try {
    $modelo_checada = new inm_checada(link: $link);

    $r_alta_bd = $modelo_checada->alta_registro(registro: $data);
    if (errores::$error) {
        http_response_code(500);
        echo json_encode(["error" => $r_alta_bd]);
        exit;
    }
    echo json_encode(["status" => $r_alta_bd]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}