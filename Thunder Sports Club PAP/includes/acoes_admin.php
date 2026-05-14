<?php
session_start();
include 'ligamysql.php';

// Segurança: Apenas Admin N3 executa ações aqui
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 3) {
    die("Acesso negado.");
}

// AÇÃO 1: Mudar de Cargo
if (isset($_POST['id_user']) && isset($_POST['novo_cargo'])) {
    $id = $_POST['id_user'];
    $cargo = $_POST['novo_cargo'];
    
    $sql = "UPDATE users SET cargo = '$cargo' WHERE id_user = '$id'";
    if (mysqli_query($conexao, $sql)) {
        header("Location: ../admin_painel.php?msg=updated");
    }
}

// AÇÃO 2: Eliminar Utilizador
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Proteção: Não deixar o admin apagar-se a si próprio
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Erro: Não podes apagar a tua própria conta!'); window.location.href='../admin_painel.php';</script>";
        exit();
    }

    $sql = "DELETE FROM users WHERE id_user = '$id'";
    if (mysqli_query($conexao, $sql)) {
        header("Location: ../admin_painel.php?msg=deleted");
    }
}
?>