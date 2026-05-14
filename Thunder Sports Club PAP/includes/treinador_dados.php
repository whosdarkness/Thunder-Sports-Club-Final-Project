<?php
session_start();
include 'ligamysql.php';

if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : $_SESSION['user_id'];
$cargo = $_SESSION['cargo'] ?? 0;

if ($cargo < 2) {
    header("Location: dashboard.php");
    exit();
}

// Deixar de treinar uma modalidade
if (isset($_GET['sair_mod'])) {
    $id_mod = mysqli_real_escape_string($conexao, $_GET['sair_mod']);
    mysqli_query($conexao, "DELETE FROM propostas_treino WHERE id_user = '$user_id' AND id_mod = '$id_mod' AND aprovado = 1");
    
    // Verificar se tem mais modalidades
    $res_check = mysqli_query($conexao, "SELECT COUNT(*) as cnt FROM propostas_treino WHERE id_user = '$user_id' AND aprovado = 1");
    $check = mysqli_fetch_assoc($res_check);
    
    // Se não tem mais modalidades, voltar a sócio
    if($check['cnt'] == 0) {
        mysqli_query($conexao, "UPDATE users SET cargo = 1 WHERE id_user = '$user_id'");
    }
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

// Perfil do treinador
$res_trainer = mysqli_query($conexao, "SELECT username, email FROM users WHERE id_user = '$user_id'");
$trainer = mysqli_fetch_assoc($res_trainer);

// Modalidades atribuídas ao treinador (aprovadas)
$modalidades_res = mysqli_query($conexao, "SELECT DISTINCT p.id_mod, m.nome_mod
                                      FROM propostas_treino p
                                      JOIN modalidade m ON p.id_mod = m.id_mod
                                      WHERE p.id_user = '$user_id' AND p.aprovado = 1");
$modalidades = [];
$modalidade_ids = [];
while ($row = mysqli_fetch_assoc($modalidades_res)) {
    $modalidades[] = $row;
    $modalidade_ids[] = $row['id_mod'];
}

$trainees = [];
if (!empty($modalidade_ids)) {
    $ids = implode(',', array_map('intval', $modalidade_ids));
    $trainees_res = mysqli_query($conexao, "SELECT DISTINCT s.nome_socio, m.nome_mod, i.pago
                                           FROM inscricao_mod_socio i
                                           JOIN modalidade m ON i.id_mod = m.id_mod
                                           JOIN socios s ON i.num_socio = s.num_socio
                                           WHERE i.id_mod IN ($ids)
                                           ORDER BY m.nome_mod, s.nome_socio");
    while ($row = mysqli_fetch_assoc($trainees_res)) {
        $trainees[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Dados do Treinador - Thunder Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #090a0d;
            --surface: #141414;
            --surface-strong: #1a1a1a;
            --border: #222;
            --text: #f4f4f9;
            --muted: #8b8f9b;
            --gold: #ffcc00;
            --gold-soft: rgba(255, 204, 0, 0.12);
        }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        .main-container {
            max-width: 1180px;
            margin: auto;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .page-header h2 {
            font-size: 2.3rem;
            letter-spacing: -1px;
            margin: 0;
            font-weight: 800;
            text-transform: uppercase;
        }
        .page-header h2 span {
            color: var(--gold);
        }
        .page-header p {
            color: var(--muted);
            margin: 8px 0 0;
            font-size: 0.95rem;
        }
        .btn-voltar {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.25s ease;
            text-transform: uppercase;
        }
        .btn-voltar:hover {
            background: var(--gold);
            color: #000;
            border-color: var(--gold);
        }
        .card-custom {
            background: var(--surface);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.14);
        }
        .section-title {
            color: var(--gold);
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 24px;
            display: block;
            letter-spacing: 1px;
        }
        .info-label {
            color: var(--muted);
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .info-value {
            color: var(--text);
            font-size: 1.05rem;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 12px;
            font-weight: 500;
        }
        .training-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .badge-trained {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            background: var(--gold-soft);
            color: var(--gold);
            border: 1px solid rgba(255, 204, 0, 0.3);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        .btn-sair-mod {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 8px;
            background: rgba(255, 68, 68, 0.15);
            color: #ff6666;
            border: 1px solid #ff4444;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 10px;
        }
        
        .btn-sair-mod:hover {
            background: rgba(255, 68, 68, 0.25);
            text-decoration: none;
            color: #ff6666;
        }
        .text-muted-small {
            color: var(--muted);
            font-size: 0.95rem;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table {
            color: var(--text);
            border-collapse: separate;
            border-spacing: 0 12px;
            width: 100%;
        }
        .table thead th {
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: transparent;
            border: none;
            padding: 16px 20px;
            vertical-align: middle;
        }
        .table tbody tr {
            background: var(--surface-strong);
            border: none;
            border-radius: 12px;
        }
        .table tbody td {
            border: none;
            padding: 20px;
            vertical-align: middle;
            background: var(--surface-strong);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        .table tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
            font-weight: 600;
            color: #f1f1f1;
        }
        .table tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid;
        }
        .status-pago { 
            background: rgba(0, 255, 127, 0.15);
            color: #00ff7f;
            border-color: #00ff7f;
        }
        .status-pendente { 
            background: rgba(255, 68, 68, 0.15);
            color: #ff6666;
            border-color: #ff4444;
        }
        .table-empty {
            padding: 60px 20px;
            color: var(--muted);
            text-align: center;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="page-header">
        <div>
            <h2>Dados do <span>Treinador</span></h2>
            <p class="text-muted-small">Consulta quem estás a treinar e em que modalidade.</p>
        </div>
        <a href="dashboard.php" class="btn-voltar">Voltar ao Painel</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-custom">
                <span class="section-title">Perfil do Treinador</span>
                <div class="info-label">Nome de Utilizador</div>
                <div class="info-value"><?php echo htmlspecialchars($trainer['username'] ?? 'Treinador'); ?></div>
                <div class="info-label">Email</div>
                <div class="info-value"><?php echo htmlspecialchars($trainer['email'] ?? 'N/A'); ?></div>
                <div class="info-label">Modalidades atribuídas</div>
                <div class="training-badges">
                    <?php if (!empty($modalidades)): ?>
                        <?php foreach ($modalidades as $mod): ?>
                            <div style="display: flex; align-items: center;">
                                <span class="badge-trained"><?php echo htmlspecialchars($mod['nome_mod']); ?></span>
                                <a href="?sair_mod=<?php echo $mod['id_mod']; ?>" class="btn-sair-mod" onclick="return confirm('Deseja deixar de treinar esta modalidade?')">Sair</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted-small">Ainda não tens modalidades aprovadas.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-custom">
                <span class="section-title">Sócios que estás a treinar</span>
                <?php if (!empty($trainees)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Modalidade</th>
                                <th>Sócio</th>
                                <th>Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trainees as $trainee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($trainee['nome_mod']); ?></td>
                                <td><?php echo htmlspecialchars($trainee['nome_socio']); ?></td>
                                <td>
                                    <?php $status = in_array(strtolower(trim($trainee['pago'])), ['1','sim','true','yes'], true); ?>
                                    <span class="status-pill <?php echo $status ? 'status-pago' : 'status-pendente'; ?>">
                                        <?php echo $status ? 'PAGO' : 'PENDENTE'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="table-empty">Não há sócios inscritos nas tuas modalidades aprovadas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
