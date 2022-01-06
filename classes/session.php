<?php

class Session{


  private $sessionName = 'user';

  function __construct(){
    //error_log("SESSION::__construct()");
    if(session_status() == PHP_SESSION_NONE){
      session_start();
    }
  }


  public function setCurrentUser($user){
    //error_log("SESSION::setCurrentUser()");
    $_SESSION[$this->sessionName] = $user;
  }

  public function getCurrentUser(){
    //error_log("SESSION::getCurrentUser()" . $_SESSION[$this->sessionName]);
    return $_SESSION[$this->sessionName];
  }

  public function closeSession(){
    session_unset();
    session_destroy();
  }

  public function exists(){
    //error_log("SESSION::exists()");
    return isset($_SESSION[$this->sessionName]);
  }

}


?>