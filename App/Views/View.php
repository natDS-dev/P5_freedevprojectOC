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
        } 
        echo $this->twigEnvironment->render($template . ".html.twig", $data);
        unset($_SESSION["logs"]); 
    }

    public function addLog($message, $alertBootstrap)
    {
        if (!isset($_SESSION["logs"])) 
        {
            $_SESSION["logs"] = [];
        }
        
        $_SESSION["logs"][] = [
            "message" => $message,
            "alertBootstrap" => $alertBootstrap
        ]; 
    }
}