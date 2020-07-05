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
    $lastBaskets = $this->model->findLastBaskets();
    $data['lastBaskets'] = $lastBaskets;
    $this->view->render('baskets/index', $data);
  }
  //Create => or edit basket if id is set and correct
  public function create($id=0)
  { 
    if((int)$_SESSION['user']['role'] !== 2)
    {
        $this->addLog("Oh oh, petit problème", "alert-warning");
        header("Location: index.php?controller=home&action=index");
        exit;
    }
    //variable data contenant le titre de la page 
    $data = ['title' => 'Créer un panier'];
    //Si id passé dans l'url et différent de 0 =>vérification  
    if($id !== 0)
    {
      //Recherche du panier correspondant à l'id
      $data['basket']=$this->model->findBasket((int)$id);
      //Vérification => si l'id ne correspond pas à l'id de l'auteur rattaché au panier    
      if((int)$_SESSION['user']['id'] !== (int)$data['basket']['company_id'])
      {
        $this->addLog("Oh oh, petit problème", "alert-warning");
        unset($data['basket']);
      }
    }
    //Si les champs de création de panier ne sont pas vides
    if(!empty($_POST)){
      $title = !empty($_POST["title"]) ? strip_tags($_POST["title"]) : null;
      $description = !empty($_POST["description"]) ? strip_tags($_POST["description"]) : null;
      if (!isset($_POST["available"])){
        $available = null;
      }else{
        $available = ($_POST["available"] === "1") ? 1 : 0;
      }  
        //Si 1 (ou plusieurs) champs est nul 
      if(is_null($title) || is_null($description) || is_null($available))
      {//=> renvoit d'un message d'erreur 
        $this->addLog("Oups tous les champs sont obligatoires","alert-danger");
        //Clé supplémentaire au tableau data pour pré-remplissage auto des champs
        $data['basket']= [
          "title" => $_POST["title"],
          "description" => $_POST["description"],
          "available" => $_POST["available"]
        ]; 
      }else{//si tout les champs ok
        $company_id = (int)$_SESSION["user"]["id"];
        if (!empty($_POST["id"]))
        {//si un id il y avait, alors s'agit d'une modification =>message de confirmation modifcation addlog
          $id=(int)($_POST["id"]);
          $this->addLog("Top ! le panier est bien modifié","alert-success");
          $this->model->editBasket($id,$title,$description,$company_id,$available);
        } else {//sinon (si aucun id) il s'agit d'une création => message de confirmation création addlog
          $this->addLog("Super! le panier est bien créé","alert-success");
          $this->model->createBasket($title,$description,$company_id,$available);
        }
      }  
    } 
    // On rend la vue correspondante  
    $this->view->render('baskets/create', $data);    
  }

}