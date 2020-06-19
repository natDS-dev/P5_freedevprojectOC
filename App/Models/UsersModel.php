<?php
namespace App\Models;

class UsersModel extends Model
{
    public function createNewUser($name, $surname, $company, $address, $zipcode, $city,	$phone,	$email,	$password, $role, $profile, $lat, $lng)
    {
         $query=$this->db->getPDO()->prepare('INSERT INTO `users` (name, surname, company, address, zip_code,	city,	phone,	email,	password, role, profile, lat, lng) VALUES (:name, :surname, :company, :address, :zip_code,	:city,	:phone,	:email,	:password, :role, :profile, :lat, :lng)');
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
            "lng"=>$lng
         ]);
    }

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


}