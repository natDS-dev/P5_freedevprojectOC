<?php
namespace App\Controllers\BackAdmin;
use App\Models\CategoriesModel;

class BackController extends \App\Controllers\Controller
{
    public function index() {
        $this->view->render("admin/index",[]);
    }
}