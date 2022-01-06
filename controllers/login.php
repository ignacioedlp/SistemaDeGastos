<?php


class Login extends SessionController{

  function __construct(){
    parent::__construct();
  }

  function render(){
    $this->view->render('login/index');
  }

  function authenticate(){
    if($this->existPOST(['username', 'password'])){
      $username = $this->getPOST('username');
      $password = $this->getPOST('password');
      //error_log('LOGIN::authenticate->username: '.$username);
      if($username == "" || $password == ""){
          //error_log('LOGIN::authenticate->empty: '.$username);
          $this->redirect('login', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
          return;
      }

      $user = $this->model->login($username, $password);

     
      
      if($user !== NULL){
        $this->initialize($user);
        //error_log('LOGIN::authenticate->success: ');
        return;

      }else{
        $this->redirect('login', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);
        //error_log('LOGIN::authenticate->failed: ');
        return;
      }
    }else{
      //error_log('LOGIN::authenticate->error: '.$username);
      $this->redirect('login', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
      return;
    }
  }
}


?>