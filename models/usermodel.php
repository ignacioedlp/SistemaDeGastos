<?php

class userModel extends Model implements iModel{

  private $id;
  private $username;
  private $password;
  private $role;
  private $photo;
  private $budget;
  private $name;


  function __construct(){
    parent::__construct();
    $this->id = '';
    $this->username = '';
    $this->password = '';
    $this->role = '';
    $this->photo = '';
    $this->budget = 0.0;
    $this->name = '';
  }

    //getters y setters
  public function getId(){return $this->id;}
  public function getUsername(){return $this->username;}
  public function getPassword(){return $this->password;}
  public function getRole(){return $this->role;}
  public function setId($id){$this->id = $id;}
  public function getPhoto(){return $this->photo;}
  public function getBudget(){return $this->budget;}
  public function getName(){return $this->name;}
  
  public function setUsername($username){$this->username = $username;}

  private function getHashPassword($password){
    return $hash = password_hash($password, PASSWORD_DEFAULT);
  }

  public function setPassword($password){
    $this->password = $this->getHashPassword($password);
  }
  public function setRole($role){$this->role = $role;}
  public function setPhoto($photo){$this->photo = $photo;}
  public function setBudget($budget){$this->budget = $budget;}
  public function setName($newname){$this->name = $newname;}

 public function save(){
        //error_log('MODEL:: userModel:: save' . $this->username);
        try{
            $query = $this->prepare('INSERT INTO users (username, password, role, budget, photo, name) VALUES(:username, :password, :role, :budget, :photo, :name )');
            $query->execute([
                'username'  => $this->username, 
                'password'  => $this->password,
                'role'      => $this->role,
                'budget'    => $this->budget,
                'photo'     => $this->photo,
                'name'      => $this->name
                ]);
            return true;
        }catch(PDOException $e){
            error_log('USERMODEL::save::error: '.$e->getMessage());
            return false;
        }
      }
  public function update(){
    try{
      $sql = $this->prepare('UPDATE users SET username = :username, password = :password, role = :role, photo = :photo, budget = :budget, name = :name WHERE id = :id');
      $sql->execute([
        'id' => $this->id,
        'username' => $this->username,
        'password' => $this->password,
        'role' => $this->role,
        'photo' => $this->photo,
        'budget' => $this->budget,
        'name' => $this->name
      ]);
      return true;
    }
    catch(Exception $e){
      error_log('USERMODEL::update->PDOEception: '.$e->getMessage());
      return false;
    }
    catch(Exception $e){
      error_log('USERMODEL::update->PDOEception: '.$e->getMessage());
      return false;
    }
  }

  public function delete($id){
    try{
      $sql = $this->prepare('DELETE FROM users WHERE id = :id');
      $sql->bindParam(':id', $id);
      $sql->execute();
      return true;
    }
    catch(Exception $e){
      error_log('USERMODEL::delete->PDOEception: '.$e->getMessage());
      return false;
    }
  }

  public function get($id){
    try{
      $sql = $this->prepare('SELECT * FROM users WHERE id = :id');
      $sql->execute([':id' => $id]);
      $user = $sql->fetch(PDO::FETCH_ASSOC);
      $this->setId($user['id']);
      $this->setUsername($user['username']);
      //$this->setPassword($user['password']);
      $this->password = $user['password'];
      $this->setRole($user['role']);
      $this->setPhoto($user['photo']);
      $this->setBudget($user['budget']);
      $this->setName($user['name']);
        
      return $this;
    }
    catch(Exception $e){
      error_log('USERMODEL::getAll->PDOEception: '.$e->getMessage());
      return false;
    }
  }
  public function getAll(){

    $items = [];

    try{
      $query = $this->query('SELECT * FROM users');

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new userModel();
                $item->setId($p['id']);
                $item->setUsername($p['username']);
                $item->setPassword($p['password'], false);
                $item->setRole($p['role']);
                $item->setBudget($p['budget']);
                $item->setPhoto($p['photo']);
                $item->setName($p['name']);
                

                array_push($items, $item);
            }
            return $items;
    }
    catch(Exception $e){
      error_log('USERMODEL::getAll->PDOEception: '.$e->getMessage());
      return false;
    }
  }
  public function from($array){
    $this->id = $array['id'];
    $this->username = $array['username'];
    $this->password = $array['password'];
    $this->role = $array['role'];
    $this->budget = $array['budget'];
    $this->photo = $array['photo'];
    $this->name = $array['name'];
  }

  public function exists($username){
    //error_log('USERMODEL::exists->username: '.$username);
    try{
      $sql = $this->prepare('SELECT * FROM users WHERE username = :username');
      $sql->execute([':username' => $username]);
      
      if($sql->rowCount() > 0){
        return true;
      }
      else{
        return false;
      }
    }
    catch(Exception $e){
      error_log('USERMODEL::exists->PDOEception: '.$e->getMessage());
      return false;
    }
  }

 function comparePasswords($current, $userid){
        try{
            $query = $this->db->connect()->prepare('SELECT id, password FROM USERS WHERE id = :id');
            $query->execute(['id' => $userid]);
            
            if($row = $query->fetch(PDO::FETCH_ASSOC)) {
              if(password_verify($current, $row['password'])){
                //error_log('USERMODEL::comparePasswords->password_verify: '. $current);
                //error_log('USERMODEL::comparePasswords correct ->password_verify: '.$row['password']);
                return true;
              }
              else{
                //error_log('USERMODEL::comparePasswords->password_verify: '. $current);
                //error_log('USERMODEL::comparePasswords incorrect ->password_verify: '.$row['password']);
                return false;
              };
              //error_log('USERMODEL::comparePasswords->error: ');
              return true;
            }
            else{
            return NULL;
            }
        }catch(PDOException $e){
            return NULL;
        }
    }


}


?>