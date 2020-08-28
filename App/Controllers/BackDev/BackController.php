<?php
namespace App\Controllers\BackDev;
use App\Models\CategoriesModel;
use App\Models\AddsModel;
use App\Models\BasketsModel;
use App\Models\UsersModel;

class BackController extends \App\Controllers\Controller
{
    public function index() {
        $data = ['title' => 'QG du dev'];
        $this->model = new AddsModel($this->db); 
        $data["adds"] = $this->model->findAllAdds(); 
        $this->model = new BasketsModel($this->db); 
        $data["baskets"] = $this->model->findAllBaskets();
        $this->model = new UsersModel($this->db); 
        $data["users"] = $this->model->findAllUsersByUserRole(1);  
        $data["usersPro"] = $this->model->findAllUsersByUserRole(2);    
        $this->view->render("dev/index",$data);
    }
    
    public function deleteAdd($id){
        $this->model = new AddsModel($this->db);
        $this->model->deleteAdd($id);  
        $this->addLog("L'annonce a bien été supprimée", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }

    public function deleteBasket($id){
        $this->model = new BasketsModel($this->db);
        $this->model->deleteBasket($id);  
        $this->addLog("Le panier a bien été supprimé", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }

    public function banUser($id){
        if($id === $_SESSION["user"]["id"] && $_SESSION["user"]["role"] === 0){
            $this->addLog("Le compte admin ne peut pas être bloqué'", "alert-danger");
            header("Location: index.php?controller=back&action=index");
            exit;
        }
        $this->model = new UsersModel($this->db); 
        $this->model->banUser($id); 
        $this->addLog("L'utilisateur a bien été banni'", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }

    public function unbanUser($id){
        $this->model = new UsersModel($this->db); 
        $this->model->unbanUser($id); 
        $this->addLog("L'utilisateur a été débloqué", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }
}