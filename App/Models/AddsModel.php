<?php
namespace App\Models;

class AddsModel extends Model
{
    //Renvoit les annonces à afficher sur la carte - Sends adds to display on the map  
    public function findMapAdds()
    {
        $sql='SELECT adds.id,select_value,adds.title,adds.description,adds.basket_size,adds.basket_quantity,users.id AS users_id,users.name,users.address,users.zip_code,users.city,users.lat,users.lng FROM `adds` INNER JOIN `users` ON users.id=adds.creator_id INNER JOIN `categories` ON categories.id=adds.category WHERE adds.closed=0 ORDER BY users.id';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    // Renvoit toutes les annonces ouvertes -  Sends all open adds
    public function findAllAdds()
    {
        $sql='SELECT adds.*,users.name,users.address FROM `adds` INNER JOIN `users` ON users.id=adds.creator_id WHERE closed=0';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    //Renvoi 5 dernières annonces de manière générale - Sends 5 last adds
    public function findLastAdds()
    {
        $sql='SELECT adds.*,users.name,users.address FROM `adds` INNER JOIN `users` ON users.id=adds.creator_id LIMIT 5';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    //Création d'une annonce - Creating add
    public function createAdd($category,$title,$description,$creator_id,$basket_size,$basket_quantity)
    {
        $sql='INSERT INTO `adds`(category,title, description, creator_id, basket_size, basket_quantity) VALUES (:category,:title, :description, :creator_id, :basket_size, :basket_quantity)';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "category" => $category,
            "title" => $title,
            "description" => $description,
            "creator_id" => $creator_id,
            "basket_size" => $basket_size,
            "basket_quantity" => $basket_quantity
        ]);
        return $res;
    }

    //Mise à jour d'une annonce - Update one add
    public function editAdd($id,$category,$title,$description,$basket_size,$basket_quantity)
    {
        $sql='UPDATE `adds` SET category=:category,title=:title, description=:description, basket_size=:basket_size, basket_quantity=:basket_quantity WHERE id=:id';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "id" => $id,
            "category" => $category,
            "title" => $title,
            "description" => $description,
            "basket_size" => $basket_size,
            "basket_quantity" => $basket_quantity
        ]);
        return $res;
    }

    //Renvoi de l'annonce demandée (id) - Find asked add by id 
    public function findAdd($id)
    {
        $query=$this->db->getPDO()->prepare('SELECT * FROM `adds` WHERE id=:id');
        $res= $query->execute(["id" => $id]);
        if($res){
            return $query->fetch();
        }else{
            return null;
        }
    }

    //Nombre total d'annonce d'un utilisateur donné - Total user adds
    public function userAddsTotal($userId){
        $query=$this->db->getPDO()->prepare('SELECT COUNT(*) AS total_adds FROM adds WHERE `creator_id`=:userId');
        $res= $query->execute(["userId" => $userId]);
        if($res){
            return (int)($query->fetch()['total_adds']);
        }else{
            return 0;
        }
    }

    //Renvoi d' une page d'annonce pour un utilisateur "donné" => myadds - Sends add page for a specific user
    public function userAddsPage($userId,$perPage,$page){
        $offset = $perPage * ($page - 1);
        
        $query=$this->db->getPDO()->prepare('SELECT adds.*, select_value  FROM adds INNER JOIN `categories` ON categories.id=adds.category WHERE `creator_id`=:userId ' . ' ORDER BY adds.id DESC  LIMIT '  . $perPage . ' OFFSET ' . $offset);
        $res= $query->execute([
            "userId" => $userId
        ]);
        if($res){
            return $query->fetchAll();
        }else{
            return [];
        }
    }

    //Effacement d'une annonce - Delete an add
    public function deleteAdd($id){
        $query=$this->db->getPDO()->prepare('DELETE FROM `adds` WHERE id=:id');
        $query->execute(["id" => $id]);
        return $query->rowCount() === 1;
    }

    //Trouver les paniers disponibles(contenu rétribution) lorsque validation annonce - Find all availables baskets when accepted offer
    public function findAvailableBaskets()
    {
        $sql='SELECT baskets.*,users.company,users.address,users.zip_code,users.city FROM `baskets` INNER JOIN `users` ON users.id=baskets.company_id WHERE baskets.available=1';
        
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }
    // Valider participation à une annonce = 2 étapes - 2 steps validating offer
    //Si annonce validée => étape 1 : fermer l'annonce - When an offer(add) is accepted => step 1
    public function closeAdd($id){
        $sql='UPDATE adds SET closed = 1 WHERE id=:id'; 
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute(["id" => $id]);
    }
    //étape 2 : confirmation participation/validation  - Step 2 : Confirm offer 
    public function confirmAdd($addId, $userId, $basketId){
        $sql='INSERT INTO validations (user_id, basket_id,add_id) VALUES (:userId,:basketId,:addId)'; 
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute([
            "addId" => $addId,
            "userId" => $userId,
            "basketId" => $basketId
        ]);
    }

    //Statistiques pour le mode développeur : nb d'annonces par catégorie - Statistics for developper mode : number of adds by category
    public function findAddsStats(){
        $sql= 'SELECT categories.*,COUNT(adds.id) AS adds_count FROM categories 
        LEFT JOIN adds ON categories.id=adds.category GROUP BY categories.id HAVING role = 1';
        $query=$this->db->getPDO()->prepare($sql);
        $res= $query->execute();
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    } 
}