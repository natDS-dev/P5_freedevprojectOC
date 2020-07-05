<?php
namespace App\Controllers;
use App\Models\AddsModel;

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
    if((int)$_SESSION['user']['role'] !== 1)
    {
      echo json_encode(["status" => "error"]);
    }else{
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
  }

  public function index()
  {
    $data = ['title' => 'Offres'];
    $lastAdds = $this->model->findLastAdds();
    $data['lastAdds'] = $lastAdds;
    $data['availableBaskets'] = $this->model->findAvailableBaskets();
    $this->view->render('adds/index', $data);
  }

  //Create => or edit add if id is set and correct
  public function create($id=0)
  { 
    if((int)$_SESSION['user']['role'] !== 1)
    {
      $this->addLog("Oh oh, petit problème", "alert-warning");
      header("Location: index.php?controller=home&action=index");
      exit;
    }
    //variable data contenant le titre de la page 
    $data = ['title' => 'Créer une annonce'];
    //Si id passé dans l'url et différent de 0 =>vérification  
    if($id !== 0)
    {
      //Recherche de l'annonce correspondant à l'id
      $data['add']=$this->model->findAdd((int)$id);
      //Vérification => si l'id ne correspond pas à l'id de l'auteur rattaché à l'annonce    
      if((int)$_SESSION['user']['id'] !== (int)$data['add']['creator_id'])
      {
        $this->addLog("Oh oh, petit problème", "alert-warning");
        unset($data['add']);
      }
    }
    //Si les champs de création d'annonce ne sont pas vides
    if(!empty($_POST)){
      $title = !empty($_POST["title"]) ? strip_tags($_POST["title"]) : null;
      $description = !empty($_POST["description"]) ? strip_tags($_POST["description"]) : null;
      $basket_size = !empty($_POST["basket_size"]) ? (int)strip_tags($_POST["basket_size"]) : null;
      $basket_quantity = !empty($_POST["basket_quantity"]) ? (int)strip_tags($_POST["basket_quantity"]) : null;
      
      //Si 1 (ou plusieurs) champs est nul 
      if(is_null($title) || is_null($description) || is_null($basket_size) || is_null($basket_quantity))
      {//=> renvoit d'un message d'erreur 
        $this->addLog("Oups tous les champs sont obligatoires","alert-danger");
        //Clé supplémentaire au tableau data pour pré-remplissage auto des champs
        $data['add']= [
          "title" => $_POST["title"],
          "description" => $_POST["description"],
          "basket_size" => $_POST["basket_size"],
          "basket_quantity" => $_POST["basket_quantity"]
        ]; 
      }else{//si tout les champs ok
        $creator_id = (int)$_SESSION["user"]["id"];
        if (!empty($_POST["id"]))
        {//si un id il y avait, alors s'agit d'une modification =>message de confirmation modifcation addlog
          $id=(int)($_POST["id"]);
          $this->addLog("Top ! l'annonce est bien modifiée","alert-success");
          $this->model->editAdd($id,$title,$description,$creator_id,$basket_size,$basket_quantity);
        } else {//sinon (si aucun id) il s'agit d'une création => message de confirmation création addlog
          $this->addLog("Super! l'annonce est bien créée","alert-success");
          $this->model->createAdd($title,$description,$creator_id,$basket_size,$basket_quantity);
        }
      }  
    } 
    // On rend la vue correspondante  
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

    $this->view->render('adds/myadds', $data);   
  }


  public function updateAdd(){
    


  } 

  public function deleteAdd($id) {
    $res=$this->model->deleteAdd($id);
    if($res){
      $this->addLog("Parfait l'annonce a été supprimée", "alert-success");
    }else{
      $this->addLog("Ohla, dans les choux ! l'annonce n'a pas été supprimée", "alert-danger");
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

    if(is_null($addId) || is_null($userId) || is_null($basketId)){
      $this->addLog("Ouhlala il y a un souci", "alert-danger");
      header("Location: index.php?controller=adds&action=index");
      exit; 
    }
    $this->model->confirmAdd($addId, $userId, $basketId);
    $this->model->closeAdd($addId);
    $this->addLog("Youhoou, merci pour ton engagement!", "alert-success");
    header("Location: index.php?controller=adds&action=index");
  }
}