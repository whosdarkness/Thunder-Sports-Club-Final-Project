<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'ligamysql.php';

// Procurar modalidades na BD para os cards e horários
$query_mod = mysqli_query($conexao, "SELECT * FROM modalidade ORDER BY id_mod ASC");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Thunder Sports Club</title>
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        :root { --thunder-gold: #ffcc00; --thunder-dark: #121212; --thunder-gray: #1f1f1f; }
        body { font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: var(--thunder-dark) !important; border-bottom: 2px solid var(--thunder-gold); }
        .navbar-brand { font-weight: 800; color: var(--thunder-gold) !important; }
        .hero-header { background: linear-gradient(45deg, rgba(18,18,18,0.9) 30%, rgba(18,18,18,0.4) 100%), url('../imagens/multisport.png'); background-size: cover; background-position: center; padding: 140px 0; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); }
        .text-gold { color: var(--thunder-gold) !important; }
        .btn-thunder { background-color: var(--thunder-gold); color: black; font-weight: bold; transition: 0.3s; border: none; }
        .btn-thunder:hover { background-color: #e6b800; transform: scale(1.05); }
        .modality-card { border: none; border-radius: 15px; background: white; transition: all 0.3s ease; border-bottom: 4px solid transparent; }
        .modality-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-bottom: 4px solid var(--thunder-gold); }
        .testimonial-box { background-color: var(--thunder-dark); color: white; border-left: 5px solid var(--thunder-gold); padding: 40px; border-radius: 0 20px 20px 0; }
        footer { background-color: var(--thunder-dark) !important; border-top: 3px solid var(--thunder-gold); }
        
        /* Estilos para a tabela de horários */
        .table-horario { border-radius: 15px; overflow: hidden; border: 1px solid #333; }
        .table-horario thead { background-color: var(--thunder-gold); color: black; }
    </style>
</head>
<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php"><i class="bi bi-lightning-fill text-gold"></i> THUNDER SPORTS CLUB</a>
            <div class="d-flex gap-2">
                <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
                <a href="registrar_user.php" class="btn btn-thunder btn-sm">Registar</a>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0">
        <header class="hero-header text-white">
            <div class="container px-5">
                <h1 class="display-2 fw-bolder mb-3">O clube onde o esforço faz <span class="text-gold">milagres!</span></h1>
                <p class="lead mb-4 fs-4 text-white-50">Acessível, Confiável e Recompensador.</p>
                <div class="d-grid gap-3 d-sm-flex">
                    <a class="btn btn-thunder btn-lg px-5" href="#mod_prec">Modalidades</a>
                    <a class="btn btn-outline-light btn-lg px-5" href="inscrever.php">Inscreva-se!</a>
                </div>
            </div>
        </header>

        <section class="py-5" id="mod_prec">
            <div class="container px-5 my-5">
                <h2 class="fw-bolder display-5 mb-5 text-center">Nossas Modalidades</h2>
                <div class="row gx-5 row-cols-1 row-cols-md-2 row-cols-xl-4 text-center">
                    <?php 
                    while($m = mysqli_fetch_assoc($query_mod)): 
                    ?>
                    <div class="col mb-4">
                        <div class="card h-100 modality-card p-4">
                            <div class="text-gold fs-1 mb-2">
                                <?php 
                                    $icon = "bi-trophy";
                                    $nomeLower = strtolower($m['nome_mod']);
                                    if(strpos($nomeLower, 'basque') !== false || strpos($nomeLower, 'basket') !== false) $icon = "bi-dribbble";
                                    if(strpos($nomeLower, 'ténis') !== false || strpos($nomeLower, 'tenis') !== false) $icon = "bi-tencent-qq";
                                    if(strpos($nomeLower, 'golf') !== false) $icon = "bi-flag-fill";
                                    if(strpos($nomeLower, 'futebol') !== false) $icon = "bi-sun-fill"; 
                                ?>
                                <i class="bi <?php echo $icon; ?>"></i>
                            </div>
                            <h3 class="fw-bold"><?php echo htmlspecialchars($m['nome_mod']); ?></h3>
                            <div class="mt-2">
                                <p class="mb-1 text-muted small">Júnior: <span class="fw-bold text-dark"><?php echo number_format($m['preco_junior'], 2); ?>€</span></p>
                                <p class="mb-0 text-muted small">Adulto: <span class="fw-bold text-dark"><?php echo number_format($m['preco_adulto'], 2); ?>€</span></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <section class="py-5 bg-dark text-white" id="horarios">
            <div class="container px-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder display-5 text-gold">Horários de Treino</h2>
                    <p class="lead text-white-50">Consulte os horários atualizados de cada modalidade</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="table-responsive table-horario shadow-lg">
                            <table class="table table-dark table-hover align-middle mb-0 text-center border-secondary">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th class="py-3">Modalidade</th>
                                        <th class="py-3">Horários Semanais</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    mysqli_data_seek($query_mod, 0);
                                    while($m = mysqli_fetch_assoc($query_mod)): 
                                        $id_atual = $m['id_mod'];
                                        // Procura os horários específicos desta modalidade
                                        $res_h = mysqli_query($conexao, "SELECT * FROM horarios WHERE id_mod = '$id_atual' ORDER BY FIELD(dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')");
                                    ?>
                                    <tr style="border-bottom: 1px solid #2a2a2a;">
                                        <td class="fw-bold text-gold py-4 text-uppercase">
                                            <?php echo htmlspecialchars($m['nome_mod']); ?>
                                        </td>
                                        <td class="py-4">
                                            <div class="d-flex flex-wrap justify-content-center gap-3">
                                                <?php if(mysqli_num_rows($res_h) > 0): ?>
                                                    <?php while($h = mysqli_fetch_assoc($res_h)): ?>
                                                        <div class="text-center p-2 rounded border border-secondary" style="min-width: 120px;">
                                                            <span class="badge rounded-pill bg-warning text-dark mb-1">
                                                                <?php echo $h['dia_semana']; ?>
                                                            </span><br>
                                                            <small class="text-white">
                                                                <?php echo substr($h['hora_inicio'],0,5) . " - " . substr($h['hora_fim'],0,5); ?>
                                                            </small>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <small class="text-muted italic">Brevemente disponível</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container my-5 pt-4">
            <div class="testimonial-box">
                <i class="bi bi-quote fs-1 text-gold"></i>
                <p class="fs-3 fst-italic mb-3">"Eu sou o melhor! Posso não ser, mas na minha cabeça eu sou o melhor!"</p>
                <div class="fw-bold text-gold">Cristiano Ronaldo <span class="text-white fw-light mx-2">|</span> Jogador de Futebol</div>
            </div>
        </div>

        <section class="py-5 bg-light" id="sobre">
            <div class="container px-5 text-center">
                <h2 class="fw-bolder display-6 mb-5">Sobre nós</h2>
                <div class="row gx-5">
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <img class="card-img-top" src="https://dummyimage.com/600x350/121212/ffcc00&text=História" alt="..." />
                            <div class="card-body p-4 text-start">
                                <h5 class="fw-bold">Fundada em 2010</h5>
                                <p class="text-muted small">Campeões nos anos 2020, 2021 e 2023.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <img class="card-img-top" src="https://dummyimage.com/600x350/ffcc00/121212&text=Novidades" alt="..." />
                            <div class="card-body p-4 text-start">
                                <h5 class="fw-bold">Época 2026</h5>
                                <p class="text-muted small">A nova temporada já começou com novas instalações!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bg-dark text-white p-4 d-flex flex-column">
                            <h5 class="fw-bold text-gold mb-3">Junta-te a nós!</h5>
                            <p class="small">Temos limite de 20 membros por modalidade para garantir a qualidade.</p>
                            <a href="inscrever.php" class="btn btn-thunder w-100 mt-auto">Inscrever Agora</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-5 mt-auto text-white">
        <div class="container px-5 text-center text-sm-start">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-auto">
                    <div class="fw-bold text-gold">Thunder Sports Club</div>
                    <div class="small text-white-50">Pedro Rafael 2025/2026</div>
                </div>
                <div class="col-sm-auto">
                    <div class="small"><i class="bi bi-envelope-fill me-2"></i>thundersports@expap.pt</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>