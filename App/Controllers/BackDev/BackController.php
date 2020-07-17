<?php
namespace App\Controllers\BackDev;
use App\Models\CategoriesModel;
use App\Models\AddsModel;

class BackController extends \App\Controllers\Controller
{
    public function index() {
        $data = ['title' => 'QG du dev'];
        $this->model = new AddsModel($this->db); 
        $data["lastAdds"] = $this->model->findLastAdds();  
        $this->view->render("dev/index",$data);
    }
}