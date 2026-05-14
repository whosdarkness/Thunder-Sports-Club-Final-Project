<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

// Verificação de segurança: Apenas Admin (cargo 3)
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 3) { 
    header("Location: dashboard.php"); 
    exit(); 
}

// Adicionar novo horário
if (isset($_POST['add'])) {
    $id_mod = $_POST['id_mod']; 
    $dia = $_POST['dia']; 
    $ini = $_POST['inicio']; 
    $fim = $_POST['fim'];
    mysqli_query($conexao, "INSERT INTO horarios (id_mod, dia_semana, hora_inicio, hora_fim) VALUES ('$id_mod', '$dia', '$ini', '$fim')");
}

// Eliminar horário existente
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($conexao, "DELETE FROM horarios WHERE id_horario = '$id'");
}

$mods = mysqli_query($conexao, "SELECT * FROM modalidade");
$lista = mysqli_query($conexao, "SELECT h.*, m.nome_mod FROM horarios h JOIN modalidade m ON h.id_mod = m.id_mod ORDER BY m.nome_mod, FIELD(dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Gestão de Horários - Thunder Hub</title>
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0a0a0a; color: #f0f0f0; padding: 40px; font-family: 'Segoe UI', sans-serif; }
        .gold { color: #ffcc00; }
        
        .glass-card { background: #141414; border: 1px solid #333; border-radius: 15px; padding: 30px; margin-bottom: 30px; }
        
        .table { --bs-table-bg: transparent; color: #ffffff; border-color: #333; }
        .table thead th { color: #ffcc00 !important; font-weight: bold; letter-spacing: 1px; border-bottom: 2px solid #333; }
        
        .text-secondary { color: #b0b0b0 !important; }
        
        .form-control, .form-select { 
            background: #1a1a1a; 
            border: 1px solid #444; 
            color: #ffffff !important; 
            font-weight: 500;
        }
        .form-control:focus, .form-select:focus { background: #222; border-color: #ffcc00; color: white; box-shadow: none; }

        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.5);
            cursor: pointer;
        }

        .btn-gold { background: #ffcc00; color: black; font-weight: bold; border-radius: 8px; border: none; transition: 0.3s; }
        .btn-gold:hover { background: #ffd633; color: black; transform: translateY(-2px); }
        
        .badge-day { background: #222; color: #ffcc00; border: 1px solid #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; }
        
        /* CORREÇÃO AQUI: Horas em Branco Puro com brilho leve para máxima legibilidade */
        .font-monospace { 
            color: #ffffff !important; 
            font-weight: 700; 
            text-shadow: 0px 0px 8px rgba(255, 255, 255, 0.2);
        } 
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h1 class="gold fw-bold mb-0">GESTÃO DE HORÁRIOS</h1>
                <p class="text-secondary fw-bold">Define os horários semanais para cada modalidade do clube.</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm mb-2 px-3 fw-bold">← Voltar ao Painel</a>
        </div>

        <div class="glass-card shadow">
            <h5 class="mb-4 text-uppercase fw-bold gold" style="letter-spacing: 1px;">Adicionar Novo Período</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="small text-secondary fw-bold mb-1">Modalidade</label>
                    <select name="id_mod" class="form-select">
                        <?php 
                        mysqli_data_seek($mods, 0);
                        while($m = mysqli_fetch_assoc($mods)) echo "<option value='{$m['id_mod']}'>".htmlspecialchars($m['nome_mod'])."</option>"; 
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small text-secondary fw-bold mb-1">Dia da Semana</label>
                    <select name="dia" class="form-select">
                        <option>Segunda</option><option>Terça</option><option>Quarta</option>
                        <option>Quinta</option><option>Sexta</option><option>Sábado</option><option>Domingo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small text-secondary fw-bold mb-1">Hora Início</label>
                    <input type="time" name="inicio" class="form-control text-white fw-bold" required>
                </div>
                <div class="col-md-2">
                    <label class="small text-secondary fw-bold mb-1">Hora Fim</label>
                    <input type="time" name="fim" class="form-control text-white fw-bold" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button name="add" class="btn btn-gold w-100 py-2 shadow-sm">ADICIONAR</button>
                </div>
            </form>
        </div>

        <div class="glass-card shadow">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>MODALIDADE</th>
                        <th>DIA</th>
                        <th>INTERVALO DE HORÁRIO</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($lista) > 0): ?>
                        <?php while($l = mysqli_fetch_assoc($lista)): ?>
                        <tr>
                            <td class="fw-bold text-white"><?php echo htmlspecialchars($l['nome_mod']); ?></td>
                            <td><span class="badge badge-day px-3 py-2"><?php echo $l['dia_semana']; ?></span></td>
                            <td class="font-monospace fs-5">
                                <?php echo substr($l['hora_inicio'],0,5); ?> 
                                <span class="text-secondary mx-2" style="font-size: 0.9rem;">→</span> 
                                <?php echo substr($l['hora_fim'],0,5); ?>
                            </td>
                            <td class="text-end">
                                <a href="?del=<?php echo $l['id_horario']; ?>" class="btn btn-outline-danger btn-sm border-0" onclick="return confirm('Apagar este horário?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5 v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-secondary italic">Nenhum horário configurado para exibição.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>