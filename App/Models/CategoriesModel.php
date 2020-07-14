<?php
namespace App\Models;

class CategoriesModel extends Model
{
    public function findAll($role)
    {
        $sql='SELECT* FROM `categories` WHERE role=:role ORDER BY `select_value`';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute(['role' => $role]);
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }
}