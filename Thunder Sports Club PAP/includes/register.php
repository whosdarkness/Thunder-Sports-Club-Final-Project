<?php
session_start();
include "ligamysql.php"; // Certifica-te de que este ficheiro define $connexao corretamente

if(isset($_POST['registar'])){
    $username  = $_POST['username'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $password2 = $_POST['password2'];

    // Confirmação de password
    if($password !== $password2){
        echo "❌ As passwords não coincidem.";
        exit;
    }

    $password_hashed = md5($password);

    // Verifica se o email já existe
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conexao->query($check_email);

    if($result->num_rows > 0){
        echo "Email já registado. Tente outro.";
    } else {
        $insertQuery = "INSERT INTO users (username, email, password) 
                        VALUES ('$username', '$email', '$password_hashed')";
        if($conexao->query($insertQuery) === TRUE){
            header("Location: login.php");
            exit;
        } else {
            echo "Erro no registo: " . $conexao->error;
        }
    }
}

// LOGIN (opcional, se estiver no mesmo ficheiro)
if(isset($_POST['login'])){
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $password_hashed = md5($password);

    $loginQuery = "SELECT * FROM users WHERE email='$email' AND password='$password_hashed'";
    $result = $conexao->query($loginQuery);

    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: index.php");
        exit;
    } else {
        echo "Email ou password incorretos.";
    }
}
?>
