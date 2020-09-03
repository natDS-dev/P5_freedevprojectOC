<?php
namespace App\Controllers\BackDev;
use App\Models\CategoriesModel;
use App\Models\AddsModel;
use App\Models\BasketsModel;
use App\Models\UsersModel;

class BackController extends \App\Controllers\Controller
{   //Accès page d'accueil du mode Dév - Home access to Dev mode
    public function index() {
        $data = ['title' => 'QG du dev'];
        $this->model = new AddsModel($this->db); 
        //Appel à la Liste des annonces du AddsModel - Adds list 
        $data["adds"] = $this->model->findAllAdds(); 
        //Statistiques pour les catégories d'annonces particuliers - Stats by catégories for individual adds
        $data["adds_stats"] = $this->model->findAddsStats();
        $this->model = new BasketsModel($this->db);
        //Appel à la Liste des annonces panier - Baskets list 
        $data["baskets"] = $this->model->findAllBaskets();
        //Statistiques pour les catégories de paniers professionnels - Stats by catégories for professionnal baskets 
        $data["baskets_stats"] = $this->model->findBasketsStats();
        $this->model = new UsersModel($this->db);
        //Appel fonction du usersModel pour classement des utilisateurs par rôle (particulier et pro)  - individual & professionnal user list
        $data["users"] = $this->model->findAllUsersByUserRole(1);  
        $data["usersPro"] = $this->model->findAllUsersByUserRole(2);    
        $this->view->render("dev/index",$data);
    }
    
    //Fonction effacement des annonces particuliers - Delete add function (individuals)
    public function deleteAdd($id){
        $this->model = new AddsModel($this->db);
        $this->model->deleteAdd($id);  
        $this->addLog("L'annonce a bien été supprimée", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }

    //Fonction effacement des paniers - Delete basket function (professionnals)
    public function deleteBasket($id){
        $this->model = new BasketsModel($this->db);
        $this->model->deleteBasket($id);  
        $this->addLog("Le panier a bien été supprimé", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }

    //Fonction bannissement d'un utilisateur (particulier ou professionnel) - Ban user function (individual or pro)
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

    // Fonction de réintégration d'un utilisateur (particulier ou pro) - Unblock user function
    public function unbanUser($id){
        $this->model = new UsersModel($this->db); 
        $this->model->unbanUser($id); 
        $this->addLog("L'utilisateur a été débloqué", "alert-success");
        header("Location: index.php?controller=back&action=index");
    }
}