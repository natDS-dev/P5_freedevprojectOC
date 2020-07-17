<?php
namespace App\Controllers;
use App\Models\AddsModel;
use App\Models\UsersModel;
use App\Models\BasketsModel;

class AddsController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->model = new AddsModel($this->db);
  }

  public function getJSON()
  {
    header('Content-type: application/json');
    $data=$this->model->findMapAdds();
    $mapAdds = [];
    foreach($data as $add){
      $users_id = (int)$add["users_id"];
      if(!isset($mapAdds[$users_id]))
      {
      $mapAdds[$users_id] = [];          
      }
      if ($add["basket_size"] === 1) {
        $add["basket_size"] = "S";
      }
      else if ($add["basket_size"] === 2) {
          $add["basket_size"] = "M";
      } else {
          $add["basket_size"] = "L";
      }
      $mapAdds[$users_id][] = $add;
    }
    echo json_encode(array_values($mapAdds)); 
  }

  public function index()
  {
    $data = ['title' => 'Offres'];
    $data['availableBaskets'] = $this->model->findAvailableBaskets();
    $this->view->render('adds/index', $data);
  }

  //Create => or edit add if id is set and correct
  public function create()
  {
     //Vérification du role de l'utilisateur => Les producteurs ne peuvent pas créer d'annonce
    if((int)$_SESSION['user']['role'] !== 1)
    {
      $this->addLog("Oh oh, petit problème", "alert-warning");
      header("Location: index.php?controller=home&action=index");
      exit;
    }
    //variable data contenant le titre de la page 
    $data = ['title' => 'Créer une annonce'];
   
    //Si les champs de création d'annonce ne sont pas vides
    if(!empty($_POST)){
      $category = !empty($_POST["category"]) ? strip_tags($_POST["category"]) : null;
      $title = !empty($_POST["title"]) ? strip_tags($_POST["title"]) : "";
      $description = !empty($_POST["description"]) ? strip_tags($_POST["description"]) : null;
      $basket_size = !empty($_POST["basket_size"]) ? (int)strip_tags($_POST["basket_size"]) : null;
      $basket_quantity = !empty($_POST["basket_quantity"]) ? (int)strip_tags($_POST["basket_quantity"]) : null;
      
      //Si 1 (ou plusieurs) champs est nul 
      if(is_null($category) || is_null($description) || is_null($basket_size) || is_null($basket_quantity))
      {//=> renvoit d'un message d'erreur 
        $this->addLog("Oups tous les champs sont obligatoires","alert-danger");
        //Clé supplémentaire au tableau data pour pré-remplissage auto des champs
        $data['add']= [
          "category" => (int)$_POST["category"],
          "title" => $_POST["title"],
          "description" => $_POST["description"],
          "basket_size" => $_POST["basket_size"],
          "basket_quantity" => $_POST["basket_quantity"]
        ]; 
      }else{//si tout les champs ok
        $creator_id = (int)$_SESSION["user"]["id"];
        $this->addLog("Super! l'annonce est bien créée","alert-success");
        $this->model->createAdd($category,$title,$description,$creator_id,$basket_size,$basket_quantity);
      }  
    } 
    // On rend la vue correspondante 
    $data["categories"] = (new \App\Models\CategoriesModel(($this->db)))->findAll(1); 
    $this->view->render('adds/create', $data);    
  }

  public function myAdds($page){
    //si aucun numéro de page précisé
    if($page === 0){
      $page = 1 ;
    }
    if(isset($_GET['param']) && !empty($_GET['param'])){
      $page = (int)(strip_tags($_GET['param']));
      if($page <= 0){
        $this->addLog("Le numéro de page n'est pas correct", "alert-warning");
        header("Location: index.php?controller=adds&action=myAdds&param=1");
        exit;
      }
    }
   
    $perPage = 3;
    $userId = $_SESSION['user']['id'];
    $myAddsTotal = $this->model->userAddsTotal($userId);
    if(($page-1)*$perPage > $myAddsTotal){
      $this->addLog("Le numéro de page n'est pas correct", "alert-warning");
      header("Location: index.php?controller=adds&action=myAdds&param=1");
      exit; 
    }
    
    $data = [
      "title" => "Mes annonces",
      "page" => $page,
      "myAddsTotal" => $myAddsTotal,
      "myAddsPage" => $this->model->userAddsPage($userId,$perPage,$page),
      "previousPage" => $page - 1,
      "nextPage" => null
    ];

    if($page*$perPage < $myAddsTotal){
      $data["nextPage"] = $page + 1; 
    }
    $data["categories"] = (new \App\Models\CategoriesModel(($this->db)))->findAll(1);
    $this->view->render('adds/myadds', $data);   
  }


  public function updateAdd(){
    //Methode uniquement appelée par POST => si POST est vide = exit
    if(empty($_POST)){
      $this->addLog("Ohla, dans les choux ! l'annonce n'a pas été mise à jour", "alert-warning");
      header("Location: index.php?controller=adds&action=myAdds");
      exit;
    }
    //Récupération de l'annonce correspondant à l'id
    $id = isset($_POST["id"]) ? (int)strip_tags($_POST["id"]) : null; 
    $add = is_null($id) ? null : $this->model->findAdd($id);
    //L'annonce doit exister et nous appartenir => impossible de mettre àjour l'annonce d'une autre personne
    if(is_null($add) || $add["creator_id"] !== $_SESSION["user"]["id"] ){
      $this->addLog("Ohla, dans les choux ! l'annonce n'a pas été mise à jour", "alert-warning");
      header("Location: index.php?controller=adds&action=myAdds");
      exit;
    }
    //récupération des valeurs du formulaire de mise à jour 
    $category = isset($_POST["category"])? (int)strip_tags($_POST["category"]):null;
    $title = isset($_POST["title"])? strip_tags($_POST["title"]):"";
    $description = isset($_POST["description"])? strip_tags($_POST["description"]):null;
    $basket_size = isset($_POST["basket_size"])? (int)strip_tags($_POST["basket_size"]):null;
    $basket_quantity = isset($_POST["basket_quantity"])? (int)strip_tags($_POST["basket_quantity"]):null;

    //Les champs indispensables ne doivent pas être nul
    if(in_array(null,[$id,$category,$description,$basket_size,$basket_quantity],true)){
      $this->addLog("Ohla, dans les choux ! des champs ne sont pas valides", "alert-warning");
      header("Location: index.php?controller=adds&action=myAdds");
      exit;
    }
    //Mise à jour de l'annonce
    $this->model->editAdd($id,$category,$title,$description,$basket_size,$basket_quantity);
    $this->addLog("Parfait, l'annonce a été mise à jour", "alert-success");
    header("Location: index.php?controller=adds&action=myAdds");
  } 

  public function deleteAdd($id) {
    //Récupération de l'annonce correspondant à l'id
    $add = $this->model->findAdd($id);
    //L'annonce doit exister et nous appartenir => impossible de supprimer l'annonce d'une autre personne
    if(is_null($add) || $add["creator_id"] !== $_SESSION["user"]["id"] ){
      $this->addLog("Ohla, dans les choux ! l'annonce n'a pas été supprimée", "alert-danger");
    }else{
      //Suppression de l'annonce
      $res=$this->model->deleteAdd($id);
      if($res){
        $this->addLog("Parfait l'annonce a été supprimée", "alert-success");
      }else{
        $this->addLog("Ohla, dans les choux ! l'annonce n'a pas été supprimée", "alert-danger");
      }
    }     
    header("Location: index.php?controller=adds&action=myAdds");
  }

  public function validation(){
    
    if(empty($_POST)){
      $this->addLog("Oh oh, petit problème", "alert-danger");
      header("Location: index.php?controller=adds&action=index");
      exit;
    }

    $addId = isset($_POST["add_id"]) ? strip_tags($_POST["add_id"]) : null;
    $userId = isset($_POST["user_id"]) ? strip_tags($_POST["user_id"]) : null;
    $basketId = isset($_POST["basket_id"]) ? strip_tags($_POST["basket_id"]) : null;

    /*Pour ne pas pouvoir répondre à sa propre annonce OU qu'un professionnel (de rôle 2) ne puisse répondre*/  
    if($_SESSION["user"]["role"] !== 1 || $userId === $_SESSION["user"]["id"]){
      $this->addLog("Tu n'as pas le droit de répondre à cette annonce", "alert-danger");
      header("Location: index.php?controller=adds&action=index");
      exit;
    }
    
    if(is_null($addId) || is_null($userId) || is_null($basketId)){
      $this->addLog("Ouhlala il y a un souci", "alert-danger");
      header("Location: index.php?controller=adds&action=index");
      exit; 
    }
    $this->model->confirmAdd($addId, $userId, $basketId);
    $this->model->closeAdd($addId);
    $this->addLog("Youhoou, merci pour ton engagement!", "alert-success");
    
    /*Envoi de mail automatique aux 3 partie à la validation de mission*/
    /*Pour envoi mail :Récupération des données créateur annonce/validateur annonce/entreprise choisie pour rétribution*/
    $add = (new AddsModel($this->db))->findAdd($addId);
    $addCreator = (new UsersModel($this->db))->findUser((int)$add["creator_id"]);
    $addValidator = (new UsersModel($this->db))->findUser($userId);
    $basket = (new BasketsModel($this->db))->findbasket($basketId);
    $basketCreator = (new UsersModel($this->db))->findUser((int)$basket["company_id"]);
    
    if ($add[basket_size] === 1) {
      $add[basket_size] = "S";
    }
    else if ($add[basket_size] === 2) {
        $add[basket_size] = "M";
    } else {
        $add[basket_size] = "L";
    }
    /*Mail au créateur de l'annonce*/
    $this->sendMail($addCreator[email],"Hey ! tu as reçu un coup de pouce sur 3Flans, 6Choux !","Cher $addCreator[name], ta mission : $add[title] vient d'être acceptée par $addValidator[name] $addValidator[surname] en échange de $add[basket_quantity] panier(s) de taille $add[basket_size] que tu prendras chez $basketCreator[company]  $basketCreator[address] - $basketCreator[city] - $basketCreator[phone]. Contacte le rapidement pour vous organiser. Tu peux le joindre au $addValidator[phone] . A bientôt sur 3Flans, 6 Choux");

    /*Mail a l'utilisateur qui accepte la mission*/
    $this->sendMail($addValidator[email],"Au top ! tu viens de t'engager pour une mission sur 3Flans, 6Choux !","Cher $addValidator[name], bravo pour ton engagement, tu viens d'accepter la mission de $addCreator[name]. Tu t'engages à : $add[title], $add[description]. La contrepartie que tu as choisie est : $add[basket_quantity] panier(s) de taille $add[basket_size] chez $basketCreator[company] ( $basketCreator[address] - $basketCreator[city] - $basketCreator[phone]). Contacte rapidement $addCreator[name] pour vous organiser. Tu peux le joindre au $addCreator[phone].Nous comptons sur toi !. A bientôt sur 3Flans, 6 Choux");
    /*Mail au producteur chez qui le panier a été commandé*/
    $this->sendMail($basketCreator[email],"Un panier vient d'être validé sur 3Flans, 6Choux !","Cher $basketCreator[name], $addValidator[name] vient de choisir ton entreprise pour sa rétribution en échange du service qu'il va rendre à $addCreator[name]. Nombre de panier(s) : $add[basket_quantity] - Taille de panier : $add[basket_size]. Nous comptons sur eux et sur toi !. A bientôt sur 3Flans, 6 Choux");
    // dump($add,$addCreator,$addValidator,$basket,$basketCreator);
    header("Location: index.php?controller=adds&action=index");
  }
}