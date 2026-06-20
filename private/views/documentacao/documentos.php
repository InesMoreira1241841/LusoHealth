<?php

require_once __DIR__ . '/../../includes/funcoes.php';

include '../../../assets/includes/head.php';

start_session();
redirect_if_not_logged();

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Captura o modo de visualização. Se não for especificado "arquivados", mostra os ativos (0)
$ver_arquivados = (isset($_GET['modo']) && $_GET['modo'] === 'arquivados') ? 1 : 0;

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT
            d.id,
            d.tipo,
            d.nome_documento,
            d.data_validade,
            d.ficheiro_path,
            d.url_externo,
            e.codigo_inventario,
            e.designacao AS equipamento_nome,
            f.nome AS fornecedor_nome
        FROM documentos d
        LEFT JOIN equipamentos e ON d.equipamento_id = e.id
        LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
        WHERE d.arquivado = :arquivado
        ORDER BY d.nome_documento ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([':arquivado' => $ver_arquivados]);

    $lista_documentos = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Erro ao aceder à base de dados.";
    $lista_documentos = [];
}

$ligacao = null;

// Mapeamento de tipo -> classe do badge (mantém os valores do teu <select> de filtro)
$badges_tipo = [
    'Manual'       => 'bg-danger-subtle text-danger border-danger-subtle',
    'Calibracao'   => 'bg-warning-subtle text-warning border-warning-subtle',
    'Conformidade' => 'bg-success-subtle text-success border-success-subtle',
    'Relatorio'    => 'bg-info-subtle text-info border-info-subtle',
];
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/documentacao.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="btn-group shadow-sm rounded-pill p-1 bg-light border" role="group">
                        <a href="documentos.php" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 0 ? 'btn-success shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-folder-open me-1"></i> Ativos
                        </a>
                        <a href="documentos.php?modo=arquivados" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 1 ? 'btn-danger shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-box-archive me-1"></i> Arquivados
                        </a>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Repositório de Documentação Técnica</h2>
                            <p class="text-muted small m-0">Upload e associação de manuais, relatórios técnicos e certificados de conformidade.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-upload me-2"></i>Associar Documento
                        </a>
                    </div>

                    <?php if (!empty($success_message)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border border-light-subtle" id="tabela">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center">Tipo de Documento</th>
                                    <th class="text-center">Nome do Documento</th>
                                    <th class="text-center">Equipamento</th>
                                    <th class="text-center">Data de Validade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="small text-secondary text-center">

                                <?php foreach ($lista_documentos as $doc) : ?>

                                    <?php
                                    $classe_badge = $badges_tipo[$doc->tipo] ?? 'bg-secondary-subtle text-secondary border-secondary-subtle';

                                    $validade_expirada = false;
                                    if (!empty($doc->data_validade)) {
                                        $validade_expirada = (new DateTime($doc->data_validade)) < new DateTime();
                                    }
                                    ?>

                                    <tr>
                                        <td>
                                            <span class="badge <?= $classe_badge ?> border px-2 py-1 fw-bold">
                                                <?= htmlspecialchars($doc->tipo) ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            <i class="fa-regular fa-file-pdf text-danger me-2 fs-6"></i>
                                            <?= htmlspecialchars($doc->nome_documento) ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($doc->equipamento_nome)) : ?>
                                                <span class="badge bg-light text-dark border font-monospace">#<?= htmlspecialchars($doc->codigo_inventario) ?></span>
                                                <?= htmlspecialchars($doc->equipamento_nome) ?>
                                            <?php elseif (!empty($doc->fornecedor_nome)) : ?>
                                                <?= htmlspecialchars($doc->fornecedor_nome) ?>
                                            <?php else : ?>
                                                <span class="text-muted">Sem associação</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-monospace <?= $validade_expirada ? 'text-danger fw-bold' : '' ?>">
                                            <?php if (empty($doc->data_validade)) : ?>
                                                <span class="text-muted">N/A</span>
                                            <?php else : ?>
                                                <?= $validade_expirada ? '<i class="fa-solid fa-triangle-exclamation me-1"></i>' : '' ?>
                                                <?= date('d/m/Y', strtotime($doc->data_validade)) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group gap-1">
                                                <a href="detalhes.php?id=<?= aes_encrypt($doc->id) ?>" class="btn btn-sm btn-outline-secondary rounded-2" title="Visualizar Detalhes">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <?php if ($ver_arquivados === 0): ?>
                                                    <a href="editar.php?id=<?= aes_encrypt($doc->id) ?>" class="btn btn-sm btn-outline-success rounded-2" title="Editar">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="arquivar.php?id=<?= aes_encrypt($doc->id) ?>" class="btn btn-sm btn-outline-danger rounded-2"
                                                        title="Arquivar" onclick="return confirm('Tem a certeza que deseja arquivar este documento?');">
                                                        <i class="fa-solid fa-box-archive"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="desarquivar.php?id=<?= aes_encrypt($doc->id) ?>" class="btn btn-sm btn-outline-primary rounded-2"
                                                        title="Desarquivar" onclick="return confirm('Deseja restaurar este documento?');">
                                                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>