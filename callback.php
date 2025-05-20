<?php
if (!isset($_GET['code'])) {
    echo "Error: No se recibió ningún código.";
    exit;
}

$authorization_code = $_GET['code'];

echo "El código de autorización es: $authorization_code";
// Aquí puedes continuar con el intercambio por el token

