<?php
namespace App\Controllers\BackDev;
use App\Models\CategoriesModel;

class BackController extends \App\Controllers\Controller
{
    public function index() {
        $this->view->render("dev/index",[]);
    }
}