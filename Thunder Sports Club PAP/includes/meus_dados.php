<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

// Verifica se existe sessão
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);


$nome = "Utilizador sem perfil";
$email = "N/A";
$telefone = "Não registado";
$num_socio = null;

// 1. Tenta buscar como utilizador normal
$res_perfil = mysqli_query($conexao, "SELECT u.*, s.* FROM users u LEFT JOIN socios s ON u.num_socio = s.num_socio WHERE u.id_user = '$user_id'");
if ($res_perfil && mysqli_num_rows($res_perfil) > 0) {
    $perfil = mysqli_fetch_assoc($res_perfil);
    $nome = $perfil['nome_socio'] ?: $perfil['username'] ?: $nome;
    $email = $perfil['email_socio'] ?: $perfil['email'] ?: $email;
    $telefone = $perfil['telefone_socio'] ?: $telefone;
    // Se não houver num_socio, tenta buscar pelo email
    if (!empty($perfil['num_socio'])) {
        $num_socio = $perfil['num_socio'];
    } else {
        // Procura na tabela socios pelo mesmo email
        $email_lookup = mysqli_real_escape_string($conexao, $perfil['email'] ?: $perfil['email_socio']);
        $res_soc = mysqli_query($conexao, "SELECT num_socio FROM socios WHERE email_socio = '$email_lookup' LIMIT 1");
        if ($res_soc && mysqli_num_rows($res_soc) > 0) {
            $soc = mysqli_fetch_assoc($res_soc);
            $num_socio = $soc['num_socio'];
        }
    }
} else {
    // 2. Se não existir em users, tenta como sócio direto
    $res_socio = mysqli_query($conexao, "SELECT * FROM socios WHERE num_socio = '$user_id'");
    if ($res_socio && mysqli_num_rows($res_socio) > 0) {
        $perfil = mysqli_fetch_assoc($res_socio);
        $nome = $perfil['nome_socio'] ?: $nome;
        $email = $perfil['email_socio'] ?: $email;
        $telefone = $perfil['telefone_socio'] ?: $telefone;
        $num_socio = $perfil['num_socio'];
        // Tenta encontrar um utilizador ligado a este sócio
        $res_user_link = mysqli_query($conexao, "SELECT username, email FROM users WHERE num_socio = '{$perfil['num_socio']}' LIMIT 1");
        if ($res_user_link && mysqli_num_rows($res_user_link) > 0) {
            $user_link = mysqli_fetch_assoc($res_user_link);
            $nome = $user_link['username'] ?: $nome;
            $email = $user_link['email'] ?: $email;
        }
    }
}

// Busca Inscrições e, quando existir, o(s) treinador(es) aprovados para a modalidade
if ($num_socio) {
    $sql_insc = "SELECT i.num_insc, m.nome_mod, i.pago,
                 (SELECT GROUP_CONCAT(DISTINCT u.username SEPARATOR ', ')
                  FROM propostas_treino p
                  JOIN users u ON p.id_user = u.id_user
                  WHERE p.id_mod = i.id_mod AND p.aprovado = 1
                 ) AS treinadores
                 FROM inscricao_mod_socio i
                 JOIN modalidade m ON i.id_mod = m.id_mod
                 WHERE i.num_socio = '$num_socio'";
    $res_insc = mysqli_query($conexao, $sql_insc);
} else {
    $res_insc = false;
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Meus Dados - Thunder Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --gold: #ffcc00; --dark-bg: #0a0a0a; --card-bg: #111111; }
        body { background-color: var(--dark-bg); color: #ffffff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .main-container { max-width: 1100px; margin: auto; }
        .card-custom { background: var(--card-bg); border-radius: 15px; padding: 30px; border: 1px solid #222; height: 100%; }
        h2 { font-weight: 800; letter-spacing: -1px; margin-bottom: 30px; }
        .section-title { color: var(--gold); font-size: 0.9rem; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; display: block; }
        .info-label { color: #555; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { color: #fff; font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid #222; padding-bottom: 5px; }
        .table { color: #fff !important; --bs-table-bg: transparent; margin-bottom: 0; }
        .table td { padding: 20px 0; border-bottom: 1px solid #222; vertical-align: middle; color: #fff !important; }
        .table th { color: #555; font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid #333; }
        .status-badge { padding: 6px 15px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; border: 1px solid; }
        .status-pago { background: rgba(0, 255, 150, 0.1); color: #00ff96; border-color: #00ff96; }
        .status-pendente { background: rgba(255, 50, 50, 0.1); color: #ff3232; border-color: #ff3232; }
        .btn-voltar { background: transparent; color: #fff; border: 1px solid #333; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: 0.3s; }
        .btn-voltar:hover { background: #fff; color: #000; }
    </style>
</head>
<body>

<div class="main-container">
    <div class="d-flex justify-content-between align-items-center">
        <h2>MEUS <span style="color: var(--gold);">DADOS</span></h2>
        <a href="dashboard.php" class="btn-voltar">VOLTAR AO PAINEL</a>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card-custom">
                <span class="section-title">Perfil</span>
                
                <div class="info-label">Nome Completo</div>
                <div class="info-value"><?php echo $nome; ?></div>

                <div class="info-label">Email</div>
                <div class="info-value"><?php echo $email; ?></div>

                <div class="info-label">Telemóvel</div>
                <div class="info-value"><?php echo $telefone; ?></div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-custom">
                <span class="section-title">Minhas Inscrições</span>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Modalidade</th>
                                <th>Treinador</th>
                                <th class="text-end">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($res_insc && mysqli_num_rows($res_insc) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($res_insc)): ?>
                                <tr>
                                    <td class="fw-bold fs-5"><?php echo htmlspecialchars($row['nome_mod']); ?></td>
                                    <td><?php echo htmlspecialchars($row['treinadores'] ?: 'Sem treinador atribuído'); ?></td>
                                    <td class="text-end">
                                        <?php $pago_status = in_array(strtolower(trim($row['pago'])), ['1', 'sim', 'true', 'yes'], true); ?>
                                        <span class="status-badge <?php echo $pago_status ? 'status-pago' : 'status-pendente'; ?>">
                                            <?php echo $pago_status ? 'PAGO' : 'PENDENTE'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-secondary text-center">Ainda não tens inscrições.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>