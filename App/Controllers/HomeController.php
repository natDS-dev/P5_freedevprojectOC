<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\AddsModel;

//Héritage de class, class fille hérite des méthodes de class Controller mère/parent - Child class extends parent class 
class HomeController extends Controller
{
  //Fonction pour titre de la page + dernières annonces (pour Version 2) - title & last adds function for the future website 
  public function index()
  {
    $this->model = new AddsModel($this->db);
    $data = ['title' => 'Accueil'];
    $data["lastAdds"] = $this->model->findLastAdds(); 
    $this->view->render("home/index", $data);
  }

  // Page d'erreur - Error page
  public function error()
  {
    $data = ['title' => 'Erreur 404'];
    $this->view->render("home/error", $data);
  }

  //Vérification des champs du formulaire de contact + envoi de mail - Check fields of contact form & send automatic mail 
  public function contact()
  {
    $data = ['title' => 'Contact'];
    if (!empty($_POST)){
      $name = empty($_POST["name"])? null :strip_tags($_POST["name"]);
      $surname = empty($_POST["surname"])? null :strip_tags($_POST["surname"]);
      $email = empty($_POST["email"])? null :strip_tags($_POST["email"]);
      $subject = empty($_POST["subject"])? null :strip_tags($_POST["subject"]);
      $message = empty($_POST["message"])? null :strip_tags($_POST["message"]);

      if(in_array(null,[$name,$surname,$email,$subject,$message])){
        $this->addLog("Tous les champs sont obligatoires","alert-warning");
      }else{
        $to="natachadesmet@yahoo.fr";        
        $message="Vous avez reçu un message de ".$name. " " . $surname. ": ".$message;
        $this->sendMail($to,$subject,$message);
        $this->addLog("Ton message a bien été envoyé","alert-success");
      }
    }
    $this->view->render("home/contact", $data);
  }

  public function infos()
  {
    $data = ['title' => 'Infos'];
    $this->view->render("home/infos", $data);
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
  
  //Fonction enregistrement nouvel utilisateur - New user register function
  public function register()
  {
    if (isset($_SESSION["user"]))
    {
      header("Location: index.php?controller=users&action=index");
      exit;
    }
    $this->model = new UsersModel($this->db);
    $data = ['title' => 'Inscription'];
    //Si le formulaire a été envoyé  - If the form have been sent
    if (!empty($_POST)){
      //condition ? si vraie : si fausse - condition ? if true : if false
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
      //verifier qu'aucun champs n'est nul - Checking that any fields are null
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
          $recoveryToken = "";      
          $res = $this->model->createNewUser($name, $surname, $company, $address, $zipcode,	$city,	$phone,	$email,	$password, $role, $profile, $lat, $lng,$recoveryToken);
          if($res) 
          { 
            //redirection accueil + message session - Redirect to home + session message
            $this->addLog("Inscription ok, connecte-toi ", "alert-success");
            $this->sendMail($email,"Inscription sur 3Flans, 6Choux","Cher ${name}, ton inscription est bien prise en compte");
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
    //Si connecté => home - If connected redirect home
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
        if($user["banned"]){
          $this->addLog("Ton compte est suspendu, contacte-nous !", "alert-danger");
          header("Location: index.php?controller=home&action=login");
          exit;
        }
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

  //Fonction mot de passe oublié (token) - Forget password function (token)
  public function forgetPassword(){
   if(!empty($_POST["email"])){
     $token = uniqid();
     $email = strip_tags($_POST["email"]);
     $this->model = new UsersModel($this->db);
     $res = $this->model->setToken($email, $token);
     if($res){
        //envoi mail auto - Send automatic mail  
        $to = $email;
        $subject = "Réinitialisation de ton mot de passe" ;
        $url ="https://" . $_SERVER["HTTP_HOST"] . "/index.php?controller=home&action=updatePassword&param=" . $token;
        $message = 'Clique sur le lien suivant : <a href="'.$url.'">'.$url.'</a>';
        $this->sendMail($to,$subject,$message);
        $this->addLog("Un email t'a été envoyé", "alert-success");
     }else{
       $this->addLog("Cette adresse email n'existe pas", "alert-danger");
     }
   } 
    $this->view->render("home/forgetpassword",["title"=>"Mot de passe oublié"]); 
  }

  // Mise à jour du mot de passe - Update password
  public function updatePassword() {
    $data =  ["title"=>"Mot de passe oublié"];
    $this->model = new UsersModel($this->db);
    //Token ipératif pour réinitialisation du mot de passe

    if(!isset($_GET["param"])){
      $this->addLog("Une erreur de token est survenue", "alert-danger");
    }else{
      $token = strip_tags($_GET["param"]);
      $data["token"] = $token;
    }

    if(!empty($_POST)){
      $error = false;
      $email = isset($_POST["email"])?strip_tags($_POST["email"]):null;
      $password = isset($_POST["password"])?strip_tags($_POST["password"]):null;
      $password2 = isset($_POST["password2"])?strip_tags($_POST["password2"]):null;

      if(in_array(null,[$email,$password,$password2],true)){
        $this->addLog("Tous les champs sont obligatoires", "alert-danger");
        $error = true;
      }else{
        if($password !== $password2){
          $this->addLog("Les mots de passe sont différents", "alert-warning");
          $error = true;
        }
      }

      if(!is_null($email)){
        $token2 = $this->model->getToken($email);
        if($token2 !== $_POST["token"]){
          $this->addLog("Problème technique oups", "alert-warning");
          $error = true;
        }
      }
      if(!$error){
        $newPassword = password_hash($password,PASSWORD_DEFAULT);
        $res = $this->model->updatePassword($email,$newPassword);        
        if($res){
          $this->addLog("Super, ton mot de passe est bien mis à jour ", "alert-success");
          header('Location: index.php?controller=home&action=login');
          exit;
        }else{
          $this->addLog("Il y a eu un problème", "alert-warning");
        }
      }
    }
    $this->view->render("home/updatepassword",$data);
  }
}