<?php

require_once 'models/expensesmodel.php';
require_once 'models/joinexpensescategoriesmodel.php';
require_once 'models/categoriesmodel.php';

class Expenses extends SessionController{

  private $user; //

  public function __construct(){
    parent::__construct();
    $this->user = $this->getUserSessionData();
  }

  public function render(){
    $this->view->render('expenses/index', [
      'user' => $this->user, 
      'dates' => $this->getDateList(),
      'categories' => $this->getCategoryNameList()	
    ]);
  }

  public function newExpense(){
    if(!$this->existPOST(['title', 'amount', 'category', 'date'])){
     
      $this->redirect('dashboard', []); //TODO: error
      return;
    }
    if($this->user == NULL){
      
      $this->redirect('dashboard', []); //TODO: error
      return;
    }

    $expenses = new expensesModel();

    $expenses->setTitle($this->getPOST('title'));
    $expenses->setAmount((float)$this->getPOST('amount'));
    $expenses->setCategoryid($this->getPOST('category'));
    $expenses->setDate($this->getPOST('date'));
    $expenses->setUserid($this->user->getId());
    

    $expenses->save();
    $this->redirect('dashboard', []); //TODO: success

  }

  public function create(){
    
    $categories = new categoriesModel();
    $this->view->render('expenses/create', [
      'categories' => $categories->getAll(), 
      'user' => $this->user
    ]);
    
  }

  function getCategoriesId(){
    $joinModel = new joinExpensesCategoriesModel();
    $categories = $joinModel->getAll($this->user->getId());
    $res = [];
    foreach($categories as $category){
      $res[] = $category->getCategoriesId();
    }

    $res = array_values(array_unique($res));
    return $res;
  }

  private function getDateList(){
    $months = []; //
    $res = [];
    $joinmodel = new joinExpensesCategoriesModel();
    $expenses = $joinmodel->getAll($this->user->getId());
    foreach($expenses as $expense){
      array_push($months, substr($expense->getDate(), 0, 7));
    }
    $months = array_values(array_unique($months));

    foreach($months as $month){
      array_push($res, $month);
    }
    // if(count($months) > 3){
    //   array_push($res, array_pop($months));
    //   array_push($res, array_pop($months));
    //   array_push($res, array_pop($months));
    // }

    return $res;
  }

  function getCategoryNameList(){
    $res = [];
    $joinmodel = new joinExpensesCategoriesModel();
    $expenses = $joinmodel->getAll($this->user->getId());

    foreach($expenses as $expense){
      array_push($res, $expense->getNameCategory());
    }
    $res = array_values(array_unique($res));
    return $res;
  }

  function getCategoryColorList(){
    $res = [];
    $joinmodel = new joinExpensesCategoriesModel();
    $expenses = $joinmodel->getAll($this->user->getId());
    foreach($expenses as $expense){
      array_push($res, $expense->getColor());
    }
    $res = array_unique($res);
    $res = array_values(array_unique($res));
    return $res;
  }

  function getHistoryJSON(){
    
    header('Content-Type: application/json');
    $res = [];
    $joinmodel = new joinExpensesCategoriesModel();
    $expenses = $joinmodel->getAll($this->user->getId());
    foreach($expenses as $expense){
      array_push($res, $expense->toArray());
    }
    
    echo json_encode($res);
  }

  function getExpensesJSON(){
    header('Content-Type: application/json');
    error_log('EXPENSES::getHistoryJSON->INICIO');
        $res = [];
        $categoryIds     = $this->getCategoriesId();
        $categoryNames  = $this->getCategoryNameList();
        $categoryColors = $this->getCategoryColorList();

        array_unshift($categoryNames, 'mes');
        array_unshift($categoryColors, 'categorias');
        /* array_unshift($categoryNames, 'categorias');
        array_unshift($categoryColors, NULL); */

        $months = $this->getDateList();

        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($categoryIds); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $categoryIds[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }

        array_unshift($res, $categoryNames);
        array_unshift($res, $categoryColors);
        
        
        echo json_encode($res);
  }


  private function getTotalByMonthAndCategory($month, $categoryId){
    $iduser = $this->user->getId();
    $expenses = new expensesModel();
    $total = $expenses->getTotalByMonthAndCategory($month, $categoryId, $iduser);
    if($total == NULL){
      $total = 0;
    }
    return $total;
  }

  function delete($params){
    if($params === NULL){
      $this->redirect('expenses', []); //TODO: error
      return;
    }
    $id = $params[0];
    $res = $this->model->delete($id);
    if($res){
      $this->redirect('expenses', []); //TODO: success
    }else{
      $this->redirect('expenses', []); //TODO: error
    }
  }

}


?>