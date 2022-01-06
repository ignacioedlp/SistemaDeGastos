<?php
require_once 'models/expensesmodel.php';
require_once 'models/categoriesmodel.php';

class Dashboard extends SessionController{

  private $user;

  function __construct(){
    parent::__construct();
    $this->user = $this->getUserSessionData();
    
  }

  function render(){
    $expensesModel = new ExpensesModel();
    $expenses = $this->getExpenses(2);
    $totalThisMonth = $expensesModel->getTotalAmountThisMonth($this->user->getId());
    $maxExpensesThisMonth = $expensesModel->getMaxExpensesThisMonth($this->user->getId());
    $categories = $this->getCategories();
    $this->view->render('dashboard/index', [
      'user' => $this->user,
      'expenses' => $expenses,
      'totalThisMonth' => $totalThisMonth,
      'maxExpensesThisMonth' => $maxExpensesThisMonth,
      'categories' => $categories
    ]);
  }


  private function getExpenses($cant = 0){
    if($cant < 0){
      return NULL;
    }
    $expensesModel = new ExpensesModel();
    return $expensesModel->getByUserIdAndLimit($this->user->getId(), $cant);
  }

  private function getCategories(){
    $res = []; //
    $categoriesModel = new CategoriesModel();
    $expensesModel = new ExpensesModel();
    $categories = $categoriesModel->getAll();
    foreach($categories as $category){
      $categoryArray = []; //
      $total = $expensesModel->getTotalByCategoryThisMonth($category->getId(), $this->user->getId());
      $numberOfExpenses = $expensesModel->getNumberOfExpensesByCategoryThisMonth($category->getId(), $this->user->getId());

      if($numberOfExpenses > 0){ 
        $categoryArray['total'] = $total;
        $categoryArray['count'] = $numberOfExpenses;
        $categoryArray['category'] = $category;
        array_push($res, $categoryArray);
      }
    }
   
    return $res;
  }



}




?>