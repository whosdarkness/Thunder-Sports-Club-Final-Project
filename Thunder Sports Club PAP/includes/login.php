<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Thunder Sports Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        :root { --thunder-gold: #ffcc00; --thunder-dark: #121212; }
        body { 
            background: linear-gradient(rgba(18,18,18,0.8), rgba(18,18,18,0.8)), url('../imagens/multisport.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 204, 0, 0.3);
            border-radius: 20px;
            padding: 40px;
            color: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.15);
            color: white;
            border-color: var(--thunder-gold);
            box-shadow: none;
        }
        .btn-thunder { background: var(--thunder-gold); color: black; font-weight: bold; border-radius: 10px; }
        .btn-thunder:hover { background: #e6b800; transform: translateY(-2px); }
        .text-gold { color: var(--thunder-gold); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <a href="home.php" class="text-decoration-none">
                            <h2 class="fw-bold text-gold"><i class="bi bi-lightning-fill"></i> THUNDER</h2>
                        </a>
                        <p class="text-white-50">Bem-vindo de volta, atleta!</p>
                    </div>
                    <form action="process_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small">Email ou Utilizador</label>
                            <input type="text" name="user" class="form-control" placeholder="exemplo@email.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small">Palavra-passe</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-thunder w-100 py-2 mb-3">Entrar</button>
                        <div class="text-center">
                            <p class="small mb-0 text-white-50">Não tens conta? <a href="registrar_user.php" class="text-gold text-decoration-none fw-bold">Regista-te aqui</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>