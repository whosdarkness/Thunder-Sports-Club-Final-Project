<?php

$host = "localhost";
$user = "root";
$pass = "12345"; 
$db   = "clube_desp";

$conexao = mysqli_connect($host, $user, $pass, $db);

if (!$conexao) {
    die("Erro na ligação: " . mysqli_connect_error());
}
?>