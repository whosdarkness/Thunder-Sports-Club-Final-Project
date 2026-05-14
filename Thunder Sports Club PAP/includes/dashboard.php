<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';
mysqli_set_charset($conexao, "utf8mb4");

// Verificação de sessão
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Definição de variáveis de ambiente
$uid = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : $_SESSION['user_id'];
$cargo = $_SESSION['cargo'];
$username = $_SESSION['username'];

// Procurar modalidades para o formulário e para o horário
$res_modalidades = mysqli_query($conexao, "SELECT * FROM modalidade");

// Lógica para o Treinador pedir nova modalidade
if (isset($_POST['pedir_treino'])) {
    $id_mod = mysqli_real_escape_string($conexao, $_POST['id_mod']);
    $query = "INSERT INTO propostas_treino (id_user, id_mod, aprovado) VALUES ('$uid', '$id_mod', 0)";
    
    if(mysqli_query($conexao, $query)) {
        header("Location: dashboard.php?msg=pedido_enviado");
    } else {
        echo "Erro: " . mysqli_error($conexao);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <title>Thunder Hub - Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0a0a0a; color: white; padding: 40px; font-family: 'Segoe UI', sans-serif; }
        .gold { color: #ffcc00; }
        .card-custom { background: #141414; border-radius: 15px; padding: 25px; border: 1px solid #222; height: 100%; transition: 0.3s; }
        .card-custom:hover { border-color: #444; }
        .badge-nivel { padding: 8px 12px; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .btn-admin-main { background: #ffcc00; color: #000; font-weight: bold; border: none; }
        .btn-admin-main:hover { background: #e6b800; color: #000; }
        .modal-content { background: #141414; border: 1px solid #ffcc00; }
        .table-dark { --bs-table-bg: #1a1a1a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1>Bem-vindo, <span class="gold"><?php echo htmlspecialchars($username); ?></span></h1>
            <a href="logout.php" class="btn btn-danger px-4 fw-bold">Sair</a>
        </div>

        <p class="mb-5">O teu nível atual: 
            <span class="badge badge-nivel <?php echo ($cargo == 3) ? 'bg-warning text-dark' : (($cargo == 2) ? 'bg-primary' : 'bg-secondary'); ?>">
                <?php echo ($cargo == 3) ? "ADMINISTRADOR" : (($cargo == 2) ? "TREINADOR" : "SÓCIO"); ?>
            </span>
        </p>

        <div class="row g-4">
            <?php if($cargo == 3): ?>
                <div class="col-md-6">
                    <div class="card-custom shadow-sm" style="border-left: 5px solid #ffcc00;">
                        <h4 class="gold mb-4">Painel de Gestão</h4>
                        <div class="d-grid gap-3">
                            <a href="admin_painel.php" class="btn btn-admin-main">GERIR UTILIZADORES</a>
                            <a href="admin_horarios.php" class="btn btn-admin-main">GERIR HORÁRIOS</a>
                            <a href="admin_painel.php#pedidos" class="btn btn-outline-warning fw-bold">VER PEDIDOS DE TREINADOR</a>
                            <a href="admin_modalidades.php" class="btn btn-outline-warning">GERIR PREÇOS E MODALIDADES</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($cargo == 2): ?>
                <div class="col-md-6">
                    <div class="card-custom shadow-sm" style="border-left: 5px solid #007bff;">
                        <h4 class="text-primary mb-4">Área Técnica</h4>
                        <form method="POST">
                            <label class="small text-secondary mb-2">PEDIR AUTORIZAÇÃO PARA TREINAR:</label>
                            <div class="input-group shadow-sm">
                                <select name="id_mod" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="" disabled selected>Escolha a modalidade...</option>
                                    <?php 
                                    mysqli_data_seek($res_modalidades, 0);
                                    while($m = mysqli_fetch_assoc($res_modalidades)) {
                                        echo "<option value='{$m['id_mod']}'>".htmlspecialchars($m['nome_mod'])."</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="pedir_treino" class="btn btn-primary fw-bold">PEDIR</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-6">
                <div class="card-custom shadow-sm">
                    <h4 class="mb-4">Minha Atividade</h4>
                    <div class="d-grid gap-3">
                        <a href="inscrever.php" class="btn btn-outline-light py-2">Inscrição em Modalidade</a>
                        
                        <button type="button" class="btn btn-outline-info py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalHorario">
                            📅 CONSULTAR HORÁRIO SEMANAL
                        </button>

                        <a href="meus_dados.php" class="btn btn-outline-light py-2">Consultar Meus Dados</a>
                        
                        <?php if($cargo == 2): ?>
                            <a href="treinador_dados.php" class="btn btn-outline-warning py-2">Consultar Dados do Treinador</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHorario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-white bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title gold">HORÁRIO DAS MODALIDADES</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover text-center align-middle">
                        <thead>
                            <tr class="gold">
                                <th>Modalidade</th>
                                <th>Treino 1</th>
                                <th>Treino 2</th>
                                <th>Treino 3</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        mysqli_data_seek($res_modalidades, 0);

                        while($m = mysqli_fetch_assoc($res_modalidades)):

                            $id_mod = $m['id_mod'];

                            $res_horarios = mysqli_query($conexao, "
                                SELECT * FROM horarios
                                WHERE id_mod = '$id_mod'
                                ORDER BY FIELD(dia_semana, 'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo')
                                LIMIT 3
                            ");

                            $horarios = [];
                            while($h = mysqli_fetch_assoc($res_horarios)) {
                                $horarios[] = $h;
                            }
                        ?>

                        <tr>
                            <td class="fw-bold text-uppercase">
                                <?php echo htmlspecialchars($m['nome_mod']); ?>
                            </td>

                            <?php for($i=0; $i<3; $i++): ?>
                                <td>
                                    <?php if(isset($horarios[$i])): ?>
                                        <span class="badge bg-secondary mb-1">
                                            <?php echo $horarios[$i]['dia_semana']; ?>
                                        </span><br>
                                        <?php echo substr($horarios[$i]['hora_inicio'],0,5); ?>
                                        -
                                        <?php echo substr($horarios[$i]['hora_fim'],0,5); ?>
                                    <?php else: ?>
                                        <span class="text-secondary">Sem horário</span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>

                        <?php endwhile; ?>
                        </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Alerta de sucesso ao enviar pedido
        if(window.location.search.includes('msg=pedido_enviado')) {
            alert('Pedido de autorização enviado com sucesso!');
            window.history.replaceState({}, document.title, "dashboard.php");
        }
    </script>
</body>
</html>