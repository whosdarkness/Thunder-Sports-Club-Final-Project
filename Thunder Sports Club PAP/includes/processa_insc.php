<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_nome = mysqli_real_escape_string($conexao, $_POST['p_nome']);
    $u_nome = mysqli_real_escape_string($conexao, $_POST['u_nome']);
    $nome_completo = $p_nome . " " . $u_nome;
    
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
    $nif = mysqli_real_escape_string($conexao, $_POST['nif']);
    $data_nasc = $_POST['data_nasc'];
    $id_mod = intval($_POST['id_mod']);

    $sql_socio = "INSERT INTO socios (nome_socio, email_socio, telefone_socio, cont_socio, data_nasc_socio) 
                  VALUES ('$nome_completo', '$email', '$telefone', '$nif', '$data_nasc')
                  ON DUPLICATE KEY UPDATE nome_socio='$nome_completo', email_socio='$email'";
    
    if (mysqli_query($conexao, $sql_socio)) {
        $res_id = mysqli_query($conexao, "SELECT num_socio FROM socios WHERE cont_socio = '$nif'");
        $socio_data = mysqli_fetch_assoc($res_id);
        $user_id = $socio_data['num_socio'];
        
        $_SESSION['user_id'] = $user_id;

        $sql_ins = "INSERT INTO inscricao_mod_socio (num_socio, id_mod, data_registo, pago) 
                    VALUES ('$user_id', '$id_mod', NOW(), 'Não')
                    ON DUPLICATE KEY UPDATE id_mod='$id_mod'"; 
        
        mysqli_query($conexao, $sql_ins);

        // REDIRECIONAMENTO CORRIGIDO (SEM INCLUDES/)
        header("Location: pagamento.php?id_mod=$id_mod");
        exit();
    } else {
        echo "Erro: " . mysqli_error($conexao);
    }
}
?>