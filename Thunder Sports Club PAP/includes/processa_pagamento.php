<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $id_mod  = intval($_POST['id_mod']);

    // ATUALIZA O ESTADO: Muda de 0 para 1 na base de dados
    $sql_update = "UPDATE inscricao_mod_socio SET pago = 1 
                   WHERE num_socio = '$user_id' AND id_mod = '$id_mod'";
    
    if (mysqli_query($conexao, $sql_update)) {
        echo "<script>alert('Pagamento Concluído com Sucesso!'); window.location.href='meus_dados.php';</script>";
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conexao);
    }
}
?>