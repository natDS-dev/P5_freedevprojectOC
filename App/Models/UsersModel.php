<?php
namespace App\Models;

class UsersModel extends Model
{
    //Insertion données nouvel utilisateur - Insert new user values 
    public function createNewUser($name, $surname, $company, $address, $zipcode, $city,	$phone,	$email,	$password, $role, $profile, $lat, $lng,$recoveryToken)
    {
         $query=$this->db->getPDO()->prepare('INSERT INTO `users` (name, surname, company, address, zip_code,	city,	phone,	email,	password, role, profile, lat, lng, recovery_token) VALUES (:name, :surname, :company, :address, :zip_code,	:city,	:phone,	:email,	:password, :role, :profile, :lat, :lng, :token)');
         return $query->execute([ 
            "name"=>$name,
            "surname"=>$surname,
            "company"=>$company,
            "address"=>$address,
            "zip_code"=>$zipcode,
            "city"=>$city,
            "phone"=>$phone,
            "email"=>$email,
            "password"=>$password,
            "role"=>$role,
            "profile"=>$profile,
            "lat"=>$lat,
            "lng"=>$lng,
            "token"=>$recoveryToken
         ]);
    }

    // Mise à jour infos utilisateur - Update user infos
    public function updateUser($id, $name, $surname, $company, $address, $zipCode, $city,	$phone,	$email,	$password, $profile, $lat, $lng){
        $query=$this->db->getPDO()->prepare('UPDATE `users` SET name=:name,surname=:surname,company=:company,address=:address,zip_code=:zip_code,city=:city,phone=:phone,email=:email,password=:password,profile=:profile,lat=:lat,lng=:lng WHERE id=:id');
        return $query->execute([ 
            "name"=>$name,
            "surname"=>$surname,
            "company"=>$company,
            "address"=>$address,
            "zip_code"=>$zipCode,
            "city"=>$city,
            "phone"=>$phone,
            "email"=>$email,
            "password"=>$password,
            "profile"=>$profile,
            "lat"=>$lat,
            "lng"=>$lng,
            "id"=>$id
         ]);
    }

    //Trouve utilisateur par email - Find user by email adress
    public function findUserByUserEmail($email)
    {
        $query=$this->db->getPDO()->prepare('SELECT * FROM `users` WHERE email=:email');
        $res= $query->execute(["email" => $email]);
        if($res){
            return $query->fetch();
        }else{
            return null;
        }
    }
    
    // Trouve le mp par l'email utilisateur - Find passssord by user email
    public function findPasswordByUserEmail($email)
    {
        $query=$this->db->getPDO()->prepare('SELECT password FROM `users` WHERE email=:email');
        $res= $query->execute(["email" => $email]);
        if($res){
            return $query->fetch()["password"];
        }else{
            return null;
        }
    }

    // Statistiques pour tableau de bord utilisateur - Statistics for user dashboard
    public function findBadgesStats($id)
    {
        //Nb d'annonces - Total adds 
        $badgeStats = [];
        $query=$this->db->getPDO()->prepare('SELECT COUNT(*) AS created_adds FROM `adds` WHERE creator_id=:id AND closed=0');
        $res= $query->execute(["id" => $id]);
        $badgeStats['createdAdds'] = $res?(int)$query->fetch()["created_adds"]:0;

        //Nb d'offres/missions acceptées - Total accepted offers
        $query=$this->db->getPDO()->prepare('SELECT COUNT(*) AS accepted_adds FROM `validations` WHERE user_id=:id');
        $res= $query->execute(["id" => $id]);
        $badgeStats['acceptedAdds'] = $res?(int)$query->fetch()["accepted_adds"]:0;

        //Nb de paniers disponibles créés - Total availables baskets created 
        $query=$this->db->getPDO()->prepare('SELECT COUNT(*) AS available_baskets FROM `baskets` WHERE company_id=:id AND available=1');
        $res= $query->execute(["id" => $id]);
        $badgeStats['availableBaskets'] = $res?(int)$query->fetch()["available_baskets"]:0;
        
        return $badgeStats;
    }

    // Trouve un utilisateur par son id - Find user 
    public function findUser($id)
    {
        $query=$this->db->getPDO()->prepare('SELECT * FROM `users` WHERE id=:id');
        $res= $query->execute(["id" => $id]);
        if($res){
            return $query->fetch();
        }else{
            return null;
        }
    }

    // Mise à jour du token - Update token 
    public function  setToken($email,$token){
        $query=$this->db->getPDO()->prepare('UPDATE `users` SET recovery_token=:token WHERE email=:email');
        $res= $query->execute([
            "email" => $email,
            "token" => $token
        ]);
        return $query->rowCount() === 1;
    }

    //Renvoi du token - Sends token
    public function  getToken($email){
        $query=$this->db->getPDO()->prepare('SELECT `recovery_token` FROM `users` WHERE email=:email');
        $res= $query->execute([
            "email" => $email
        ]);
        return $query->fetch()["recovery_token"];
    }

    //Mise à jour du mp - Update user password
    public function updatePassword($email,$newPassword){
        $query=$this->db->getPDO()->prepare('UPDATE `users` SET recovery_token="", password=:newPassword WHERE email=:email');
        $res= $query->execute([
            "email" => $email,
            "newPassword" => $newPassword
        ]);
        return $query->rowCount() === 1;
    }

    //Trouve tous les utilisateurs par rôle - Find all users by role
    public function findAllUsersByUserRole($role = "*"){
        $query=$this->db->getPDO()->prepare('SELECT * FROM `users` WHERE `role`=:role');
        $res= $query->execute(["role"=>$role]);
        if($res){
            return $query->fetchAll();
        }else{
            return null;
        }
    }


    //Mode développeur : Mise à jour du statut "bloqué" pour un utilisateur - Update user status => ban
    public function banUser($id){
        $query=$this->db->getPDO()->prepare('UPDATE `users` SET `banned`= 1 WHERE id=:id');
        $query->execute(["id"=> $id]);
        return $query->rowCount() === 1;
    }

    //Mise à jour statut "débloqué" pour un utilisateur - Update user status => unbaned
    public function unbanUser($id){
        $query=$this->db->getPDO()->prepare('UPDATE `users` SET `banned`= 0 WHERE id=:id');
        $query->execute(["id"=> $id]);
        return $query->rowCount() === 1;
    }
}