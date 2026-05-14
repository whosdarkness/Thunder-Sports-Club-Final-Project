<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

// 1. CAPTURA DO ID: Sem isto, davas o erro "Undefined variable $dados"
$id_mod = isset($_GET['id_mod']) ? intval($_GET['id_mod']) : 0;

// 2. CONSULTA À DB: Garante que o nome e preço da modalidade aparecem
$res = mysqli_query($conexao, "SELECT * FROM modalidade WHERE id_mod = '$id_mod'");
$dados = mysqli_fetch_assoc($res);

// 3. VALIDAÇÃO: Se o ID for inválido, não deixa a página carregar com erros
if (!$dados) { 
    header("Location: dashboard.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Pagamento - Thunder Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --gold: #ffcc00; --dark-bg: #0f0f0f; --card-bg: #1a1a1a; }
        body { background-color: var(--dark-bg); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .pay-card { background: var(--card-bg); border-radius: 20px; padding: 35px; border: 1px solid #2a2a2a; width: 420px; box-shadow: 0 15px 35px rgba(0,0,0,0.7); }
        .info-display { background: #222; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #333; }
        .info-label { color: #888; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
        .method-option { background: #222; border: 2px solid #333; padding: 18px; border-radius: 12px; cursor: pointer; margin-bottom: 12px; display: flex; align-items: center; transition: 0.2s; position: relative; }
        .method-option:hover { border-color: #444; }
        .method-option.active { border-color: var(--gold); background: rgba(255, 204, 0, 0.05); }
        .method-option input { position: absolute; opacity: 0; }
        .method-option i { font-size: 1.5rem; margin-right: 15px; color: var(--gold); width: 30px; text-align: center; }
        .btn-confirm { background: var(--gold); color: #000; font-weight: 800; padding: 15px; border-radius: 12px; border: none; width: 100%; text-transform: uppercase; margin-top: 15px; cursor: pointer; }
    </style>
</head>
<body>

<div class="pay-card">
    <h3 class="text-center fw-bold mb-4">FINALIZAR <span style="color:var(--gold)">PAGAMENTO</span></h3>
    
    <div class="info-display d-flex justify-content-between align-items-center">
        <div>
            <div class="info-label">Modalidade</div>
            <div class="fw-bold"><?php echo $dados['nome_mod']; ?></div>
        </div>
        <div class="text-end">
            <div class="info-label">Valor</div>
            <div class="fw-bold" style="color:var(--gold)"><?php echo number_format($dados['preco'], 2); ?>€</div>
        </div>
    </div>

    <form action="processa_pagamento.php" method="POST">
        <input type="hidden" name="id_mod" value="<?php echo $id_mod; ?>">
        <input type="hidden" name="valor" value="<?php echo $dados['preco']; ?>">

        <label class="method-option active" id="label-mbway">
            <input type="radio" name="metodo" value="MBWAY" checked onclick="toggleMethod('mbway')">
            <i class="fa-solid fa-mobile-screen-button"></i>
            <div>
                <strong>MB WAY</strong><br>
                <small style="color: #888;">Pagamento Instantâneo</small>
            </div>
        </label>

        <label class="method-option" id="label-mb">
            <input type="radio" name="metodo" value="MULTIBANCO" onclick="toggleMethod('mb')">
            <i class="fa-solid fa-building-columns"></i>
            <div>
                <strong>Multibanco</strong><br>
                <small style="color: #888;">Referência Multibanco</small>
            </div>
        </label>

        <button type="submit" class="btn-confirm">CONFIRMAR E PAGAR AGORA</button>
    </form>
</div>

<script>
    function toggleMethod(type) {
        document.getElementById('label-mbway').classList.remove('active');
        document.getElementById('label-mb').classList.remove('active');
        if(type === 'mbway') {
            document.getElementById('label-mbway').classList.add('active');
        } else {
            document.getElementById('label-mb').classList.add('active');
        }
    }
</script>

</body>
</html>