<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/default.css">
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
   <div id="header">
        <ul>
            <li><a href="dashboard">Home</a></li>
            <li><a href="nuevo">Budget</a></li>
            <li><a href="consulta">History</a></li>
            <li>
                <a class='btn btn-warning  ml-1' href="<?php echo $_SERVER["REQUEST_URI"]?>/logout">Cerrar sesión</a>
            </li>
        </ul>
    </div>

</body>
</html>