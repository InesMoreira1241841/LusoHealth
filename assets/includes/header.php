<?php
// Verifica se a sessão ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão
}
// Verifica se o utilizador está autenticado
if (!isset($_SESSION['utilizador'])) {
    // Se não estiver autenticado, redireciona para o formulário de login
    header('Location: ../public/login.php');
    exit; // Encerra o script
}
// A partir daqui, o utilizador está autenticado
// Podemos usar livremente os dados da sessão
$nome = $_SESSION['utilizador'];
?>

<header class="container-fluid bg-white border-bottom p-3 shadow-sm">
    <div class="row align-items-center">
        <div class="col-6 d-flex align-items-center">
            <i class="fa-solid fa-microscope fa-2xl me-3 icon-brand-clinical"></i>
            <h3 class="mb-0 fw-bold text-dark">LusoHealth <span class="fs-6 text-muted fw-normal">| Gestão de
                    Inventário</span></h3>
        </div>

        <div class="col-6 text-end">
            <div class="dropdown d-inline-block">
                <button
                    class="btn btn-light border dropdown-toggle p-2 rounded-pill fw-medium d-flex align-items-center gap-2 ms-auto"
                    type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-gear text-success"></i>
                    <span><?= htmlspecialchars($nome) ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle mt-2 custom-card-rounded"
                    aria-labelledby="userMenu">
                    <li>
                        <h6 class="dropdown-header small text-uppercase fw-bold text-muted"><?= htmlspecialchars($nome) ?></h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center gap-2 px-3 py-2 text-decoration-none small"
                            href="/lusohealth/public/logout.php">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="fw-medium">Terminar Sessão</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>