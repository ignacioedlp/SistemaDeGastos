<?php

  require_once 'models/categoriesmodel.php';
  require_once 'models/usermodel.php';
  require_once 'models/expensesmodel.php';

  class Admin extends SessionController{


    function __construct(){
      parent::__construct();
      
    }

    function render() {
      $stats = $this->getStatistics();

      $this->view->render('admin/index', [
        'stats' => $stats
      ]);
    }

    function createCategory(){
      $this->view->render('admin/createCategory');
    }

    function newCategory(){
      if($this->existPost(['name', 'color'])){
        $name = $this->getPost('name');
        $color = $this->getPost('color');
        $categoriesModel = new categoriesModel();
        if(!$categoriesModel->exist($name)){
          $categoriesModel->setName($name);
          $categoriesModel->setColor($color);
          $categoriesModel->save();
          $this->redirect('admin', []); //TODO: Success message
        }else{
          $this->redirect('admin/createCategory', []); //TODO: Error message
        }
        $this->view->render('admin/createCategory');
      }
    }

    private function getMaxAmount($expenses){
      $max = 0;
      foreach($expenses as $expense){
        $max = max($max, $expense->getAmount());
      }

      return $max;
    }

    private function getMinAmount($expenses){
      $min = $this->getMaxAmount($expenses);
      foreach($expenses as $expense){
        $min = min($min, $expense->getAmount());
      }

      return $min;
    }

    private function getAverageAmount($expenses){
      $total = 0;
      foreach($expenses as $expense){
        $total += $expense->getAmount();
      }

      return $total / count($expenses);
    }

    private function getCategoryMostUsed($expenses){
      $repeat = [];
      foreach($expenses as $expense){
        if(!array_key_exists($expense->getCategoryId(), $repeat)){
          $repeat[$expense->getCategoryId()] = 0;
        }
        $repeat[$expense->getCategoryId()]++;
      }

      $categoryMostUsed = max($repeat);
      $categoryModel = new categoriesModel(); 
      $categoryModel->get($categoryMostUsed);
      $category = $categoryModel->getName();
      return $category;
    }

    private function getCategoryLessUsed($expenses){
      $repeat = [];
      foreach($expenses as $expense){
        if(!array_key_exists($expense->getCategoryId(), $repeat)){
          $repeat[$expense->getCategoryId()] = 0;
          
        }
        $repeat[$expense->getCategoryId()]++;
      }

      $categoryMostUsed = min($repeat);
      
      $categoryModel = new categoriesModel(); 
      $categoryModel->get($categoryMostUsed);
      $category = $categoryModel->getName();
      
      return $category;
    }



    function getStatistics(){

      $res = [];

      $userModel = new userModel();
      $users = $userModel->getAll();

      $categoriesModel = new categoriesModel();
      $categories = $categoriesModel->getAll();

      $expensesModel = new expensesModel();
      $expenses = $expensesModel->getAll();

      $res['count-users'] = count($users);
      $res['count-categories'] = count($categories);
      $res['count-expenses'] = count($expenses);

      $res['max-expenses'] = $this->getMaxAmount($expenses);
      $res['min-expenses'] = $this->getMinAmount($expenses);
      $res['average-expenses'] = $this->getAverageAmount($expenses);

      $res['category-most-used'] = $this->getCategoryMostUsed($expenses);
      $res['category-less-used'] = $this->getCategoryLessUsed($expenses);


      return $res;

    }



  }



?>