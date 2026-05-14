<?php
session_start();
include 'ligamysql.php';

// Verifica se o utilizador está logado e tem cargo de treinador (cargo >= 2)
if (!isset($_SESSION['id_user']) || $_SESSION['cargo'] < 2) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['id_user'];

// Lógica para inserir o pedido na BD
if (isset($_POST['pedir_treino'])) {
    $id_mod = mysqli_real_escape_string($conexao, $_POST['modalidade']);
    
    // Inserção explícita do estado 'pendente' conforme o ENUM da tua BD
    $sql = "INSERT INTO pedidos_treino (id_user, id_mod, estado, data_pedido) 
            VALUES ('$user_id', '$id_mod', 'pendente', NOW())";
    
    if (mysqli_query($conexao, $sql)) {
        header("Location: painel_treinador.php?msg=enviado");
        exit();
    }
}

// Queries para as tabelas
$modalidades = mysqli_query($conexao, "SELECT * FROM modalidade");
$meus_pedidos = mysqli_query($conexao, "SELECT p.*, m.nome_mod 
                                        FROM pedidos_treino p 
                                        JOIN modalidade m ON p.id_mod = m.id_mod 
                                        WHERE p.id_user = '$user_id' 
                                        ORDER BY p.data_pedido DESC");
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Painel Treinador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0a0a0a; color: #fff; padding: 40px; }
        .card-treino { background: #161616; border-radius: 15px; padding: 25px; border: 1px solid #222; }
        .badge-pendente { color: #ffc107; border: 1px solid #ffc107; padding: 5px; }
        .badge-aprovado { color: #198754; border: 1px solid #198754; padding: 5px; }
        .badge-rejeitado { color: #dc3545; border: 1px solid #dc3545; padding: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card-treino">
                    <h5 class="text-warning">Pedir Nova Modalidade</h5>
                    <form method="POST">
                        <select name="modalidade" class="form-select bg-dark text-white mb-3" required>
                            <?php while($m = mysqli_fetch_assoc($modalidades)): ?>
                                <option value="<?php echo $m['id_mod']; ?>"><?php echo $m['nome_mod']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="pedir_treino" class="btn btn-warning w-100">PEDIR</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-treino mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning mb-0">Meus Pedidos</h5>
                        <a href="treinador_dados.php" class="btn btn-outline-light btn-sm">Consultar Dados do Treinador</a>
                    </div>
                    <table class="table table-dark">
                        <thead>
                            <tr><th>Modalidade</th><th>Estado</th><th>Data</th></tr>
                        </thead>
                        <tbody>
                            <?php while($p = mysqli_fetch_assoc($meus_pedidos)): ?>
                            <tr>
                                <td><?php echo $p['nome_mod']; ?></td>
                                <td>
                                    <span class="badge-<?php echo $p['estado']; ?>">
                                        <?php echo strtoupper($p['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($p['data_pedido'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>