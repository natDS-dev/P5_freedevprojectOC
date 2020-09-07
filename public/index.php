<?php
session_start();
require_once '../vendor/autoload.php';
use App\Controllers\HomeController;

//Format d'url : index.php?controller=x&action=y&param=z  
$controllerName = isset($_GET["controller"]) ? $_GET["controller"] : "";
if(!empty($controllerName))
{  
    if($controllerName === "back"){
        $controller = "App\\Controllers\\BackDev\\BackController";
     
    }else{ 
        $controller = "App\\Controllers\\".ucfirst($controllerName)."Controller";
    }
}else{ 
    //Page par défaut
    (new HomeController())->index();
    exit;
}

if(class_exists($controller))
{   
    //Si non connecté accès à "home" seulement
    if($controllerName !== "home" && !isset($_SESSION["user"]))
    {
        (new $controller())->addLog("Oh oh... Tu dois être connecté","alert-danger");
        header("Location: index.php?controller=home&action=login");
        exit;
    }
    //Accès dev
    //Si l'user est connecté mais n'est pas dév
    if($controllerName === "back" && $_SESSION["user"]["role"] !== 0){
        (new $controller())->addLog("Tu ne peux pas accéder à cette page","alert-danger");
        header("Location: index.php?controller=home&action=index");
        exit;
    }

    if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] === 0){
        $action = isset($_GET["action"])? $_GET["action"] : "";
        
        //Si connecté en dev accès à dev et logout       
        if($controllerName !== "back" && $action !== "logout") {
            (new $controller())->addLog("Redirection vers le tableau de bord développeur","alert-warning");
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

  