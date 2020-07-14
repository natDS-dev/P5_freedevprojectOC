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
        $sql='SELECT baskets.*,select_value,users.id AS company_id,users.company AS name,users.address,users.address,users.city,users.zip_code,users.phone,users.lat,users.lng FROM `baskets` INNER JOIN `users` ON users.id=baskets.company_id INNER JOIN `categories` ON categories.id=baskets.category WHERE baskets.available=1';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    public function createBasket($title,$category,$description,$company_id,$available)
    {
        $sql='INSERT INTO `baskets`(title,category, description, company_id, available) VALUES (:title,:category, :description, :company_id, :available)';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "title" => $title,
            "category" => $category,
            "description" => $description,
            "company_id" => $company_id,
            "available" => $available
        ]);
        return $res;
    }

    public function editBasket($id,$category,$title,$description,$available)
    {
        $sql='UPDATE `baskets` SET title=:title,category=:category, description=:description, available=:available WHERE id=:id';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "id" => $id,
            "title" => $title,
            "category" => $category,
            "description" => $description,
            "available" => $available
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

    //Effacement du panier
    public function deleteBasket($id){
        $query=$this->db->getPDO()->prepare('DELETE FROM `baskets` WHERE id=:id');
        $query->execute(["id" => $id]);
        return $query->rowCount() === 1;
    }

    //Nombre total de paniers d'un utilisateur donné
    public function userBasketsTotal($userId){
        $query=$this->db->getPDO()->prepare('SELECT COUNT(*) AS total_baskets FROM baskets WHERE `company_id`=:userId');
        $res= $query->execute(["userId" => $userId]);
        if($res){
            return (int)($query->fetch()['total_baskets']);
        }else{
            return 0;
        }
    }

    //Renvoi d'une page de paniers pour un utilisateur "donné" => mybaskets
    public function userBasketsPage($userId,$perPage,$page){
        $offset = $perPage * ($page - 1);
        
        $query=$this->db->getPDO()->prepare('SELECT baskets.*, select_value  FROM baskets INNER JOIN `categories` ON categories.id=baskets.category WHERE `company_id`=:userId ' . 'LIMIT '  . $perPage . ' OFFSET ' . $offset);
        $res= $query->execute([
            "userId" => $userId
        ]);
        if($res){
            return $query->fetchAll();
        }else{
            return [];
        }
    }
}