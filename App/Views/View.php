<?php
namespace App\Views;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;


class View
{
    private $twigLoader;
    private $twigEnvironment;  
    public function __construct()
    {
        $this->twigLoader = new FilesystemLoader(__DIR__.'/Templates');
        $this->twigEnvironment = new Environment($this->twigLoader, []); 
    }

    public function render($template = null, $data = null)
    {
        if (isset($_SESSION["logs"]))
        {
            $data["logs"] = $_SESSION["logs"];
            unset($_SESSION["logs"]); 
        } 
        if (isset($_SESSION["user"]))
        {
            $data["user"] = $_SESSION["user"];
        }
        
        echo $this->twigEnvironment->render($template . ".html.twig", $data);
    }

    public function addLog($message, $alertBootstrap)
    {
        if (!isset($_SESSION["logs"])) 
        {
            $_SESSION["logs"] = [];
        }
        $_SESSION["logs"][] = [
            "message" => $message,
            "name" => (isset($_SESSION["user"]) ? $_SESSION["user"]["name"] : null),
            "alertBootstrap" => $alertBootstrap
        ]; 
    }
}