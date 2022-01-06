<?php


class JoinExpensesCategoriesModel extends Model{

  private $expensesId; //
  private $categoriesId; //
  private $title; //
  private $amount; //
  private $date; 
  private $userid;
  private $nameCategory;
  private $color;

  function __construct(){
    parent::__construct();
  }

  function from($array){
    $this->expensesId   = $array['expense_id'];
    $this->categoriesId = $array['category_id'];
    $this->title        = $array['title'];
    $this->amount       = $array['amount'];
    $this->date         = $array['date'];
    $this->userid       = $array['id_user'];
    $this->nameCategory = $array['category_name'];
    $this->color        = $array['color'];
  }

  public function getAll($user){
    $items = [];
    try{
      $query = $this->prepare('SELECT expenses.id as expense_id, title, categories.name as category_name, amount, date, id_user, categories.id as category_id, name, color 
                              FROM expenses INNER JOIN categories 
                              WHERE expenses.category_id = categories.id AND id_user = :user ORDER BY date DESC');
      $query->execute([
        'user' => $user
      ]);
      while($p = $query->fetch(PDO::FETCH_ASSOC)){
        $item = new JoinExpensesCategoriesModel();
        $item->from($p);
        array_push($items, $item);
      }
      return $items;
  
  }
    catch(Exception $e){
      error_log('Error: '.$e->getMessage());
      return false;
    }
  }


  function toArray(){
    return [
      'expensesId'   => $this->expensesId,
      'categoriesId' => $this->categoriesId,
      'title'        => $this->title,
      'amount'       => $this->amount,
      'date'         => $this->date,
      'userid'       => $this->userid,
      'nameCategory' => $this->nameCategory,
      'color'        => $this->color
    ];
  }

  

  // Getters 
  function getExpensesId(){return $this->expensesId;}
  function getCategoriesId(){return $this->categoriesId;}
  function getTitle(){return $this->title;}
  function getAmount(){return $this->amount;}
  function getDate(){return $this->date;}
  function getUserid(){return $this->userid;}
  function getNameCategory(){return $this->nameCategory;}
  function getColor(){return $this->color;}

  // Setters
  function setExpensesId($expensesId){$this->expensesId = $expensesId;}
  function setCategoriesId($categoriesId){$this->categoriesId = $categoriesId;}
  function setTitle($title){$this->title = $title;}
  function setAmount($amount){$this->amount = $amount;}
  function setDate($date){$this->date = $date;}
  function setUserid($userid){$this->userid = $userid;}
  function setNameCategory($nameCategory){$this->nameCategory = $nameCategory;}
  function setColor($color){$this->color = $color;}






}



?>