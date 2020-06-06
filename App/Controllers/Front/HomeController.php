<?php
namespace App\Controllers\Front;
use App\Controllers\Controller;

class HomeController extends Controller
{
  public function index()
  {
    $data = ['title' => 'Accueil'];
    $data["lastoffers"] = [
      [
        "id"=>1,
        "title"=>"tonte",
        "description"=> "tonte de ma pelouse"
      ],
      [
        "id"=>2,
        "title"=>"papier peint",
        "description"=> "pose de papier"
      ]
    ];
    
    $this->view->render("home/index", $data);
  }

  public function error()
  {
    $data = ['title' => 'Erreur 404'];
    $this->view->render("home/error", $data);
  }

  public function contact()
  {
    $data = ['title' => 'Contact'];
    $this->view->render("home/contact", $data);
  }

  public function who()
  {
    $data = ['title' => 'Qui ?'];
    $this->view->render("home/who", $data);
  }

  public function mentions()
  {
    $data = ['title' => 'Mentions légales'];
    $this->view->render("home/mentions", $data);
  }

}