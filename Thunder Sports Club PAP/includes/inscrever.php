<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';
$query_mods = mysqli_query($conexao, "SELECT id_mod, nome_mod FROM modalidade");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Inscrição - Thunder Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --gold: #ffcc00; --dark: #0f0f0f; }
        body { background-color: var(--dark); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .enroll-card { background: #141414; border-radius: 24px; padding: 40px; border: 1px solid #222; width: 600px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        .form-label { color: var(--gold); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .form-control, .form-select { background-color: #1a1a1a; border: 1px solid #333; color: white !important; padding: 12px; border-radius: 12px; }
        .form-control:focus { border-color: var(--gold); box-shadow: none; background: #222; }
        .btn-next { background: var(--gold); color: black; font-weight: 800; padding: 16px; border-radius: 12px; width: 100%; border: none; text-transform: uppercase; margin-top: 20px; transition: 0.3s; }
        .btn-next:hover { background: #e6b800; transform: scale(1.02); }
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
    </style>
</head>
<body>
<div class="enroll-card">
    <h2 class="text-center mb-4">FICHA DE <span style="color:var(--gold)">INSCRIÇÃO</span></h2>
    <form action="processa_insc.php" method="POST">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Primeiro Nome</label>
                <input type="text" name="p_nome" class="form-control" placeholder="Duarte" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Último Nome</label>
                <input type="text" name="u_nome" class="form-control" placeholder="Rafael" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email de Contacto</label>
            <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Telemóvel</label>
                <input type="text" name="telefone" class="form-control" placeholder="9xxxxxxxx" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIF</label>
                <input type="text" name="nif" class="form-control" maxlength="9" placeholder="9 dígitos" required>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Data Nascimento</label>
                <input type="date" name="data_nasc" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Modalidade</label>
                <select name="id_mod" class="form-select" required>
                    <option value="" disabled selected>Escolher...</option>
                    <?php while($m = mysqli_fetch_assoc($query_mods)): ?>
                        <option value="<?php echo $m['id_mod']; ?>"><?php echo $m['nome_mod']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn-next">AVANÇAR PARA PAGAMENTO</button>
    </form>
</div>
</body>
</html>