<?php
namespace App\Controllers;
use App\Tools\Database;
use App\Views\View;

class Controller
{
  protected $db; 
  protected $model;
  protected $view;
  
  public function __construct()
  {
    $this->db = new Database();
    $this->view = new View();
  }
  public function addLog($message,$alertBootstrap)
  {
    $this->view->addLog($message,$alertBootstrap);
  }
}