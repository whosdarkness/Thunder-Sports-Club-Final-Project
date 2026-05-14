<?php
session_start();
include 'ligamysql.php';

// Segurança: Apenas administradores (cargo 3 ou conforme a tua lógica)
if ($_SESSION['cargo'] < 3) { header("Location: dashboard.php"); exit(); }

// Lógica de Aprovação/Rejeição
if (isset($_GET['acao']) && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast para int por segurança
    $nova_acao = ($_GET['acao'] == 'aprovar') ? 'aprovado' : 'rejeitado';
    
    $query_update = "UPDATE pedidos_treino SET estado = '$nova_acao' WHERE id_pedido = $id";
    mysqli_query($conexao, $query_update);
    header("Location: admin_pedidos.php");
    exit();
}

// Busca pedidos pendentes cruzando com as tabelas de User e Modalidade
$pedidos = mysqli_query($conexao, "SELECT p.*, u.username, m.nome_mod 
                                   FROM pedidos_treino p 
                                   JOIN users u ON p.id_user = u.id_user 
                                   JOIN modalidade m ON p.id_mod = m.id_mod 
                                   WHERE p.estado = 'pendente'");
?>

<div class="container mt-5">
    <h3 class="text-white">Pedidos de Treinadores Pendentes</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Utilizador</th>
                <th>Modalidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($ped = mysqli_fetch_assoc($pedidos)): ?>
            <tr>
                <td><?php echo $ped['username']; ?></td>
                <td class="text-warning"><?php echo $ped['nome_mod']; ?></td>
                <td>
                    <a href="?acao=aprovar&id=<?php echo $ped['id_pedido']; ?>" class="btn btn-success btn-sm">APROVAR</a>
                    <a href="?acao=rejeitar&id=<?php echo $ped['id_pedido']; ?>" class="btn btn-danger btn-sm">REJEITAR</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($pedidos) == 0): ?>
                <tr><td colspan="3" class="text-center">Não há pedidos pendentes.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>