<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php'; // Como estão na mesma pasta, chama direto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = mysqli_real_escape_string($conexao, $_POST['user']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$user_input' OR username = '$user_input' LIMIT 1";
    $result = mysqli_query($conexao, $sql);

    if ($user = mysqli_fetch_assoc($result)) {
        // Verifica MD5 (usado no utilizador 'ze') ou Password Hash (Duarte)
        if (md5($password) == $user['password'] || password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['cargo'] = $user['cargo']; // Aqui guarda o 3 para o Duarte
            
            header("Location: dashboard.php");
            exit();
        }
    }
    echo "<script>alert('Dados inválidos'); window.history.back();</script>";
}
?>