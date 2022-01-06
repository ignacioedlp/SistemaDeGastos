<?php

  error_reporting(E_ALL);

  ini_set('ignore_repeated_errors', true);

  ini_set('display_errors', false);

  ini_set('log_errors', true);

  ini_set('error_log', 'error.log');

  error_log('Start!');


  require_once 'libs/database.php';
  require_once 'libs/controller.php';
  require_once 'libs/model.php';
  require_once 'libs/view.php';
  require_once 'libs/app.php';
  
  require_once 'config/config.php';

  require_once 'classes/errormessages.php';
  require_once 'classes/successmessages.php';
  require_once 'classes/session.php';
  require_once 'classes/sessionController.php';


  $app = new App();
?>