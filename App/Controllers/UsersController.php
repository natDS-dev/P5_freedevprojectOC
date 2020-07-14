<?php
namespace App\Controllers;
use App\Models\UsersModel;

class UsersController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->model = new UsersModel($this->db);
  }
 
  public function index()
  {
    $data = [
      'title' => 'Profil',
      'user' => $_SESSION["user"],
      'badges' => $this->model->findBadgesStats($_SESSION["user"]["id"])
    ];
    $this->view->render("users/index", $data);
  }

  public function logout()
  {
    unset($_SESSION["user"]);
    $this->addLog("See choux soon, tu as bien été déconnecté(e) !", "alert-success");
    header("Location: index.php?controller=home&action=login");
  }

  public function updateUser(){
    if(!empty($_POST)){
      $fields=[
        'name',
        'surname',
        'address',
        'zip_code',
        'city',
        'phone',
        'email',
        'profile',
      ];
      $fullAddressChanged = false;
      //On vérifie si tous les champs sont remplis
      foreach($fields as $field){
        if(!isset($_POST[$field]) || empty($_POST[$field])){
          $this->addLog('Oh oh un ou plusieurs champs sont incorrects ','alert-danger');
          header("Location: index.php?controller=users&action=index");
          exit;
        }
        $fieldValue = strip_tags($_POST[$field]);
        if(in_array($field,['address','zip_code','city'],true) && $_SESSION['user'][$field] !== $fieldValue){
          $fullAddressChanged = true;
        }else{
          if($_SESSION['user'][$field] !== $fieldValue){
            $_SESSION['user'][$field] = $fieldValue;
          } 
        }
      }
    }else{
      $this->addLog('Oh oh un ou plusieurs champs sont incorrects ','alert-danger');
      header("Location: index.php?controller=users&action=index");
      exit;
    }
    if($_SESSION['user']['role'] == 1){
      $_SESSION['user']['company'] = "";
    }else{
      if(isset($_POST['company'])  && !empty($_POST['company'])){
        $_SESSION['user']['company'] = strip_tags($_POST['company']);
      }else{
        $this->addLog('Oh oh le nom de l\'entreprise est nécessaire ','alert-danger');
        header("Location: index.php?controller=users&action=index");
        exit;
      }
    }
    if($fullAddressChanged === true ){
      $address = strip_tags($_POST['address']);
      $zipCode = strip_tags($_POST['zip_code']);
      $city = strip_tags($_POST['city']);
      $searchAddress = $address." ".$zipCode." ".$city;
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
        $this->addLog("Oups, adresse incorrecte ! seule cette information n'a pas pu être enregistrée", "alert-warning");
        $lat = $_SESSION['user']['lat'];
        $lng = $_SESSION['user']['lng'];
      } else {
        $lat = (float)json_decode($myURL)[0]->lat;
        $lng = (float)json_decode($myURL)[0]->lon;
        $_SESSION['user']['lat'] = $lat;
        $_SESSION['user']['lng'] = $lng; 
        $_SESSION['user']['address'] = strip_tags($_POST['address']);
        $_SESSION['user']['city'] = strip_tags($_POST['city']); 
        $_SESSION['user']['zip_code'] = strip_tags($_POST['zip_code']);
      }  
    }else{
      $lat = $_SESSION['user']['lat'];
      $lng = $_SESSION['user']['lng'];
    } 
    if(isset($_POST['password'])  && !empty($_POST['password'])){
      $_SESSION['user']['password'] = password_hash(strip_tags($_POST['password']), PASSWORD_DEFAULT);     
    }
    //mise à jour utilisateur  
    $this->model->updateUser($_SESSION['user']['id'], $_SESSION['user']['name'], $_SESSION['user']['surname'], $_SESSION['user']['company'], $_SESSION['user']['address'], $_SESSION['user']['zip_code'], $_SESSION['user']['city'],	$_SESSION['user']['phone'],	$_SESSION['user']['email'],	$_SESSION['user']['password'], $_SESSION['user']['profile'], $lat, $lng);
    $this->addLog("Super ton profil est à jour", "alert-success");
    header("Location: index.php?controller=users&action=index");
  }
}