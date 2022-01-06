<?php

include_once 'models/usermodel.php';

class loginModel extends Model{


  function __construct(){
    parent::__construct();
  }


  function login($username, $password){
        error_log("login: inicio");
        try{
            $query = $this->prepare('SELECT * FROM users WHERE username = :username');
            $query->execute(['username' => $username]);
            
            if($query->rowCount() == 1){
                $item = $query->fetch(PDO::FETCH_ASSOC); 
                $user = new userModel();
                $user->from($item);               
                if(password_verify($password, $user->getPassword())){
                  //error_log("login: correcto");
                  return $user;           
                }else{
                  //error_log("login: incorrecto");
                  return NULL;
                }
            }else{
                //error_log('login: failed -> user not found');
                return NULL;
            }
        }catch(PDOException $e){
            error_log('login: failed -> PDOException: '.$e->getMessage());
            return NULL;
        }
  }


}


?>