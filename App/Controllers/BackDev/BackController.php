<?php
namespace App\Controllers\BackDev;
use App\Models\CategoriesModel;
use App\Models\AddsModel;
use App\Models\BasketsModel;

class BackController extends \App\Controllers\Controller
{
    public function index() {
        $data = ['title' => 'QG du dev'];
        $this->model = new AddsModel($this->db); 
        $data["adds"] = $this->model->findAllAdds(); 
        $this->model = new BasketsModel($this->db); 
        $data["baskets"] = $this->model->findAllBaskets();  
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
}