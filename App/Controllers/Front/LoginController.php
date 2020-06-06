<?php
namespace App\Controllers\Front;
use App\Controllers\Controller;

class LoginController extends Controller
{

    public function login()
  {
    $data = ['title' => 'Connexion'];
    $this->view->render("login/index", $data);
  }

  public function register()
  {
    $data = ['title' => 'Inscription'];
    $this->view->render("login/register", $data);
  }
}