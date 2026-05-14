<?php
session_start();
include 'ligamysql.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cargo = $_SESSION['cargo'];

// 1. Puxar dados do utilizador e do sócio ligado
$query = "SELECT u.*, s.* FROM users u 
          LEFT JOIN socios s ON u.num_socio = s.num_socio 
          WHERE u.id_user = '$user_id'";
$res = mysqli_query($conexao, $query);
$dados = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Painel Thunder - <?php echo $dados['username']; ?></title>
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #121212; color: white; }
        .card-custom { background: #1f1f1f; border: 1px solid #333; border-radius: 15px; }
        .gold-text { color: #ffcc00; }
        .btn-thunder { background: #ffcc00; color: black; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Bem-vindo, <span class="gold-text"><?php echo $dados['username']; ?></span></h2>
            <p class="text-white-50">Nível de Acesso: 
                <?php 
                    if($cargo == 2) echo "Administrador";
                    elseif($cargo == 1) echo "Treinador";
                    else echo "Sócio";
                ?>
            </p>
        </div>
        <div class="col-auto">
            <a href="logout.php" class="btn btn-outline-danger">Sair</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom p-4 h-100">
                <h5 class="gold-text mb-3"><i class="bi bi-person-circle"></i> Meus Dados</h5>
                <p class="small mb-1 text-white-50">Email:</p>
                <p><?php echo $dados['email']; ?></p>
                <p class="small mb-1 text-white-50">Telemóvel:</p>
                <p><?php echo $dados['telefone_socio'] ?? 'Não registado'; ?></p>
                <button class="btn btn-sm btn-outline-light w-100 mt-3">Editar Perfil</button>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom p-4 h-100">
                
                <?php if($cargo == 0): // VISÃO DO SÓCIO ?>
                    <h5 class="gold-text mb-4"><i class="bi bi-trophy"></i> Minhas Modalidades</h5>
                    <div class="list-group list-group-flush">
                        <?php
                        $query_mod = "SELECT m.nome_mod FROM inscricao_mod_socio i 
                                      JOIN modalidade m ON i.id_mod = m.id_mod 
                                      WHERE i.num_socio = '".$dados['num_socio']."'";
                        $res_mod = mysqli_query($conexao, $query_mod);
                        if(mysqli_num_rows($res_mod) > 0){
                            while($m = mysqli_fetch_assoc($res_mod)){
                                echo "<div class='list-group-item bg-transparent text-white border-secondary'><i class='bi bi-check2-circle gold-text me-2'></i>".$m['nome_mod']."</div>";
                            }
                        } else {
                            echo "<p class='text-white-50'>Ainda não estás inscrito em nenhuma modalidade.</p>";
                        }
                        ?>
                    </div>
                    <a href="inscrever.php" class="btn btn-thunder mt-4">Inscrever em Nova Modalidade</a>

                <?php elseif($cargo == 1): // VISÃO DO TREINADOR ?>
                    <h5 class="gold-text mb-4"><i class="bi bi-people"></i> Meus Alunos</h5>
                    <p>Aqui aparecerá a lista de sócios inscritos nas modalidades que treinas.</p>
                    <table class="table table-dark table-hover">
                        <thead><tr><th>Nome</th><th>Modalidade</th></tr></thead>
                        <tbody><tr><td>Exemplo Aluno</td><td>Futebol</td></tr></tbody>
                    </table>

                <?php elseif($cargo == 2): // VISÃO DO ADMIN ?>
                    <h5 class="gold-text mb-4"><i class="bi bi-shield-lock"></i> Painel de Controlo</h5>
                    <div class="row g-3">
                        <div class="col-6"><a href="gerir_socios.php" class="btn btn-outline-light w-100 py-3">Gerir Sócios</a></div>
                        <div class="col-6"><a href="gerir_pagamentos.php" class="btn btn-outline-light w-100 py-3">Pagamentos</a></div>
                        <div class="col-6"><a href="estatisticas.php" class="btn btn-outline-light w-100 py-3">Estatísticas</a></div>
                        <div class="col-6"><a href="config_site.php" class="btn btn-outline-light w-100 py-3">Configurações</a></div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

</body>
</html>