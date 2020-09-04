<?php
namespace App\Controllers;
use App\Tools\Database;
use App\Views\View;

class Controller
{
  protected $db; 
  protected $model;
  protected $view;
  
  //instanciation objet Database (pour connexion à la db) et View pour les messages d'alerte - Initialize database & view object 
  public function __construct()
  {
    $this->db = new Database();
    $this->view = new View();
  }

  //Fonction générale pour message d'alerte - Main function for alert message
  public function addLog($message,$alertBootstrap)
  {
    $this->view->addLog($message,$alertBootstrap);
  }

  //Fonction générale envoi de mails - Main function for sending mails 
  public function sendMail($to,$subject,$message){
    $devMail = "no-reply@3flans6choux.desmet-webdev.fr";
    mail($to, $subject, $message ,"From:" . $devMail);
  }

}