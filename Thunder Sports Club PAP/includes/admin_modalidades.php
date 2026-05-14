<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

// Configuração de caracteres e acentos
mysqli_set_charset($conexao, "utf8mb4");
header('Content-Type: text/html; charset=utf-8');

// Apagar Inscrição
if (isset($_GET['apagar_insc'])) {
    $num_insc = mysqli_real_escape_string($conexao, $_GET['apagar_insc']);
    mysqli_query($conexao, "DELETE FROM inscricao_mod_socio WHERE num_insc = '$num_insc'");
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

// Validar Pagamento
if (isset($_GET['confirmar'])) {
    $num_insc = mysqli_real_escape_string($conexao, $_GET['confirmar']);
    mysqli_query($conexao, "UPDATE inscricao_mod_socio SET pago = 1 WHERE num_insc = '$num_insc'");
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

// Criar Nova Modalidade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_modality'])) {
    $nome_mod = mysqli_real_escape_string($conexao, $_POST['nome_mod']);
    $preco = mysqli_real_escape_string($conexao, $_POST['preco']);
    $preco_junior = mysqli_real_escape_string($conexao, $_POST['preco_junior']);
    $preco_adulto = mysqli_real_escape_string($conexao, $_POST['preco_adulto']);
    
    if (!empty($nome_mod) && !empty($preco) && !empty($preco_junior) && !empty($preco_adulto)) {
        mysqli_query($conexao, "INSERT INTO modalidade (nome_mod, preco, preco_junior, preco_adulto) VALUES ('$nome_mod', '$preco', '$preco_junior', '$preco_adulto')");
        header("Location: " . $_SERVER['PHP_SELF']); exit();
    }
}

// Apagar Modalidade
if (isset($_GET['apagar_mod'])) {
    $id_mod = mysqli_real_escape_string($conexao, $_GET['apagar_mod']);
    mysqli_query($conexao, "DELETE FROM modalidade WHERE id_mod = '$id_mod'");
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

// Atualizar Preços
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_price'])) {
    $id = $_POST['id_mod']; $p = $_POST['preco']; $pj = $_POST['preco_junior']; $pa = $_POST['preco_adulto'];
    mysqli_query($conexao, "UPDATE modalidade SET preco='$p', preco_junior='$pj', preco_adulto='$pa' WHERE id_mod='$id'");
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

$modalidades = mysqli_query($conexao, "SELECT * FROM modalidade");
$inscricoes = mysqli_query($conexao, "SELECT i.num_insc, s.nome_socio, m.nome_mod, i.pago FROM inscricao_mod_socio i JOIN socios s ON i.num_socio = s.num_socio JOIN modalidade m ON i.id_mod = m.id_mod ORDER BY i.pago ASC");
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #0a0a0a; 
            color: rgba(255, 255, 255, 0.8); 
            padding: 40px; 
            font-family: 'Segoe UI', sans-serif; 
        }
        
        h2, .section-title { 
            color: #ffcc00 !important; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        
        .admin-card { 
            background: #141414; 
            border-radius: 15px; 
            padding: 30px; 
            border: 1px solid #222; 
            margin-bottom: 40px; 
        }
        
        .table { 
            border-collapse: separate; 
            border-spacing: 0 12px; 
            margin-bottom: 0;
        }
        
        .table td { 
            background: #1a1a1a; 
            border: none; 
            padding: 20px; 
            vertical-align: middle; 
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }
        
        .table td.highlight-text { 
            color: #f1f1f1 !important;
            font-weight: 700; 
            font-size: 1.05rem;
            letter-spacing: 0.5px;
        }

        .table td:first-child { 
            border-radius: 12px 0 0 12px; 
        }
        
        .table td:last-child { 
            border-radius: 0 12px 12px 0; 
        }
        
        .input-price { 
            background: #000; 
            border: 1px solid #444; 
            color: #ffcc00; 
            border-radius: 8px; 
            width: 90px; 
            text-align: center; 
            font-weight: bold; 
            padding: 8px 10px; 
            font-size: 0.9rem;
        }
        
        .input-price:focus {
            background: #000;
            border-color: #ffcc00;
            color: #ffcc00;
            box-shadow: 0 0 0 2px rgba(255, 204, 0, 0.2);
        }
        
        .btn-gold { 
            background: #ffcc00; 
            color: black; 
            font-weight: bold; 
            border: none; 
            border-radius: 8px; 
            padding: 10px 18px; 
            font-size: 0.8rem; 
            text-transform: uppercase; 
            cursor: pointer;
            transition: 0.2s;
        }
        
        .btn-gold:hover {
            background: #e6b800;
            transform: scale(1.02);
        }
        
        .btn-delete { 
            background: rgba(255, 68, 68, 0.15); 
            color: #ff6666; 
            border: 1px solid #ff4444; 
            padding: 10px 18px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-size: 0.8rem; 
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.2s;
            cursor: pointer;
        }
        
        .btn-delete:hover {
            background: rgba(255, 68, 68, 0.25);
            text-decoration: none;
        }

        .btn-create {
            background: rgba(0, 255, 127, 0.2);
            color: #00ff7f;
            border: 1px solid #00ff7f;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 0.85rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-create:hover {
            background: rgba(0, 255, 127, 0.35);
        }

        .input-field {
            background: #000;
            border: 1px solid #444;
            color: #ffcc00;
            border-radius: 8px;
            padding: 10px 12px;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .input-field::placeholder {
            color: rgba(255, 204, 0, 0.5);
        }
        
        .input-field:focus {
            background: #000;
            border-color: #ffcc00;
            color: #ffcc00;
            box-shadow: 0 0 0 2px rgba(255, 204, 0, 0.2);
            outline: none;
        }

        .badge-status { 
            padding: 8px 14px; 
            border-radius: 8px; 
            font-weight: bold; 
            border: 1px solid; 
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .pago { 
            color: #00ff7f; 
            border-color: #00ff7f; 
            background: rgba(0,255,127,0.15); 
        }
        
        .pendente { 
            color: #ff4444; 
            border-color: #ff4444; 
            background: rgba(255,68,68,0.15); 
        }
        
        label { 
            color: rgba(255, 255, 255, 0.5); 
            font-size: 0.7rem; 
            text-transform: uppercase; 
            margin-right: 8px; 
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .text-end {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .d-flex.gap-2 {
            gap: 12px !important;
        }
        
        .col-md-3, .col-md-2 {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="m-0">GESTÃO DE <span>MODALIDADES</span></h2>
            <a href="dashboard.php" class="btn btn-outline-light px-4">VOLTAR</a>
        </div>

        <div class="admin-card">
            <h5 class="section-title mb-4">Criar Nova Modalidade</h5>
            <form method="POST" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">Nome da Modalidade</label>
                    <input type="text" name="nome_mod" class="form-control input-field" placeholder="Ex: Futebol, Ténis..." required style="width: 100%;">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">Preço Base</label>
                    <input type="number" name="preco" step="0.01" class="form-control input-price" placeholder="0.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">Júnior</label>
                    <input type="number" name="preco_junior" step="0.01" class="form-control input-price" placeholder="0.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">Adulto</label>
                    <input type="number" name="preco_adulto" step="0.01" class="form-control input-price" placeholder="0.00" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="create_modality" class="btn-create w-100">+ Criar Modalidade</button>
                </div>
            </form>
        </div>
        
        <div class="admin-card">
            <h5 class="section-title mb-4">Ajuste de Mensalidades</h5>
            <table class="table">
                <tbody>
                <?php while($mod = mysqli_fetch_assoc($modalidades)): ?>
                <tr>
                    <td width="25%" class="highlight-text">
                        <?php echo htmlspecialchars($mod['nome_mod'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <form method="POST">
                        <input type="hidden" name="id_mod" value="<?php echo $mod['id_mod']; ?>">
                        <td><label>BASE</label><input type="number" step="0.01" name="preco" class="input-price" value="<?php echo $mod['preco']; ?>"></td>
                        <td><label>JR</label><input type="number" step="0.01" name="preco_junior" class="input-price" value="<?php echo $mod['preco_junior']; ?>"></td>
                        <td><label>AD</label><input type="number" step="0.01" name="preco_adulto" class="input-price" value="<?php echo $mod['preco_adulto']; ?>"></td>
                        <td class="text-end">
                            <button type="submit" name="update_price" class="btn-gold me-2">Atualizar</button>
                            <a href="?apagar_mod=<?php echo $mod['id_mod']; ?>" class="btn-delete" onclick="return confirm('Apagar modalidade definitivamente?')">Apagar</a>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-card">
            <h5 class="section-title mb-4">Inscrições e Pagamentos</h5>
            <table class="table">
                <tbody>
                <?php while($ins = mysqli_fetch_assoc($inscricoes)): ?>
                <tr>
                    <td width="30%" class="highlight-text"><?php echo htmlspecialchars($ins['nome_socio'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="highlight-text"><?php echo htmlspecialchars($ins['nome_mod'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <span class="badge-status <?php echo ($ins['pago'] == 1) ? 'pago' : 'pendente'; ?>">
                            <?php echo ($ins['pago'] == 1) ? 'PAGO' : 'PENDENTE'; ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <?php if($ins['pago'] == 0): ?>
                            <a href="?confirmar=<?php echo $ins['num_insc']; ?>" class="btn btn-success fw-bold me-2" style="font-size:0.75rem; background: rgba(0, 255, 127, 0.2); border: 1px solid #00ff7f; color: #00ff7f; padding: 7px 12px; border-radius: 8px; text-decoration: none;">VALIDAR</a>
                        <?php endif; ?>
                        <a href="?apagar_insc=<?php echo $ins['num_insc']; ?>" class="btn-delete" onclick="return confirm('Apagar registo definitivamente?')">APAGAR</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>