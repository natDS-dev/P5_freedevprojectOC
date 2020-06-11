<?php
session_start();
require_once '../vendor/autoload.php';
use App\Controllers\HomeController;

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
if(!empty($controller))
{
    $controller = "App\\Controllers\\".ucfirst($controller)."Controller";
}

if(class_exists($controller))
{
    $action = isset($_GET["action"])? $_GET["action"] : "";
    if(method_exists($controller,$action))
    {
        $id = isset($_GET["id"]) ? (int)$_GET["id"]:0;
        (new $controller())->$action($id);
        exit;
    }
}   
(new HomeController())->error(); 