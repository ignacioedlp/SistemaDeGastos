<?php


class View{


  function __construct(){
    //echo 'This is the view';
  }

  function render($nombre, $data = []){
    $this->d = $data;

    $this->handleMessage();

    require 'views/' . $nombre . '.php';
  }


  function handleMessage(){
    if(isset($_GET['success']) && isset($_GET['error'])){
      //error message

    }else if(isset($_GET['error'])){
        $this->handleError();
    }else if(isset($_GET['success'])){
      $this->handleSuccess();
    }
  }

  private function handleError(){
    $hash = $_GET['error'];
    $error = new ErrorMessages();

    if($error->existsKey($hash)){
      $mensaje = $error->get($hash);
      $this->d['error'] = $mensaje;
    }
  }
  private function handleSuccess(){
    $hash = $_GET['success'];
    $success = new SuccessMessages();

    if($success->existsKey($hash)){
      $mensaje = $success->get($hash);
      $this->d['success'] = $mensaje;
    }
  }

  public function showMessages(){
    $this->showErrors();
    $this->showSuccess();
  }

  public function showErrors(){
    if(isset($this->d['error'])){
      echo '<div class="alert alert-danger" role="alert">' . $this->d['error'] . '</div>';
    }
  }
  public function showSuccess(){
    if(isset($this->d['success'])){
      echo '<div class="alert alert-success" role="alert">' . $this->d['success'] . '</div>';
    }
  }


}


?>