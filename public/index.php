<?php
session_start();
require_once '../vendor/autoload.php';
use App\Controllers\HomeController;

$controllerName = isset($_GET["controller"]) ? $_GET["controller"] : "";
if(!empty($controllerName))
{
    if($controllerName === "back"){
        $controllerName = "BackAdmin\\".ucfirst($controllerName);
    }
    $controller = "App\\Controllers\\".$controllerName."Controller";
}

if(class_exists($controller))
{
    //Si non connecté accès à "home" seulement
    if($controllerName !== "home" && !isset($_SESSION["user"]))
    {
        (new $controller())->addLog("Vous devez être connecté","alert-danger");
        header("Location: index.php?controller=home&action=login");
        exit;
    }
    //Accès admin
    if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] === 0){
        $action = isset($_GET["action"])? $_GET["action"] : "";
        
        //Si connecté en admin accès à admin et logout       
        if($controllerName !== "BackAdmin\\Back" && $action !== "logout") {
            (new $controller())->addLog("Redirection vers l'admin","alert-warning");
            header("Location: index.php?controller=back&action=index");
            exit;   
        }else{          
            if(method_exists($controller,$action))
            {
                $param = isset($_GET["param"]) ? (int)$_GET["param"]:0;
                (new $controller())->$action($param);
                exit;
            } 
        }
    }
    $action = isset($_GET["action"])? $_GET["action"] : "";
    if(method_exists($controller,$action))
    {
        $param = isset($_GET["param"]) ? (int)$_GET["param"]:0;
        (new $controller())->$action($param);
        exit;
    }
}else{
    //appelé si controlleur et/ou action non défini/incorrect
    (new HomeController())->error(); 
    exit;
}  
//Page par défaut
(new HomeController())->index();
  