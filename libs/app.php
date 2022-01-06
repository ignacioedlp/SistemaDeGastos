<?php

require_once 'controllers/errors.php';


class App{



  function __construct(){

    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = explode('/', $url);

    if(empty($url[0])){
      $archivoController = 'controllers/login.php';
      require $archivoController;
      $controller = new Login();
      $controller->loadModel('login');
      $controller->render();
      return false;
    }

    $archivoController = 'controllers/' . $url[0] . '.php';

    if(file_exists($archivoController)){
      //si existe el controlador
      require_once $archivoController;
      $controller = new $url[0];
      $controller->loadModel($url[0]);

      if(isset($url[1])){
        //si hay un metodo
        if(method_exists($controller, $url[1])){                       //$url[0] = controller
          //si existe ese metodo                                      
          if(isset($url[2])){                                         //$url[1] = metodo
            //si hay parametros                                      //$url[2] = params
                                                                 
            $nparams = sizeof($url) - 2;
            
            $params = [];
            for($i = 0; $i < $nparams; $i++){
              array_push($params, $url[$i + 2]);
            }
            $controller->{$url[1]}($params);  
          }else{
            //si no hay parametros
            $controller->{$url[1]}();
          }
        }else{
          //si no existe el metodo
          $controller = new Errors();
        }
      }else{
        $controller->render();
      }
    }else{
      //si no existe el controlador
      $controller = new Errors();
    }

  }


  

}


?>