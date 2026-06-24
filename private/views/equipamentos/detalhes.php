<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de detalhes
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

// 1. Capturar e desencriptar o ID do equipamento vindo do GET
$idEquipamentoEncrypted = $_GET['id_equipamentos'] ?? null;
$idEquipamento = aes_decrypt($idEquipamentoEncrypted);

if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: equipamentos.php');
    exit;
}

$equipamento = null;
$fornecedores_vinculados = [];
$garantias = [];
$documentos = [];
$erro = null;

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ----------------------------------------------------------------
    // 2. Dados principais do equipamento + categoria + localização
    // ----------------------------------------------------------------
    $stmt = $ligacao->prepare("
        SELECT 
            e.*,
            c.nome AS categoria_nome,
            l.nome AS localizacao_nome,
            l.edificio AS localizacao_edificio,
            l.piso AS localizacao_piso
        FROM equipamentos e
        LEFT JOIN categorias c ON e.categoria_id = c.id
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        WHERE e.id = :id
    ");

    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: equipamentos.php');
        exit;
    }

    // ----------------------------------------------------------------
    // 3. Fornecedores vinculados (Fabricante, Distribuidor, Assistência...)
    // ----------------------------------------------------------------
    $stmt_forn = $ligacao->prepare("
        SELECT ef.tipo_relacao, f.id, f.nome, f.tipo
        FROM equipamento_fornecedor ef
        INNER JOIN fornecedores f ON ef.fornecedor_id = f.id
        WHERE ef.equipamento_id = :id
        ORDER BY ef.tipo_relacao ASC
    ");
    // CORRIGIDO: Retirado os dois pontos (:) da chave do array
    $stmt_forn->execute(['id' => $idEquipamento]);
    $fornecedores_vinculados = $stmt_forn->fetchAll(PDO::FETCH_OBJ);

    // ----------------------------------------------------------------
    // 4. Garantias / Contratos de manutenção associados
    // ----------------------------------------------------------------
    $stmt_gar = $ligacao->prepare("
        SELECT g.*, f.nome AS fornecedor_nome
        FROM garantias g
        LEFT JOIN fornecedores f ON g.fornecedor_id = f.id
        WHERE g.equipamento_id = :id
        ORDER BY g.data_fim DESC
    ");
    // CORRIGIDO: Retirado os dois pontos (:) da chave do array
    $stmt_gar->execute(['id' => $idEquipamento]);
    $garantias = $stmt_gar->fetchAll(PDO::FETCH_OBJ);

    // ----------------------------------------------------------------
    // 5. Documentos associados
    // ----------------------------------------------------------------
    $stmt_doc = $ligacao->prepare("
        SELECT d.*, f.nome AS fornecedor_nome
        FROM documentos d
        LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
        WHERE d.equipamento_id = :id
        ORDER BY d.data_documento DESC
    ");
    // CORRIGIDO: Retirado os dois pontos (:) da chave do array
    $stmt_doc->execute(['id' => $idEquipamento]);
    $documentos = $stmt_doc->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erro = "Erro ao aceder à base de dados.";
    // Em produção, isto é excelente: error_log($err->getMessage());
}

$ligacao = null;

// Procura o fornecedor com papel "Fabricante" para destaque no topo
$fabricante = null;
foreach ($fornecedores_vinculados as $f) {
    if ($f->tipo_relacao === 'Fabricante') {
        $fabricante = $f;
        break;
    }
}

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">

                <?php if ($erro): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <?php if ($equipamento): ?>

                    <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <div>
                                <h2 class="fw-bold text-dark m-0">Ficha Técnica e Documental</h2>
                                <p class="text-muted small m-0">Rastreabilidade completa, manuais e garantias associadas.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="equipamentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                                </a>
                                <a href="editar.php?id_equipamentos=<?= urlencode($idEquipamentoEncrypted) ?>" class="btn btn-success btn-sm rounded-pill px-3 fw-medium">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Editar Equipamento
                                </a>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- DADOS GERAIS -->
                        <!-- ============================================================ -->
                        <div class="row g-4 text-secondary small mb-5">
                            <div class="col-md-3">
                                <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Equipamento</span>
                                <p class="text-dark fw-bold fs-5 m-0">
                                    <?= htmlspecialchars($equipamento->designacao) ?>
                                </p>
                                <small class="text-muted font-monospace">#<?= htmlspecialchars($equipamento->codigo_inventario) ?></small>
                            </div>

                            <div class="col-md-3">
                                <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Criticidade Atribuída</span>
                                <p class="m-0 mt-1">
                                    <span class="badge <?= $equipamento->criticidade === 'Suporte de vida' || $equipamento->criticidade === 'Alta' ? 'bg-danger' : 'bg-secondary' ?> text-white rounded-pill px-2 py-1">
                                        <?= htmlspecialchars($equipamento->criticidade) ?>
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-3">
                                <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Fornecedor (Fabricante)</span>
                                <p class="text-dark fw-semibold m-0 mt-1">
                                    <i class="fa-solid fa-industry me-1 text-success"></i>
                                    <?= $fabricante ? htmlspecialchars($fabricante->nome) : 'Não definido' ?>
                                </p>
                            </div>

                            <div class="col-md-3">
                                <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Localização Atual</span>
                                <p class="text-dark fw-semibold m-0 mt-1">
                                    <i class="fa-solid fa-location-dot me-1 text-success"></i>
                                    <?php if ($equipamento->localizacao_nome): ?>
                                        <?= htmlspecialchars($equipamento->localizacao_edificio) ?> |
                                        <?= htmlspecialchars($equipamento->localizacao_nome) ?>
                                        (Piso <?= htmlspecialchars($equipamento->localizacao_piso) ?>)
                                    <?php else: ?>
                                        Sem localização definida
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- ENTIDADES / FORNECEDORES VINCULADOS -->
                        <!-- ============================================================ -->
                        <div class="mb-5 pt-3 border-top">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-handshake text-success me-2"></i>Entidades Vinculadas
                            </h5>

                            <?php if (empty($fornecedores_vinculados)): ?>
                                <p class="text-muted small">Nenhuma entidade associada a este equipamento.</p>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($fornecedores_vinculados as $f): ?>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light border rounded-3">
                                                <span class="d-block text-muted small fw-bold text-uppercase opacity-75">
                                                    <?= htmlspecialchars($f->tipo_relacao) ?>
                                                </span>
                                                <p class="text-dark fw-semibold m-0">
                                                    <?= htmlspecialchars($f->nome) ?>
                                                </p>
                                                <small class="text-muted"><?= htmlspecialchars($f->tipo) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ============================================================ -->
                        <!-- DOCUMENTOS -->
                        <!-- ============================================================ -->
                        <div class="mb-5 pt-3 border-top">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-folder-open text-success me-2"></i>Repositório de Documentação Obrigatória
                            </h5>

                            <?php if (empty($documentos)): ?>
                                <p class="text-muted small">Nenhum documento associado a este equipamento.</p>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($documentos as $doc): ?>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="fa-solid fa-file-pdf fa-2xl text-danger"></i>
                                                    <div>
                                                        <h6 class="m-0 fw-bold text-dark small">
                                                            <?= htmlspecialchars($doc->nome_documento) ?>
                                                        </h6>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($doc->tipo) ?> •
                                                            <?= date('d/m/Y', strtotime($doc->data_documento)) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    
                                                    <?php if (!empty($doc->ficheiro_path)): ?>
                                                        <a href="<?= BASE_URL . '/uploads/' . urlencode($doc->ficheiro_path) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-white border shadow-sm rounded-circle text-secondary"
                                                            title="Ver Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (!empty($doc->url_externo)): ?>
                                                        <a href="<?= htmlspecialchars($doc->url_externo) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-white border shadow-sm rounded-circle text-secondary"
                                                            title="Ver Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ============================================================ -->
                        <!-- GARANTIAS / CONTRATOS DE MANUTENÇÃO -->
                        <!-- ============================================================ -->
                        <div class="pt-3 border-top">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-file-signature text-success me-2"></i>Apólices de Garantia e Contratos de Manutenção
                            </h5>

                            <?php if (empty($garantias)): ?>
                                <p class="text-muted small">Nenhuma garantia ou contrato associado a este equipamento.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle small text-secondary">
                                        <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">
                                            <tr>
                                                <th>Nº Referência</th>
                                                <th>Tipo de Contrato</th>
                                                <th>Fornecedor</th>
                                                <th>Data Início</th>
                                                <th>Vigência Fim</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($garantias as $g): ?>
                                                <?php
                                                $hoje = new DateTime();
                                                $fim = new DateTime($g->data_fim);
                                                $expirado = $fim < $hoje;
                                                ?>
                                                <tr>
                                                    <td class="fw-bold text-dark">#<?= htmlspecialchars($g->num_contrato) ?></td>
                                                    <td><?= htmlspecialchars($g->tipo) ?></td>
                                                    <td><?= htmlspecialchars($g->fornecedor_nome ?? 'N/D') ?></td>
                                                    <td><?= date('d/m/Y', strtotime($g->data_inicio)) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($g->data_fim)) ?></td>
                                                    <td>
                                                        <span class="badge <?= $expirado ? 'bg-danger' : 'bg-success' ?> text-white px-2 rounded-pill">
                                                            <?= $expirado ? 'Expirado' : 'Ativa e Válida' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="../garantias/detalhes.php?id=<?= urlencode(aes_encrypt($g->id)) ?>"
                                                            class="btn btn-sm btn-outline-secondary rounded-2" title="Ver detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                <?php endif; ?>

            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>