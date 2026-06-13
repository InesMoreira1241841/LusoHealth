<?php include '../assets/includes/head.php'; ?>

<body>
    <header class="custom-header sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg container">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                    <img src="../assets/img/logo.png" alt="Logo LusoHealth" width="40" height="40">
                    <span class="fw-bold brand-title"><?php echo APP_NAME; ?></span>
                </a>
                <div class="d-none d-lg-block">
                    <a href="login.php" class="btn btn-login-custom fw-bold px-4 rounded-pill shadow-sm">
                        ENTRAR <i class="fa-solid fa-right-to-bracket ms-2"></i>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <section class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text-dark">Fornecedores</h1>
                    <p class="text-muted">Consulte o inventário público de fornecedores e parceiros técnicos.</p>
                </div>
                <a href="index.php" class="btn btn-light border rounded-pill px-4 fw-medium">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
            <div class="alert alert-info rounded-3">
                <i class="fa-solid fa-circle-info me-2"></i>
                A listagem pública de fornecedores estará disponível após a ligação à base de dados.
                <a href="login.php" class="alert-link ms-1">Aceder à área privada.</a>
            </div>
        </section>
    </main>

    <footer class="py-4 mt-5 border-top border-light-subtle">
        <div class="container text-center">
            <p class="small text-muted mb-0"><?php echo APP_COPYRIGHT; ?> <span class="fw-semibold text-success-custom"><?php echo APP_NAME; ?></span></p>
        </div>
    </footer>

<?php include '../assets/includes/footer.php'; ?>
