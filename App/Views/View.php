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
        echo $this->twigEnvironment->render($template . ".html.twig", $data);

    }
}