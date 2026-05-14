<?php
session_start();
include 'ligamysql.php';

// Proteção: Só o Admin (Nível 3) executa isto
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 3) {
    die("Acesso negado.");
}

// ALTERAR CARGO
if (isset($_POST['btn_cargo'])) {
    $id = $_POST['id'];
    $novo_cargo = $_POST['novo_cargo'];
    
    $query = "UPDATE users SET cargo = '$novo_cargo' WHERE id_user = '$id'";
    mysqli_query($conexao, $query);
    header("Location: ../admin_painel.php?msg=sucesso");
}

// ELIMINAR UTILIZADOR
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Impede o admin de se apagar a si próprio
    if ($id == $_SESSION['user_id']) {
        header("Location: ../admin_painel.php?error=self_delete");
        exit();
    }

    $query = "DELETE FROM users WHERE id_user = '$id'";
    mysqli_query($conexao, $query);
    header("Location: ../admin_painel.php?msg=eliminado");
}
?>