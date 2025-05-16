<?php

namespace gamboamartin\inmuebles\models;

use base\orm\modelo;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\notificaciones\models\not_mensaje;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\notificaciones\models\not_rel_mensaje;
use PDO;
use stdClass;

class _dropbox
{
    public const string UPLOAD = "https://content.dropboxapi.com/2/files/upload";
    public const string DOWNLOAD = "https://content.dropboxapi.com/2/files/download";


    public function upload(string $archivo_drop, string $archivo_local, string $mode = 'add', bool $autorename = false): bool|string
    {
        $ruta_base = (new generales())->ruta_base_dropbox;
        $path_base = (new generales())->path_base;
        $token = (new generales())->token;

        $arguments = [
            'path' => $ruta_base.$archivo_drop,
            'mode' => $mode,
            'autorename' => $autorename,
        ];

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode(
                $arguments
            )
        ];

        $file = fopen($path_base.$archivo_local, 'rb');
        $fileSize = filesize($path_base.$archivo_local);

        $ch = curl_init(self::UPLOAD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_INFILE, $file);
        curl_setopt($ch, CURLOPT_INFILESIZE, $fileSize);
        curl_setopt($ch, CURLOPT_UPLOAD, true);

        return curl_exec($ch);
    }

    public function download(string $dropbox_id): bool|string
    {
        $token = (new generales())->token;

        $arguments = [
            'path' => $dropbox_id,
        ];

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode(
                $arguments
            )
        ];

        $ch = curl_init(self::DOWNLOAD);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

}