<?php
namespace App\Models;

class BasketsModel extends Model
{
    public function findLastBaskets()
    {
        $sql='SELECT baskets.*,users.company,users.address FROM `baskets` INNER JOIN `users` ON users.id=baskets.company_id WHERE baskets.available=1 LIMIT 5';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    public function findMapBaskets()
    {
        $sql='SELECT baskets.*,users.id AS company_id,users.company AS name,users.address,users.address,users.city,users.zip_code,users.phone,users.lat,users.lng FROM `baskets` INNER JOIN `users` ON users.id=baskets.company_id WHERE baskets.available=1';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    public function createBasket($title,$description,$company_id,$available)
    {
        $sql='INSERT INTO `baskets`(title, description, company_id, available) VALUES (:title, :description, :company_id, :available)';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "title"=>$title,
            "description"=>$description,
            "company_id"=>$company_id,
            "available"=>$available
        ]);
        return $res;
    }

    public function editBasket($id,$title,$description,$company_id,$available)
    {
        $sql='UPDATE `baskets` SET title=:title, description=:description, company_id=:company_id, available=:available WHERE id=:id';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "id"=>$id,
            "title"=>$title,
            "description"=>$description,
            "company_id"=>$company_id,
            "available"=>$available
        ]);
        return $res;
    }

    public function findBasket($id)
    {
        $query=$this->db->getPDO()->prepare('SELECT * FROM `baskets` WHERE id=:id');
        $res= $query->execute(["id" => $id]);
        if($res){
            return $query->fetch();
        }else{
            return null;
        }
    }


}