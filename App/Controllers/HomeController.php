<?php
namespace App\Controllers;

class HomeController extends Controller
{
  public function index()
  {
    $data = ['title' => 'Titre test'];
    $this->view->render("home/index", $data);
  }

  public function error()
  {
    $data = ['title' => 'error 404'];
    $this->view->render("home/error", $data);
  }
}