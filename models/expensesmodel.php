<?php

class expensesModel extends Model implements iModel{
  
  private $id;
  private $title;
  private $amount;
  private $categoryid;
  private $date;
  private $userid;



  function __construct(){
    parent::__construct();
  }


  //setters
  public function setId($id){$this->id = $id;}
  
  public function setTitle($title){$this->title = $title;}

  public function setAmount($amount){$this->amount = $amount;}

  public function setCategoryid($categoryid){$this->categoryid = $categoryid;}

  public function setDate($date){$this->date = $date;}

  public function setUserid($userid){$this->userid = $userid;}

  //getters

  public function getId(){return $this->id;}

  public function getTitle(){return $this->title;}

  public function getAmount(){return $this->amount;}

  public function getCategoryid(){return $this->categoryid;}

  public function getDate(){return $this->date;}

  public function getUserid(){return $this->userid;}

  //functions

  public function save(){
    try{
      $query = $this->prepare('INSERT INTO expenses (title, amount, category_id, date, id_user) VALUES (:title, :amount, :categoryid, :date, :userid)');
      $query->execute([
        'title' => $this->title, 
        'amount' => $this->amount, 
        'categoryid' => $this->categoryid, 
        'date' => $this->date, 
        'userid' => $this->userid
      ]);
      if($query->rowCount() > 0){
        return true;
      }
      return false;

    }catch(PDOException $e){
      error_log('save: failed -> PDOException: '.$e->getMessage());
      return false;
    }
  }
  public function update(){
    try{
      $query = $this->prepare('UPDATE expenses SET title = :title, amount = :amount, categoryid = :categoryid, date = :date, id_user = :userid WHERE id = :id');
      $query->execute([
        'title' => $this->title(), 
        'amount' => $this->amount(), 
        'categoryid' => $this->categoryid(), 
        'date' => $this->date(), 
        'userid' => $this->userid(),
        'id' => $this->id()
      ]);
      if($query->rowCount() > 0){
        return true;
      }
      return false;

    }catch(PDOException $e){
      error_log('update: failed -> PDOException: '.$e->getMessage());
      return false;
    }
  }

  public function delete($id){
    try{
      $query = $this->prepare('DELETE FROM expenses WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);
      return true;
    }catch(PDOException $e){
      error_log('get: failed -> PDOException: '.$e->getMessage());
      return false;
    }
  }

  public function get($id){
    try{
      $query = $this->prepare('SELECT * FROM expenses WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);
      $expenses = $query->fetch(PDO::FETCH_ASSOC);
      $this->from($expenses);
      return $this;
    }catch(PDOException $e){
      error_log('get: failed -> PDOException: '.$e->getMessage());
      return false;
    }
  }
  
  
  
  public function getAll(){
    $items = [];
    try{
      $query = $this->query("SELECT * FROM expenses");
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new expensesModel();
        $item->from($p);
        array_push($items, $item);
      }
      return $items;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  }
  public function from($array){
    $this->setId($array['id']);
    $this->setTitle($array['title']);
    $this->setAmount($array['amount']);
    $this->setCategoryid($array['category_id']);
    $this->setDate($array['date']);
    $this->setUserid($array['id_user']);
  }

  public function getAllByUserId($id){
    $items = [];
    try{
      $query = $this->prepare("SELECT * FROM expenses WHERE id_user = :id");
      $query->execute([
        'id' => $id
      ]);
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new expensesModel();
        $item->from($p);
        array_push($items, $item);
      }
      return $items;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  }

  public function getByUserIdAndLimit($id, $limit){
    $items = [];
    try{
      $query = $this->prepare("SELECT * FROM expenses WHERE id_user = :id ORDER BY date DESC LIMIT :limit");
      $query->execute([
        'id' => $id, 
        'limit' => $limit
      ]);
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new expensesModel();
        $item->from($p);
        array_push($items, $item);
      }
      return $items;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  }

  public function getTotalAmountThisMonth($idUser){
    try{
      

      $year = date('Y');
      $month = date('m');
     
      $query = $this->prepare("SELECT SUM(amount) AS total FROM expenses WHERE id_user = :id AND YEAR(date) = :year AND MONTH(date) = :month");
      $query->execute([
        'id' => $idUser, 
        'year' => $year,
        'month' => $month
      ]);
      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
      if($total == NULL){
        
        return 0;
      }
      
      return $total;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  } 

  public function getMaxExpensesThisMonth($idUser){
    try{
      $year = date('Y');
      $month = date('m');
      $query = $this->prepare("SELECT MAX(amount) AS total FROM expenses WHERE id_user = :id AND YEAR(date) = :year AND MONTH(date) = :month");
      $query->execute([
        'id' => $idUser, 
        'year' => $year,
        'month' => $month
      ]);
      $max = $query->fetch(PDO::FETCH_ASSOC)['total'];
      
      if($max === NULL){
        return 0;
      }
      
      return $max;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  } 

   public function getTotalByCategoryThisMonth($categoryid,$idUser){
    try{
      $year = date('Y');
      $month = date('m');
      $query = $this->prepare("SELECT SUM(amount) AS total 
                              FROM expenses 
                              WHERE category_id = :category AND id_user = :id 
                                AND YEAR(date) = :year AND MONTH(date) = :month");
      $query->execute([
        'id' => $idUser, 
        'year' => $year,
        'month' => $month,
        'category' => $categoryid
      ]);
      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
      if($total == NULL){
        return 0;
      }
      return $total;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  }


  function getTotalByMonthAndCategory($date, $category, $userid){
    try{
      $total = 0;
      $year = substr($date, 0, 4);
      $month = substr($date, 5, 7);

      $query = $this->prepare('SELECT SUM(amount) AS total 
                              FROM expenses 
                              WHERE YEAR(date) = :year AND MONTH(date) = :month 
                              AND category_id = :category AND id_user = :user');
      $query->execute([
        'year' => $year,
        'month' => $month,
        'category' => $category,
        'user' => $userid
      ]);
      if($query->rowCount() > 0){
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
      }
      return $total;
  

    }catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }
  
  public function getNumberOfExpensesByCategoryThisMonth($categoryid,$idUser){
    try{
      $year = date('Y');
      $month = date('m');
      $query = $this->prepare("SELECT COUNT(*) AS total 
                              FROM expenses 
                              WHERE category_id = :category AND id_user = :id 
                                AND YEAR(date) = :year AND MONTH(date) = :month");
      $query->execute([
        'id' => $idUser, 
        'year' => $year,
        'month' => $month,
        'category' => $categoryid
      ]);
      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
      if($total == NULL){
        return 0;
      }
      return $total;
    }
    catch(Exception $e){
      error_log('getAll: failed -> Exception: '.$e->getMessage());
      return NULL;
    }
  }




}


?>