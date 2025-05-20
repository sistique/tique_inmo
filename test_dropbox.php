<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$con = new conexion();
$link = conexion::$link;

/*$nombre_doc = 'hola.pdf';
$name = 'archivos/doc_documento/9.858556784382.pdf';

$guarda = (new _dropbox(link: $link))->upload(archivo_drop: $nombre_doc, archivo_local: $name);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}

$nombre_local = 'descarga.pdf';
$guarda = (new _dropbox(link: $link))->download(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHZQ',archivo_local: $nombre_local);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}*/

$guarda = (new _dropbox(link: $link))->preview(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHZQ');
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;


/*
$accessToken = 'sl.u.AFsbTTNu7oUGKcUZI52zL7NXr2GUM3Z_ejPOf5KpsB2gpxU63No07zXA0W74977lf4PAURcbnJTIoPt2cJvvZ-x8Shd0l__A49mUTS6eAU50Zaflbqy9-W6-8d0OgU559nR_sJ48Dz3XQbHZlPs_O5H7Subgvl2KFFAUJwTFunKtGOJQGbnydQFqBiEhjB9WfkCqye6RNWqyvHn2ORK-IHvLJF_N0lNzWOLMzMltSbby6pbCd7sD7d02SzYR16IB3aYHYHQjie8kqaupFFVrdr4WEvMyXqc8hyyiogT4x7E15pLB50UFkibHRMk8OC9xnHq73s5VBK-R8JpznWSKEpQ7dNObThKEgKm8xmeED1nrXbAq_mvy7CJFHEZGBx52fMn4JJ79yco55NY1-VCzTYHnLkbxmRzZl86MSEBOBsbdCX559QK2v8D92Y7JrEacOQSGbHAoqloGRSnfN7paj1Tw4Lv-AG1Kw8KU-bug_3Anpt2UP6T7x0_jPVPdK3oJOBk_Y0PK5KrzYlDlLgwLKcYb2Zh9Bd9Cp-gK3rtGLKOiMgWGza3Kwp1xYWjvf9bg-u16t9FyXNSliuvkS5OyiJZj3czvB-g3dX6QzemIC0--WtxuO58npAwbRlpb7MAMTRqFRrrclbE6YjiTv0A009opl0UgIc-HqXEasj-9U1M2kEaOVZBqztj90F9OAoP8yO9-iwJpcx8Q0bkYqbV_igKB-gvTzRQuZTJLPtdc3W6u1soxi4DxogapOu7ulVIr7sT6tHo8tkebaOdfoTUsRnVLEZNabrgaby-6SqrmKHb-MIp0N-sFxzf4m8WQMcoDJEDi2U5rk6POcb8QtkL3OVC8Cl4YfDOryAQVaVW5kDGX892AQiVWBToKy1W7eQwxxAg7qpO4QPNh_3YUeuTT_9sHXnor04pPaR0O-U9did5pdIibQNnKSScd_kxI1e4RjMIDhomnoLm01oL8Udk4IUVllohiTVWsyuOU214Nj13iy7ts79do-RK4t7xi5UNvE0bEWXapISFd-rBOmXZuRffgxIGa78ng28DO08n4cLxfcmDnqQrJbOPDA_OSIc6K-so7MZg8oHyHR6wcPd7fVOyfVhce3giAWBpK9QppZBEi_URCs8acybhT7FTzwC9Aa4T9nSoS0-AKc4X8Tb6sq2vNMusocPFgo2yyQKDFj7tbPIBWlAQf8QYG_bW3svsA97MTryBIlqxOOKI3PX1ZfxLL34F7uC7hMhEpf1XMVW2vx5cRNgNVzJEZ60TPX80hX-CqlIeNDBCk4RuBC39vIP83QvwE__tYxbYOXz4Bm3lnucsmp6Yp7UlW0jffOzWUleY';
$dropboxPath = '/Tique/hola.pdf'; // Ruta dentro de Dropbox
$localFilePath = '/home/mauriciohdz/Descargas/E902-00401980_250519_112501.pdf'; // Ruta en tu sistema

if (!file_exists($localFilePath)) {
    die("❌ Archivo no encontrado: $localFilePath\n");
}

$fileContent = file_get_contents($localFilePath);

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/octet-stream',
    'Dropbox-API-Arg: ' . json_encode([
        'path' => $dropboxPath,
        'mode' => 'add',
        'autorename' => true
    ], JSON_UNESCAPED_SLASHES)
];

$ch = curl_init('https://content.dropboxapi.com/2/files/upload');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "❌ Error cURL: " . curl_error($ch);
} else {
    echo "✅ Código HTTP: $httpCode\n";
    echo "📥 Respuesta: $response\n";
}

curl_close($ch);*/
