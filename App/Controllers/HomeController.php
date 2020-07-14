<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\AddsModel;

class HomeController extends Controller
{
  public function index()
  {
    $this->model = new AddsModel($this->db);
    $data = ['title' => 'Accueil'];
    $data["lastAdds"] = $this->model->findLastAdds(); 
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
  
  public function register()
  {
    if (isset($_SESSION["user"]))
    {
      header("Location: index.php?controller=users&action=index");
      exit;
    }
    $this->model = new UsersModel($this->db);
    $data = ['title' => 'Inscription'];
    //SI LE FORM A ETE ENVOYE
    if (!empty($_POST)){
      //condition ? si vraie : si fausse
      if(!isset($_POST["rgpd"]) || $_POST["rgpd"] !== "accepted"){
        $this->addLog("Tu dois accepter les conditions stp", "alert-warning");
        $rgpd = null;
      }else{
        $rgpd=strip_tags($_POST["rgpd"]);
      }
      $role = isset($_POST["role"]) ? (int)strip_tags($_POST["role"]) : null;
      if ($role === 1) {
        $surname = isset($_POST["surname"]) ? strip_tags($_POST["surname"]) : null;
        $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : null;
        $company = "";
      } elseif($role === 2) {
        $surname = isset($_POST["surname"]) ? strip_tags($_POST["surname"]) : null;
        $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : null;
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
      || is_null($email) || is_null($password) || is_null($role) || is_null($profile)|| is_null($rgpd)) {
        $this->addLog("Oups, il y a un problème !", "alert-danger");
      } else {
        $searchAddress = $address." ".$zipcode." ".$city;
        $referer = "https://nominatim.openstreetmap.org/search?q=${searchAddress}&format=json&polygon=1&addressdetails=1";      
        $opts = [
          'http'=>[
            'header'=>[
              "Referer: $referer\r\n"
            ]
          ]
        ];
        $context = stream_context_create($opts);
        $myURL = file_get_contents($referer, false, $context); 
        if(empty(json_decode($myURL))){
          $this->addlog("Il y a un problème d'adresse", "alert-warning");
          $data["newUser"] =[
            "name"=>$name,
            "surname"=>$surname,
            "company"=>$company,
            "address"=>$address,
            "zip_code"=>$zipcode,
            "city"=>$city,
            "phone"=>$phone,
            "email"=>$email,
            "role"=>$role,            
            "profile"=>$profile
          ];
        } else {
          $lat = (float)json_decode($myURL)[0]->lat;
          $lng = (float)json_decode($myURL)[0]->lon;
          $password = password_hash($password, PASSWORD_DEFAULT);      
          $res = $this->model->createNewUser($name, $surname, $company, $address, $zipcode,	$city,	$phone,	$email,	$password, $role, $profile, $lat, $lng);
          if($res) 
          { 
            //redirection accueil + message session
            $this->addLog("Inscription ok, connecte-toi ", "alert-success");
            header("Location: index.php?controller=home&action=login");
            exit;
          } else {
            $this->addlog("Champs invalides ou/et adresse mail déjà connue", "alert-danger");
          }
        }
      } 
    }   
    
    $this->view->render("home/register", $data);
  }

  public function login()
  {
    $this->model = new UsersModel($this->db);
    //Si connecté => home
    if (isset($_SESSION["user"]))
    {
      header("Location: index.php?controller=users&action=index");
      exit;
    }
    $data = ['title' => 'Connexion'];
    if (!empty($_POST['login']) && !empty($_POST['password']))
    {
      $passwordHash = $this->model->findPasswordByUserEmail(strip_tags($_POST["login"]));
      if (!is_null($passwordHash) && password_verify(strip_tags($_POST["password"]), $passwordHash))
      {
        $user = $this->model->findUserByUserEmail(strip_tags($_POST["login"]));
        $_SESSION["user"] = $user;
        $this->addLog("Choux-bidouwouah ! tu es bien connecté(e) ", "alert-success");
        if($user["role"] === 0){
          header("Location: index.php?controller=back&action=index");
          exit;
        }else{
          header("Location: index.php?controller=users&action=index");
          exit;
        }
      }else{
        $this->addLog("Dans les choux ! petit problème d'identification", "alert-danger");
      }
    }
    $this->view->render("home/login", $data);
  }
}