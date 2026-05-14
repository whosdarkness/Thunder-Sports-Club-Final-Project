<?php
session_start();
include "ligamysql.php";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições - Thunder Sports Club</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column h-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="home.php">Thunder Sports Club</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="home.php">Página Inicial</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login | Registar</a></li>
                    <li class="nav-item"><a class="nav-link" href="termos.php">Termos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo dos Termos -->
    <main class="flex-shrink-0">
        <div class="container my-5">
            <h1 class="mb-4">Termos e Condições</h1>
            <p>Bem-vindo ao Thunder Sports Club! Ao utilizar os nossos serviços, concorda com os seguintes termos:</p>

            <h3>1. Uso do Site</h3>
            <p>O conteúdo do nosso site é apenas para informação geral. Não nos responsabilizamos por decisões tomadas com base nestas informações.</p>

            <h3>2. Inscrições e Pagamentos</h3>
            <p>Os preços das modalidades estão sujeitos a alterações. O pagamento das mensalidades deve ser efetuado pontualmente para garantir a participação nas atividades.</p>

            <h3>3. Privacidade</h3>
            <p>Respeitamos a sua privacidade e os seus dados pessoais são tratados de acordo com a nossa política de privacidade e com o RGPD.</p>

            <h3>4. Responsabilidade</h3>
            <p>O clube não se responsabiliza por acidentes ocorridos durante a prática desportiva fora das condições normais e supervisionadas pelo clube.</p>

            <h3>5. Alterações</h3>
            <p>Podemos atualizar estes termos a qualquer momento. Recomendamos que consulte esta página regularmente.</p>

            <p class="mt-4">Ao continuar a utilizar o nosso site, concorda com estes termos e condições.</p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark py-5 mt-auto text-white" style="font-size: 1.2rem;">
        <div class="container px-5">
            <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                <div class="col-auto mb-2 mb-sm-0">Feito por Pedro Rafael 2025/2026</div>
                <div class="col-auto mb-2 mb-sm-0">Contacto: +351 961 854 787 | Email: thundersports@expap.pt</div>
                <div class="col-auto">
                    <a class="link-light mx-2" href="terms.php">Termos e privacidade</a>
                    <a class="link-light mx-2" href="home.php#fw-aaa">Contacto</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
