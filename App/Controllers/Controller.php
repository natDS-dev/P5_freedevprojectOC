<?php
namespace App\Controllers;
use App\Views\View;

class Controller
{
  protected $view;
  public function __construct()
  {
    $this->view = new View();
  }
}