<link rel="stylesheet" href="public/css/default.css">
<link rel="stylesheet" href="public/css/dashboard.css">


<div id="header">
    <ul>
        <li><a href="dashboard">Home</a></li>
        <li><a href="expenses">Expenses</a></li>
        <li>
            <a class='btn btn-warning  ml-1' href="<?php echo $_SERVER["REQUEST_URI"]?>/logout">Cerrar sesión</a>
        </li>
    </ul>
    <div id="profile-container">
        <a href="<?php echo constant('URL')?>user">
            <div class="name"><?php echo $user->getName() !== NULL ? $user->getName() : $user->getUsername();?></div>
            <div class="photo">
                <?php
                if($user->getPhoto() !== NULL){?>
                    <img src="<?php echo constant('URL');?>public/img/photos/<?php echo $user->getPhoto()?>" alt="">
                <?php }else{ ?>
                    <img src="<?php echo constant('URL')?>public/img/photos/default.jpg" alt="">
                <?php } ?>
                
            </div>
        </a>
        <div id="submenu">
            <ul>
                <li><a href="user">Ver perfil</a></li>
                <li class='divisor'></li>
                <li>
                    <a class='btn btn-warning  ml-1' href="<?php echo $_SERVER["REQUEST_URI"]?>/logout">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</div>