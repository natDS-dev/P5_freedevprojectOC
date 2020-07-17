<?php
namespace App\Controllers;
use App\Models\BasketsModel;

class BasketsController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->model = new BasketsModel($this->db);
  }

  public function getJSON()
  {
    header('Content-type: application/json');
    $data=$this->model->findMapBaskets();
    $mapBaskets = [];
    foreach($data as $basket){
      $companyId = (int)$basket["company_id"];
      if(!isset($mapBaskets[$companyId]))
      {
        $mapBaskets[$companyId] = [];          
      }
      $mapBaskets[$companyId][] = $basket;
    }
    echo json_encode(array_values($mapBaskets)); 
  }
 
  public function index()
  {
    $data = ['title' => 'Paniers'];
    $this->view->render('baskets/index', $data);
  }

  //Create => or edit basket if id is set and correct
  public function create()
  { 
    //Seuls les producteurs peuvent créer un panier
    if((int)$_SESSION['user']['role'] !== 2)
    {
        $this->addLog("Oh oh, petit problème", "alert-warning");
        header("Location: index.php?controller=home&action=index");
        exit;
    }
    //variable data contenant le titre de la page 
    $data = ['title' => 'Créer un panier'];
   
    //Si les champs de création de panier ne sont pas vides
    if(!empty($_POST)){
      $title = !empty($_POST["title"]) ? strip_tags($_POST["title"]) : "";
      $category = !empty($_POST["category"]) ? (int)strip_tags($_POST["category"]) : null;
      $description = !empty($_POST["description"]) ? strip_tags($_POST["description"]) : null;
      if (!isset($_POST["available"])){
        $available = null;
      }else{
        $available = ($_POST["available"] === "1") ? 1 : 0;
      }  
        //Si 1 (ou plusieurs) champs est nul 
      if(is_null($category) || is_null($description) || is_null($available))
      {//=> renvoit d'un message d'erreur 
        $this->addLog("Oups tous les champs sont obligatoires","alert-danger");
        //Clé supplémentaire au tableau data pour pré-remplissage auto des champs
        $data['basket']= [
          "title" => $_POST["title"],
          "category" => $_POST["category"],
          "description" => $_POST["description"],
          "available" => $_POST["available"]
        ]; 
      }else {
        //si tout les champs ok => création du nouveau panier
        $company_id = (int)$_SESSION["user"]["id"];
        //sinon (si aucun id) il s'agit d'une création => message de confirmation création addlog
        $this->addLog("Super! le panier est bien créé","alert-success");
        $this->model->createBasket($title,$category,$description,$company_id,$available);
      }
    } 
    // On rend la vue correspondante  
    $data["categories"] = (new \App\Models\CategoriesModel(($this->db)))->findAll(2); 
    $this->view->render('baskets/create', $data);    
  }

  public function myBaskets($page){
    //si aucun numéro de page précisé
    if($page === 0){
      $page = 1 ;
    }
    if(isset($_GET['param']) && !empty($_GET['param'])){
      $page = (int)(strip_tags($_GET['param']));
      if($page <= 0){
        $this->addLog("Le numéro de page n'est pas correct", "alert-warning");
        header("Location: index.php?controller=baskets&action=myBaskets&param=1");
        exit;
      }
    }
   
    $perPage = 3;
    $userId = $_SESSION['user']['id'];
    $myBasketsTotal = $this->model->userBasketsTotal($userId);
    if(($page-1)*$perPage > $myBasketsTotal){
      $this->addLog("Le numéro de page n'est pas correct", "alert-warning");
      header("Location: index.php?controller=baskets&action=myBaskets&param=1");
      exit; 
    }
    
    $data = [
      "title" => "Mes paniers",
      "page" => $page,
      "myBasketsTotal" => $myBasketsTotal,
      "myBasketsPage" => $this->model->userBasketsPage($userId,$perPage,$page),
      "previousPage" => $page - 1,
      "nextPage" => null
    ];

    if($page*$perPage < $myBasketsTotal){
      $data["nextPage"] = $page + 1; 
    }
    $data["categories"] = (new \App\Models\CategoriesModel(($this->db)))->findAll(2);
    $this->view->render('baskets/mybaskets', $data);   
  }

  public function updateBasket(){
    //Methode uniquement appelée par POST => si POST est vide = exit
    if(empty($_POST)){
      $this->addLog("Ohla, dans les choux ! le panier n'a pas été mis à jour", "alert-warning");
      header("Location: index.php?controller=baskets&action=myBaskets");
      exit;
    }
    //Récupération du panier correspondant à l'id
    $id = isset($_POST["id"]) ? (int)strip_tags($_POST["id"]) : null; 
    $basket = is_null($id) ? null : $this->model->findBasket($id);
    //Le panier  doit exister et nous appartenir => impossible de mettre àjour le panier d'une autre personne
    if(is_null($basket) || $basket["company_id"] !== $_SESSION["user"]["id"] ){
      $this->addLog("Ohla, dans les choux ! le panier n'a pas été mis à jour", "alert-warning");
      header("Location: index.php?controller=baskets&action=myBaskets");
      exit;
    }
    //récupération des valeurs du formulaire de mise à jour 
    $category = isset($_POST["category"])? (int)strip_tags($_POST["category"]):null;
    $title = isset($_POST["title"])? strip_tags($_POST["title"]):"";
    $description = isset($_POST["description"])? strip_tags($_POST["description"]):null;
    $available =  isset($_POST["available"])? (int)strip_tags($_POST["available"]):null;
    //Les champs indispensables ne doivent pas être nul
    if(in_array(null,[$id,$category,$description,$available],true)){
      $this->addLog("Ohla, dans les choux ! des champs ne sont pas valides", "alert-warning");
      header("Location: index.php?controller=baskets&action=myBaskets");
      exit;
    }
    //Mise à jour du panier
    $this->model->editBasket($id,$category,$title,$description,$available);
    $this->addLog("Parfait, le panier a été mis à jour", "alert-success");
    header("Location: index.php?controller=baskets&action=myBaskets");
  } 

  public function deleteBasket($id) {
    //Récupération du pannier  correspondant à l'id
    $basket = $this->model->findBasket($id);
    //Le panier doit exister et nous appartenir => impossible de supprimer le panier d'une autre personne
    if(is_null($basket) || $basket["company_id"] !== $_SESSION["user"]["id"] ){
      $this->addLog("Ohla, dans les choux ! le panier n'a pas été supprimé", "alert-danger");
    }else{
      //Suppression du panier
      $res=$this->model->deleteBasket($id);
      if($res){
        $this->addLog("Parfait le panier a été supprimé", "alert-success");
      }else{
        $this->addLog("Ohla, dans les choux ! le panier n'a pas été supprimé", "alert-danger");
      }
    }     
    header("Location: index.php?controller=baskets&action=myBaskets");
  }

}