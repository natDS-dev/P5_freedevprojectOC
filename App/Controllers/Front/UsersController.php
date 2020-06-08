<?php
namespace App\Controllers\Front;
use App\Controllers\Controller;
use App\Models\UsersModel;

class UsersController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->model = new UsersModel($this->db);
  }
  public function login()
  {
    //Si connecté => home
    if (isset($_SESSION["user"]))
    {
      header("Location: index.php?controller=home&action=index");
    }
    $data = ['title' => 'Connexion'];
    if (!empty($_POST['login']) && !empty($_POST['password']))
    {
      $passwordHash = $this->model->findPasswordByUserEmail(strip_tags($_POST["login"]));
      if (!is_null($passwordHash) && password_verify(strip_tags($_POST["password"]), $passwordHash))
      {
        $this->view->addLog("Identification correcte !", "alert-success");
        $user = $this->model->findUserByUserEmail(strip_tags($_POST["login"]));
        $_SESSION["user"] = $user;
        header("Location: index.php?controller=home&action=index");
        exit;
      }else{
        $this->view->addLog("Oups, il y a un problème d'identification !", "alert-danger");
      }
    }
    $this->view->render("users/login", $data);
  }

  public function logout()
  {
    unset($_SESSION["user"]);
    $this->view->addLog("Vous avez bien été déconnecté !", "alert-success");
    header("Location: index.php?controller=users&action=login");
  }

  public function register()
  {
    $data = ['title' => 'Inscription'];
    //SI LE FORM A ETE ENVOYE
    if (!empty($_POST)){
      //condition ? si vraie : si fausse
      $role = isset($_POST["role"]) ? (int)strip_tags($_POST["role"]) : null;
      if ($role === 1) {
        $surname = isset($_POST["surname"]) ? strip_tags($_POST["surname"]) : null;
        $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : null;
        $company = "";
      } elseif($role === 2) {
        $surname = "";
        $name = "";
        $company = isset($_POST["company"]) ? strip_tags($_POST["company"]) : null;
      } else {
        $role = null;
        $name = null;
        $surname = null;
        $company = null;
      }
      $address = isset($_POST["address"]) ? strip_tags($_POST["address"]) : null;
      $zipcode = isset($_POST["zip_code"]) ? strip_tags($_POST["zip_code"]) : null;
      $city = isset($_POST["city"]) ? strip_tags($_POST["city"]) : null;
      $phone = isset($_POST["phone"]) ? strip_tags($_POST["phone"]) : null;
      $email = isset($_POST["email"]) ? strip_tags($_POST["email"]) : null;
      $password = isset($_POST["password"]) ? strip_tags($_POST["password"]) : null;
      $profile = isset($_POST["profile"]) ? strip_tags($_POST["profile"]) : null;
      //verifier qu'aucun champs n'est nul
      if (is_null($name) || is_null($surname) || is_null($company) || is_null($role)
      || is_null($address) || is_null($zipcode) || is_null($city) || is_null($phone)
      || is_null($email) || is_null($password) || is_null($role) || is_null($profile)){
        $this->view->addLog("Oups, il y a un problème !", "alert-danger");
      } else {
        $password = password_hash($password, PASSWORD_DEFAULT);      
        $this->model->createNewUser($name, $surname, $company, $address, $zipcode,	$city,	$phone,	$email,	$password, $role, $profile);
        //redirection accueil + message session
        $this->view->addLog("Inscription ok, connectez-vous !", "alert-success");
        header("Location: index.php?controller=users&action=login");
        exit;
      } 
    }   
    
    $this->view->render("users/register", $data);
  }
}
//name	surname	company	address	zip_code	city	phone	email	password	role	profile