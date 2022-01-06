<?php

require_once 'models/usermodel.php';

  class signup extends SessionController{

    function __construct(){
      parent::__construct();
      
    }

    public function render(){
      $this->view->render('login/signup');
    }

    function newUser(){
      if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        
        //si estan vacios los campos
        if($username == "" || $password == ""){
          
          $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
          return;
        }

        $user = new userModel();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRole('user');

        if($user->exists($username)){
          $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);
          return;
        }else if($user->save()){
         
          $this->redirect('login', ['success' => SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
          return;
        }

      }else{
        $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
        return;
      }
      
    }
    
  }



?>