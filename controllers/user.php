<?php

require_once 'models/usermodel.php';

class User extends SessionController{
  
  private $user;
  
  
  function __construct(){
    parent::__construct();
    $this->user = $this->getUserSessionData();
    //error_log('USER::USER: name: ' . $this->user->getName() . ' username: ' . $this->user->getUsername() . ' role: ' . $this->user->getRole());
  }


  function render() {
    $this->view->render('user/index', [
      'user' => $this->user
    ]);
  }


  function updateBudget(){
    if(!$this->existPost('budget')){
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET]); 
      return;
    }

    $budget = $this->getPost('budget');

    if(empty($budget) || $budget < 0){
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET_EMPTY]);
      return;
    }

    $this->user->setBudget($budget);

    if($this->user->update()){
      $this->redirect('user' , ['success' => SuccessMessages::SUCCESS_USER_UPDATEBUDGET]); 
     
    }

  }
  

  function updateName(){
    if(!$this->existPost('name')){
      //error_log('USER::UPDATENAME: No name');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATENAME]); 
      return;
    }

    $newName = $this->getPost('name');
    //error_log('USER::UPDATENAME: name: ' . $newName);

    if(empty($newName) || $newName == '' || $newName == null){
      //error_log('USER::UPDATENAME: name is empty');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATENAME_EMPTY]); 
      return;
    }

    $this->user->setName($newName);
    // error_log('USER::UPDATENAME: name: ' . $this->user->getName());

    if($this->user->update()){
      // error_log('USER::UPDATENAME: name updated');
      $this->redirect('user' , ['success' => SuccessMessages::SUCCESS_USER_UPDATENAME]); 
    }
  }

  function updatePassword(){
    if(!$this->existPost(['current_password', 'new_password'])){
      //error_log('USER::UPDATEPASSWORD: No password');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
      return;
    }
    $current = $this->getPOST('current_password');
    $new = $this->getPOST('new_password');


    if(empty($current) || empty($new)){
      //error_log('USER::UPDATEPASSWORD: password empty');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD_EMPTY]); 
      return;
    }

    if($current == $new){
      //error_log('USER::UPDATEPASSWORD: new password is the same as the current one');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
      return;
    }

    $newHash = $this->model->comparePasswords($current , $this->user->getId());

    if($newHash){
      //error_log('USER::UPDATEPASSWORD: password is correct');
      $this->user->setPassword($new);
      if($this->user->update()){
        //error_log('USER::UPDATEPASSWORD: password updated');
        $this->redirect('user' , ['success' => SuccessMessages::SUCCESS_USER_UPDATEPASSWORD]); 
      }else{
        //error_log('USER::UPDATEPASSWORD: password not updated');
        $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]); 
      }
    }else{
      //error_log('USER::UPDATEPASSWORD: password is incorrect');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD_ISNOTTHESAME]); 
    }
    
 
    
  }

  function updatePhoto(){

    if(!isset($_FILES['photo'])){
      //error_log('USER::UPDATEPHOTO: NO FILE');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO]); 
      return;
    }
    $photo = $_FILES['photo'];

    $targetDir = 'public/img/photos/';

    $extension = explode('.', $photo['name']);

    $filename = $extension[sizeof($extension)-2];

    $ext = $extension[sizeof($extension)-1];

    $hash = md5(Date('Ymdgi') . $filename) . '.' . $ext;

    $targetFile = $targetDir . $hash;

    $uploadOk = false;

    // error_log('USER::UPDATEPHOTO: ' . $targetFile);

    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    $check = getimagesize($photo['tmp_name']);

    if($check){
      $uploadOk = true;
    }else{
      $uploadOk = false;
      
    }

    if($uploadOk == false){
    //  error_log('USER::UPDATEPHOTO: uploadOk = false');
      $this->redirect('user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO_FORMAT]); 
      return;
    }

    // error_log('USER::UPDATEPHOTO: uploadOk = true');

    if(move_uploaded_file($photo['tmp_name'], $targetFile)){
      // error_log('USER::UPDATEPHOTO: move_uploaded_file');
      $this->user->setPhoto($hash);
      $this->user->update();
      // error_log('USER::UPDATEPHOTO: updatePhoto');
      $this->redirect('user' , ['success' => SuccessMessages::SUCCESS_USER_UPDATEPHOTO]); 
      return;

    }else{
      // error_log('USER::UPDATEPHOTO: move_uploaded_file failed');
      $this->redirect('/user' , ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO]); 
      return;
    }

  }








}





?>