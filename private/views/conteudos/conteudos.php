<?php
require_once __DIR__ . '/../../includes/funcoes.php';

start_session();
redirect_if_not_logged();

include '../../../assets/includes/head.php';

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

$conteudos_existentes = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->query("SELECT id, chave, valor, atualizado_em FROM conteudos_publicos ORDER BY chave ASC");
    $conteudos_existentes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $error_message = "Erro ao aceder à base de dados.";
}
$ligacao = null;
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/conteudos.php' ?>

            <main class="col-md-9 col-lg-10">
                
                <div class="bg-white p-4 shadow-sm border border-light-subtle custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Conteúdos do Website Público</h2>
                        <p class="text-muted small m-0">Edite os textos apresentados na página inicial sem necessidade de alterar código.</p>
                    </div>

                    <?php if (!empty($success_message)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Lista de conteúdos existentes, cada um com o seu próprio formulário -->
                    <?php foreach ($conteudos_existentes as $conteudo) : ?>
                        <form action="processa_conteudo.php" method="POST" class="mb-4 pb-3 border-bottom">
                            <input type="hidden" name="acao" value="atualizar">
                            <input type="hidden" name="id" value="<?= $conteudo->id ?>">

                            <label class="form-label fw-bold text-dark">
                                <?= htmlspecialchars($conteudo->chave) ?>
                                <?php if ($conteudo->atualizado_em): ?>
                                    <span class="small text-muted fw-normal">
                                        (última atualização: <?= date('d/m/Y H:i', strtotime($conteudo->atualizado_em)) ?>)
                                    </span>
                                <?php endif; ?>
                            </label>

                            <textarea name="valor" class="form-control rounded-3" rows="3"><?= htmlspecialchars($conteudo->valor) ?></textarea>

                            <div class="mt-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="fa-solid fa-floppy-disk me-1"></i>Guardar
                                </button>
                            </div>
                        </form>
                    <?php endforeach; ?>

                    <!-- Formulário para adicionar uma nova chave -->
                    <div class="pt-3">
                        <h5 class="fw-bold text-dark mb-3">Adicionar Nova Entrada</h5>
                        <form action="processa_conteudo.php" method="POST" class="row g-2 align-items-end">
                            <input type="hidden" name="acao" value="criar">

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Chave</label>
                                <input type="text" name="chave" class="form-control rounded-3" placeholder="Ex: texto_home" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small text-muted">Valor</label>
                                <input type="text" name="valor" class="form-control rounded-3" placeholder="Texto a apresentar" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-success w-100 rounded-pill">
                                    <i class="fa-solid fa-plus me-1"></i>Criar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>