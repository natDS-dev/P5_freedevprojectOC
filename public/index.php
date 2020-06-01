<?php
require_once '../vendor/autoload.php';

$controller = $_GET["controller"];
$action = $_GET["action"];
$id = isset($_GET["id"]) ? (int)$_GET["id"]:0;
$controller = "App\\Controllers\\Front\\".ucfirst($controller)."Controller";
(new $controller())->$action($id);