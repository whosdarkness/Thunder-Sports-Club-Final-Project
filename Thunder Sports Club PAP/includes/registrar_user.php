<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <title>Registro - Thunder Sports Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        :root { --thunder-gold: #ffcc00; --thunder-dark: #121212; }
        body { background-color: var(--thunder-dark); color: white; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; }
        .reg-container { background: #1f1f1f; border-radius: 30px; border: 1px solid #333; overflow: hidden; }
        .reg-info { background: var(--thunder-gold); color: black; padding: 40px; }
        .form-section { padding: 40px; }
        .form-control { background: #2a2a2a; border: 1px solid #444; color: white; }
        .form-control:focus { background: #333; border-color: var(--thunder-gold); color: white; box-shadow: none; }
        .btn-thunder { background: var(--thunder-gold); color: black; font-weight: bold; }
        .helper-text { font-size: 0.75rem; color: #aaa; margin-top: 4px; }
        .invalid-feedback-custom { color: #ff4444; font-size: 0.8rem; display: none; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="reg-container shadow-lg">
                    <div class="row g-0">
                        <div class="col-md-5 reg-info d-none d-md-flex flex-column justify-content-center text-center">
                            <i class="bi bi-lightning-fill display-1 mb-3"></i>
                            <h2 class="fw-bold mb-4">JUNTA-TE À ELITE.</h2>
                            <p>Cria a tua conta para acederes às modalidades e gerires o teu perfil.</p>
                            <a href="login.php" class="btn btn-dark btn-sm mt-3 w-50 mx-auto">Já tenho conta</a>
                        </div>
                        <div class="col-md-7 form-section">
                            <h3 class="fw-bold mb-4">Criar Conta</h3>
                            <form action="process_register.php" method="POST" id="regForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">Nome Próprio</label>
                                        <input type="text" name="fname" class="form-control" placeholder="Ex: Pedro" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">Apelido</label>
                                        <input type="text" name="lname" class="form-control" placeholder="Ex: Rafael" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1">Email</label>
                                    <input type="email" name="email" id="emailInput" class="form-control" placeholder="exemplo@dominio.com" required>
                                    <div class="helper-text">Será usado para o teu login.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1">Palavra-passe</label>
                                    <input type="password" name="password" id="pw1" class="form-control" required>
                                    <div class="helper-text">Mínimo 8 caracteres (letras e números).</div>
                                </div>
                                <div class="mb-4">
                                    <label class="small mb-1">Confirmar Palavra-passe</label>
                                    <input type="password" name="confirm_password" id="pw2" class="form-control" required>
                                    <div id="pw-status" class="helper-text fw-bold"></div>
                                </div>
                                <div class="form-check mb-4 small">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label text-white-50" for="terms">
                                        Aceito os <a href="terms.php" class="text-warning">termos e condições</a>.
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-thunder w-100 py-2" id="btnSubmit">Finalizar Registo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pw1 = document.getElementById('pw1');
        const pw2 = document.getElementById('pw2');
        const status = document.getElementById('pw-status');
        const btn = document.getElementById('btnSubmit');

        // Validação de coincidência de password em tempo real
        pw2.addEventListener('keyup', () => {
            if (pw1.value !== pw2.value) {
                status.innerHTML = "<i class='bi bi-x-circle'></i> As passwords não coincidem";
                status.style.color = "#ff4444";
                btn.disabled = true;
            } else if (pw1.value === "") {
                status.innerHTML = "";
                btn.disabled = true;
            } else {
                status.innerHTML = "<i class='bi bi-check-circle'></i> As passwords coincidem";
                status.style.color = "#00ff00";
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>