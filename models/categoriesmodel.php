<?php

class categoriesModel extends Model implements iModel{
  

  private $id;
  private $name;
  private $color;


  function __construct(){
    parent::__construct();
  }

  //setters
  public function setId($id){$this->id = $id;}

  public function setName($name){$this->name = $name;}

  public function setColor($color){$this->color = $color;}

  //getters

  public function getId(){return $this->id;}

  public function getName(){return $this->name;}

  public function getColor(){return $this->color;}

  //functions

  public function save(){
    try{
      $query = $this->prepare('INSERT INTO categories (name, color) VALUES (:name, :color)');
      $query->execute([
        'name' => $this->name, 
        'color' => $this->color
      ]);
      if($query->rowCount() > 0){
        return true;
      }
    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }

  public function update(){
    try{
      $query = $this->prepare('UPDATE categories SET name = :name, color = :color WHERE id = :id');
      $query->execute([
        'name' => $this->name, 
        'color' => $this->color,
        'id' => $this->id
      ]);
       $p = $query->fetch(PDO::FETCH_ASSOC);
       return true;
    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }
  public function delete($id){
    try{
      $query = $this->prepare('DELETE FROM categories WHERE id = '.$id);
      $query->execute([
          'id' => $id
      ]);
      return true;
      
    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }

  public function get($id){
    try{
      $query = $this->prepare('SELECT * FROM categories WHERE id = :id');
      $query->execute([
          'id' => $id
      ]);

      $p = $query->fetch(PDO::FETCH_ASSOC);
      
      $this->from($p);
      
      return $this;
      
    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }

  public function getAll(){

    $items = []; 

    try{
      $query = $this->query('SELECT * FROM categories');
      
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new categoriesModel();
        $item->from($p);
        array_push($items, $item);
      }
      return $items;
      
    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }

  }

  public function from($array){
    $this->setColor($array['color']);
    $this->setId($array['id']);
    $this->setName($array['name']);
  }

  public function exist($name){
    try{
      $query = $this->prepare('SELECT * FROM categories WHERE name = :name');
      $query->execute([
        'name' => $name
      ]);
      if($query->rowCount() > 0){
        return true;
      }
      return false;
    }catch(PDOException $e){
      error_log('exist: failed -> PDOException: '.$e->getMessage());
      return false;
    }
  }
}

?>