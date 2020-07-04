<?php
session_start();
require_once '../vendor/autoload.php';
use App\Controllers\HomeController;

$controllerName = isset($_GET["controller"]) ? $_GET["controller"] : "";
if(!empty($controllerName))
{
    $controller = "App\\Controllers\\".ucfirst($controllerName)."Controller";
}

if(class_exists($controller))
{
    if($controllerName != "home" && !isset($_SESSION["user"]))
    {
        (new $controller())->addLog("Vous devez être connecté","alert-danger");
        header("Location: index.php?controller=home&action=login");
        exit;
    }
    $action = isset($_GET["action"])? $_GET["action"] : "";
    if(method_exists($controller,$action))
    {
        $id = isset($_GET["id"]) ? (int)$_GET["id"]:0;
        (new $controller())->$action($id);
        exit;
    }
}else{
    //appelé si controlleur et/ou action non défini/incorrect
    (new HomeController())->error(); 
    exit;
}  
//Page par défaut
(new HomeController())->index();
  