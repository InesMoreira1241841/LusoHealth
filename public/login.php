<?php 
require_once __DIR__ . '/../config/config.php'; 
?> 

<!DOCTYPE html> <!-- Informa ao navegador que este é um documento HTML5 -->

<html lang="pt">
<!-- <html> define o início do documento HTML -->
<!-- lang="pt" indica que o idioma principal da página é português -->

<head>
    <meta charset="UTF-8">
    <!-- Define a codificação de caracteres para UTF-8, que 
         suporta acentos e caracteres especiais usados em português -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Torna a página responsiva, ajustando-a para diferentes 
         tamanhos de ecrã, especialmente em dispositivos móveis -->
    <title><?php echo APP_NAME; ?></title>

    <!-- favicon -->
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (local) -->
    <link rel="stylesheet" href="assets/fontawesome/all.min.css">

    <!-- Bootstrap CSS & custom CSS -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/1241841.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container-fluid mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5 col-lg-4">
                
                <div class="card p-4">
                    
                    <div class="text-center mb-4">
                        <div class="icon-circle-main bg-success-light text-success-custom mx-auto mb-2">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Área Reservada</h4>
                        <p class="small text-muted">Introduza as suas credenciais de acesso</p>
                    </div>

                    <form action="index.html" method="POST">
                        
                        <div class="mb-3">

                            <label for="utilizador" class="form-label fw-semibold text-muted small">Utilizador</label>
                            
                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                    <i class="fa-solid fa-user small"></i>
                                </span>
                                <input type="text" id="utilizador" name="utilizador" 
                                class="form-control bg-light border-start-0 rounded-end-3 focus-custom" placeholder="Ex: ines.moreira" required>
                            </div>
                        </div>

                        <div class="mb-4">

                            <label for="password" class="form-label fw-semibold text-muted small">Palavra-Passe</label>

                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                    <i class="fa-solid fa-key small"></i>
                                </span>
                                <input type="password" id="password" name="password" 
                                class="form-control bg-light border-start-0 rounded-end-3 focus-custom" placeholder="*******" required>
                            </div>
                        </div>

                        <div class="alert alert-danger p-2 text-center small rounded-3 mb-4 d-none" id="login-erro">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Erro: Utilizador ou password incorretos.
                        </div>

                        <button type="submit" class="btn btn-success-custom w-100 fw-bold py-2.5 rounded-pill shadow-sm">
                            Entrar <i class="fa-solid fa-right-to-bracket ms-2"></i>
                        </button>

                    </form>

                    <div class="text-center mt-4">

                        <a href="../public/index.html" class="text-decoration-none small text-success-custom fw-semibold">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar à página inicial
                        </a>
                    </div>

                    </div>

                </div> 

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>