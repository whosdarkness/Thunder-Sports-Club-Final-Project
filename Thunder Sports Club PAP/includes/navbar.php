<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="home.php">Menu</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      
      <!-- Espaço vazio para empurrar o botão para a direita -->
      <ul class="navbar-nav me-auto"></ul>

      <!-- Botão Login -->
      <a href="login.php" class="btn btn-outline-light">Login</a>
      <a href="registrar_user.php" class="btn btn-outline-light">Registrar</a>
    </div>
  </div>
</nav>
