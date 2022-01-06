<?php

    $categories = $this->d['categories'];
    $user = $this->d['user'];

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo constant('URL') ?>public/css/default.css">
</head>
<body>
    <?php require_once 'views/dashboard/header.php'; ?>
    <form id="form-expense-container" action="<?php echo constant('URL')?>/expenses/newExpense" method="POST">
    <h3>Registrar nuevo gasto</h3>
    <div class="section">
        <label for="amount">Cantidad</label>
        <input type="number" name="amount" id="amount" autocomplete="off" required>
    </div>
    <div class="section">
        <label for="title">Descripción</label>
        <div><input type="text" name="title" autocomplete="off" required></div>
    </div>
    
    <div class="section">
        <label for="date">Fecha de gasto</label>
        <input type="date" name="date" id="" required>
    </div>    

    <div class="section">
        <label for="categoria">Categoria</label>
            <select name="category" id="" required>
                <option value="">Seleccione una categoria</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category->getId() ?>"><?php echo $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
    </div>    

    <div class="center">
        <input type="submit" value="Nuevo expense">
    </div>
</form>
</body>
</html>


