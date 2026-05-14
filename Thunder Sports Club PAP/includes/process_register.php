<?php
include "ligamysql.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Une Nome e Apelido para o Username
    $username = mysqli_real_escape_string($conexao, $_POST['fname'] . " " . $_POST['lname']);
    $email    = mysqli_real_escape_string($conexao, $_POST['email']);
    $password = $_POST['password'];

    // Verificação de segurança (backup do JavaScript)
    if ($password !== $_POST['confirm_password']) {
        echo "<script>alert('Erro: As passwords não coincidem.'); window.history.back();</script>";
        exit;
    }

    // Criar hash segura (substitui o MD5 antigo)
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Verifica se o email já existe na tabela 'users'
    $check_email = mysqli_query($conexao, "SELECT id_user FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('Este email já está registado! Tente fazer login.'); window.history.back();</script>";
    } else {
        // Insere como Nível 1 (Sócio) por defeito
        $sql = "INSERT INTO users (username, email, password, cargo) VALUES ('$username', '$email', '$password_hashed', 1)";
        
        if (mysqli_query($conexao, $sql)) {
            echo "<script>alert('Conta criada com sucesso!'); window.location.href='login.php';</script>";
        } else {
            echo "Erro no banco de dados: " . mysqli_error($conexao);
        }
    }
}
?>