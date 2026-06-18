<?php
// --------------------------------------------------------------------
// SEGURANÇA
// --------------------------------------------------------------------

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

// 1. Capturar e desencriptar ID
$idFornecedorEncrypted = $_GET['id_fornecedores'] ?? null;
$idFornecedor = aes_decrypt($idFornecedorEncrypted);

if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: fornecedores.php');
    exit;
}

$erros = [];
$fornecedor = null;
$equipamentos = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ------------------------------------------------
    // Query A -> dados do fornecedor
    // ------------------------------------------------
    $stmtForn = $ligacao->prepare("
        SELECT *
        FROM fornecedores
        WHERE id = :id
    ");

    $stmtForn->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmtForn->execute();

    $fornecedor = $stmtForn->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: fornecedores.php');
        exit;
    }

    // ------------------------------------------------
    // Query B -> equipamentos associados
    // ------------------------------------------------
    $stmtEquip = $ligacao->prepare("
        SELECT
            e.*,
            ef.tipo_relacao
        FROM equipamento_fornecedor ef
        INNER JOIN equipamentos e
            ON ef.equipamento_id = e.id
        WHERE ef.fornecedor_id = :id_fornecedor
        ORDER BY e.designacao ASC
    ");

    $stmtEquip->bindParam(':id_fornecedor', $idFornecedor, PDO::PARAM_INT);
    $stmtEquip->execute();

    $equipamentos = $stmtEquip->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $err) {
    $erros[] = "Erro ao carregar dados do fornecedor: " . $err->getMessage();
}

$ligacao = null;

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">

<?php include '../../../assets/includes/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row g-4">

        <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

        <main class="col-md-9 col-lg-10">

            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger text-center mb-3 shadow-sm" role="alert">
                    <?php foreach ($erros as $erro): ?>
                        <div><?= htmlspecialchars($erro) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h2 class="fw-bold text-dark m-0">Ficha Contratual do Parceiro</h2>
                        <p class="text-muted small m-0">
                            Histórico de assistência técnica e equipamentos associados.
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="fornecedores.php"
                           class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>

                        <a href="editar.php?id_fornecedores=<?= $idFornecedorEncrypted ?>"
                           class="btn btn-success btn-sm rounded-pill px-3 fw-medium shadow-sm">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Editar
                        </a>
                    </div>
                </div>

                <!-- Dados do fornecedor -->
                <div class="row g-4 text-secondary small mb-4">

                    <div class="col-md-4">
                        <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                              style="font-size: 0.7rem;">Entidade</span>

                        <p class="text-dark fw-bold fs-5 m-0">
                            <?= htmlspecialchars($fornecedor->nome) ?>
                        </p>

                        <p class="text-muted m-0 mt-1">
                            NIF: <?= htmlspecialchars($fornecedor->nif) ?>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                              style="font-size: 0.7rem;">Contactos Oficiais</span>

                        <p class="m-0 text-dark fw-medium">
                            <i class="fa-solid fa-phone me-2 text-muted"></i>
                            <?= htmlspecialchars($fornecedor->telefone ?? 'N/A') ?>
                        </p>

                        <p class="m-0 text-dark fw-medium mt-1">
                            <i class="fa-solid fa-envelope me-2 text-muted"></i>
                            <?= htmlspecialchars($fornecedor->email ?? 'N/A') ?>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                              style="font-size: 0.7rem;">Tipo de Fornecedor</span>

                        <span class="badge bg-success-subtle text-success border px-3 py-2 rounded-3">
                            <?= htmlspecialchars($fornecedor->tipo) ?>
                        </span>
                    </div>
                </div>

                <!-- Técnico -->
                <div class="bg-light p-3 rounded-3 mb-4 border row g-3 mx-0 small">

                    <div class="col-md-6 border-end border-light-subtle">
                        <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                              style="font-size: 0.7rem;">
                            Técnico Responsável
                        </span>

                        <p class="text-success fw-bold m-0">
                            <i class="fa-solid fa-user-gear me-2"></i>
                            <?= htmlspecialchars($fornecedor->tecnico_nome ?? 'Não definido') ?>
                        </p>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                              style="font-size: 0.7rem;">
                            Linha Direta
                        </span>

                        <p class="text-dark fw-bold m-0">
                            <i class="fa-solid fa-phone-volume me-2 text-success"></i>
                            <?= htmlspecialchars($fornecedor->tecnico_telefone ?? 'N/A') ?>
                        </p>
                    </div>
                </div>

                <!-- Equipamentos -->
                <div class="pt-3 border-top">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-heart-pulse text-success me-2"></i>
                        Equipamentos Associados
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle small text-secondary">

                            <thead class="table-light text-uppercase font-monospace"
                                   style="font-size: 0.75rem;">
                                <tr>
                                    <th>Inventário</th>
                                    <th>Equipamento</th>
                                    <th>Marca / Modelo</th>
                                    <th>Estado</th>
                                    <th>Relação</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($equipamentos)): ?>
                                    <?php foreach ($equipamentos as $equip): ?>
                                        <tr>
                                            <td class="fw-bold text-dark">
                                                <?= htmlspecialchars($equip->codigo_inventario) ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($equip->designacao) ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($equip->marca) ?>
                                                /
                                                <?= htmlspecialchars($equip->modelo) ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($equip->estado ?? 'Operacional') ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                                                    <?= htmlspecialchars($equip->tipo_relacao) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            Nenhum equipamento associado a este fornecedor.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<?php include '../../../assets/includes/footer.php'; ?>