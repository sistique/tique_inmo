<?php
session_start();
$_SESSION['calendario']['code'] = $_GET['code'];
$redirect_uri = $_SESSION['calendario']['link_proceso'];
header("Location: $redirect_uri");
exit();
