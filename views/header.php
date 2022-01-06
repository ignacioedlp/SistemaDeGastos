<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
         <a class="nav-link" href="<?php echo constant('URL')?>">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo constant('URL')?>user">Perfil</a>
        </li>
        <li class="nav-item">
           <a class='btn btn-warning  ml-1' href="<?php echo $_SERVER["REQUEST_URI"]?>/logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
