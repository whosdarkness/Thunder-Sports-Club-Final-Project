<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';
mysqli_set_charset($conexao, "utf8mb4");

// Verifica se é admin (Cargo 3)
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 3) { 
    header("Location: dashboard.php"); exit(); 
}

// LÓGICA DE APROVAÇÃO DE TREINADORES
if (isset($_GET['aprovar_id'])) {
    $id_p = mysqli_real_escape_string($conexao, $_GET['aprovar_id']);
    mysqli_query($conexao, "UPDATE propostas_treino SET aprovado = 1 WHERE id_proposta = '$id_p'");
    header("Location: admin_painel.php"); exit();
}

// LÓGICA DE ALTERAÇÃO DE CARGOS
if (isset($_POST['btn_cargo'])) {
    $id_u = mysqli_real_escape_string($conexao, $_POST['id']);
    $n_cargo = mysqli_real_escape_string($conexao, $_POST['novo_cargo']);
    mysqli_query($conexao, "UPDATE users SET cargo = '$n_cargo' WHERE id_user = '$id_u'");
    header("Location: admin_painel.php"); exit();
}

$res_pedidos = mysqli_query($conexao, "SELECT p.id_proposta, u.username, m.nome_mod FROM propostas_treino p JOIN users u ON p.id_user = u.id_user JOIN modalidade m ON p.id_mod = m.id_mod WHERE p.aprovado = 0");
$res_users = mysqli_query($conexao, "SELECT * FROM users ORDER BY cargo DESC");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Painel Admin - Thunder Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0a0a0a; color: #fff; padding: 40px; }
        .gold { color: #ffcc00; }
        .card-section { background: #141414; border: 1px solid #222; border-radius: 15px; padding: 30px; margin-bottom: 30px; }
        .table { --bs-table-bg: transparent; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bem-vindo, <span class="gold">Admin</span></h2>
            <div>
                <a href="admin_horarios.php" class="btn btn-warning fw-bold me-2">📅 GERIR HORÁRIOS</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>

        <div class="card-section">
            <h5 class="gold mb-3">Pedidos Pendentes (Treinadores)</h5>
            <table class="table table-dark table-hover">
                <thead>
                    <tr><th>Utilizador</th><th>Modalidade</th><th class="text-end">Ação</th></tr>
                </thead>
                <tbody>
                <?php if(mysqli_num_rows($res_pedidos) > 0): ?>
                    <?php while($p = mysqli_fetch_assoc($res_pedidos)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['username']); ?></td>
                        <td><?php echo htmlspecialchars($p['nome_mod']); ?></td>
                        <td class="text-end">
                            <a href="?aprovar_id=<?php echo $p['id_proposta']; ?>" class="btn btn-success btn-sm">Aprovar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-muted">Nenhum pedido pendente.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="card-section">
            <h5 class="mb-3">Utilizadores do Sistema</h5>
            <table class="table table-dark table-hover">
                <thead>
                    <tr><th>Username</th><th>Cargo Atual</th><th>Alterar Cargo</th></tr>
                </thead>
                <tbody>
                <?php while($u = mysqli_fetch_assoc($res_users)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                    <td>
                        <?php 
                            if($u['cargo'] == 3) echo '<span class="badge bg-danger">Admin</span>';
                            elseif($u['cargo'] == 2) echo '<span class="badge bg-primary">Treinador</span>';
                            else echo '<span class="badge bg-secondary">Sócio</span>';
                        ?>
                    </td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="id" value="<?php echo $u['id_user']; ?>">
                            <select name="novo_cargo" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="1" <?php if($u['cargo']==1) echo 'selected'; ?>>Sócio</option>
                                <option value="2" <?php if($u['cargo']==2) echo 'selected'; ?>>Treinador</option>
                                <option value="3" <?php if($u['cargo']==3) echo 'selected'; ?>>Admin</option>
                            </select>
                            <button type="submit" name="btn_cargo" class="btn btn-warning btn-sm ms-2">Alterar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>