<?php
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

$idDocumentoEncrypted = $_GET['id'] ?? null;
$idDocumento = aes_decrypt($idDocumentoEncrypted);

if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: documentos.php');
    exit;
}

$documentos = null;
$erro = null;

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT 
            g.*,
            e.codigo_inventario,
            e.designacao,
            e.num_serie,
            f.nome AS fornecedor_nome
        FROM documentos g
        INNER JOIN equipamentos e ON g.equipamento_id = e.id
        LEFT JOIN fornecedores f ON g.fornecedor_id = f.id
        WHERE g.id = :id
    ");

    $stmt->bindParam(':id', $idDocumento, PDO::PARAM_INT);
    $stmt->execute();

    $documento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$documento) {
        header('Location: documento.php');
        exit;
    }
} catch (PDOException $err) {
    $erro = "Erro na base de dados: " . $err->getMessage();
}

$ligacao = null;

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <?php if ($erro): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($documento): ?>

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <div>
                                <span class="text-uppercase font-monospace text-muted small">
                                    <?= htmlspecialchars($documento->tipo) ?>
                                </span>

                                <h2 class="fw-bold text-dark m-0">
                                    <?= htmlspecialchars($documento->nome_documento) ?>
                                </h2>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="documentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                                </a>

                                <a href="editar.php?id=<?= urlencode($idDocumentoEncrypted) ?>"
                                    class="btn btn-success btn-sm rounded-pill px-3 fw-medium shadow-sm">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Renovar / Editar
                                </a>
                            </div>
                        </div>

                        <div class="row g-4 small text-secondary justify-content-center">

                            <div class="col-md-6">
                                <div class="p-3 bg-light border rounded-3 h-100">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fa-solid fa-circle-info text-success me-2"></i>
                                        Dados da Cobertura
                                    </h6>

                                    <div class="mb-2">
                                        <strong>Tipo de Cobertura:</strong>
                                        <?= htmlspecialchars($documento->tipo) ?>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Entidade Responsável:</strong>
                                        <?= htmlspecialchars($documento->fornecedor_nome ?? 'Sem fornecedor associado') ?>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Data de Publicação:</strong>
                                        <span class="font-monospace">
                                            <?= date('d/m/Y', strtotime($documento->data_documento)) ?>
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Data de Fim:</strong>
                                        <span class="font-monospace text-danger fw-bold">
                                            <?= !empty($documento->data_validade) ? date('d/m/Y', strtotime($documento->data_validade)) : 'Sem data de validade' ?>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light border rounded-3 h-100">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fa-solid fa-heart-pulse text-success me-2"></i>
                                        Dispositivo Médico Beneficiário
                                    </h6>

                                    <div class="mb-2">
                                        <strong>Código Inventário:</strong>
                                        <span class="badge bg-white text-dark border font-monospace">
                                            #<?= htmlspecialchars($documento->codigo_inventario) ?>
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Equipamento:</strong>
                                        <?= htmlspecialchars($documento->designacao) ?>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Número de Série:</strong>
                                        <span class="font-monospace">
                                            <?= htmlspecialchars($documento->num_serie ?? 'N/D') ?>
                                        </span>
                                    </div>

                                    <div>
                                        <strong>Localização Atual:</strong>
                                        <?= htmlspecialchars($documento->localizacao_nome ?? 'Sem localização') ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($documento->url_externo)): ?>
                                <div class="col-md-6 text-center">
                                    <div class="p-3 bg-light border rounded-3">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fa-solid fa-link text-primary me-2"></i>
                                            Link Externo
                                        </h6>

                                        <a href="<?= htmlspecialchars($documento->url_externo) ?>"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-pill">
                                            Abrir Repositório
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($documento->ficheiro_path)): ?> 
                                <div class="col-md-6 text-center">
                                    <div class="p-3 bg-light border rounded-3">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                                            Documento PDF
                                        </h6>

                                        <a href="<?= BASE_URL . '/uploads/' . urlencode($documento->ficheiro_path) ?>"
                                            target="_blank"
                                            class="btn btn-danger btn-sm rounded-pill">
                                            Ver PDF
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>