<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de detalhes
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

// 1. Capturar e desencriptar o ID da localização vindo do GET
$idLocEncrypted = $_GET['id'] ?? null;
$idLoc = aes_decrypt($idLocEncrypted);

if (!$idLoc || !is_numeric($idLoc)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/localizacoes.php');
    exit;
}

$erros = [];
$localizacao = null;
$equipamentos = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query A: Obter dados estruturais da Localização
    $stmtLoc = $ligacao->prepare("SELECT * FROM localizacoes WHERE id = :id");
    $stmtLoc->bindParam(':id', $idLoc, PDO::PARAM_INT);
    $stmtLoc->execute();
    $localizacao = $stmtLoc->fetch(PDO::FETCH_OBJ);

    // Se a localização não existir no sistema, aborta imediatamente
    if (!$localizacao) {
        header('Location: ' . BASE_URL . '/private/views/localizacoes/localizacoes.php');
        exit;
    }

    // Query B: Obter a lista de equipamentos alocados a esta localização específica
    // (Nota: Adapta os nomes das colunas e da tabela de equipamentos conforme a tua BD)
    $stmtEquip = $ligacao->prepare("SELECT * FROM equipamentos WHERE localizacao_id = :id_loc ORDER BY designacao ASC");
    $stmtEquip->bindParam(':id_loc', $idLoc, PDO::PARAM_INT);
    $stmtEquip->execute();
    $equipamentos = $stmtEquip->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro ao carregar os dados da infraestrutura: " . $err->getMessage();
}

$ligacao = null;

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger text-center mb-3 shadow-sm" role="alert">
                        <?php foreach ($erros as $erro): ?>
                            <div><i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Ficha Informativa da Localização</h2>
                            <p class="text-muted small m-0">Equipamentos alocados e infraestrutura técnica da ala.</p>
                        </div>
                        <a href="localizacoes.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <div class="row g-4 text-secondary small mb-5">

                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Ala Clínica / Serviço</span>
                            <p class="text-dark fw-bold fs-5 m-0"><?= htmlspecialchars($localizacao->nome) ?></p>
                            <span class="badge bg-success-subtle text-success border font-monospace mt-1"><?= htmlspecialchars($localizacao->codigo) ?></span>
                        </div>

                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Infraestrutura Física</span>
                            <p class="text-dark fw-semibold m-0">
                                <i class="fa-solid fa-layer-group me-1 text-success"></i> Piso <?= htmlspecialchars($localizacao->piso) ?> • Edifício <?= htmlspecialchars($localizacao->edificio) ?>
                            </p>
                            <?php if (!empty($localizacao->observacoes)): ?>
                                <p class="text-muted m-0 mt-1" style="font-size: 0.8rem;"><i class="fa-solid fa-circle-info text-muted me-1"></i> <?= htmlspecialchars($localizacao->observacoes) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Responsabilidade Técnica</span>
                            <p class="text-dark fw-semibold m-0"><i class="fa-solid fa-user-shield me-1 text-success"></i> <?= htmlspecialchars($localizacao->responsavel) ?></p>
                        </div>

                    </div>

                    <div class="pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-heart-pulse text-success me-2"></i>Dispositivos Médicos Atualmente Alocados
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle small text-secondary">
                                <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">
                                    <tr>
                                        <th>Nº Série</th>
                                        <th>Equipamento</th>
                                        <th>Marca | Modelo</th>
                                        <th>Criticidade</th>
                                        <th>Estado Técnico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($equipamentos)): ?>
                                        <?php foreach ($equipamentos as $equip): ?>
                                            <tr>
                                                <td class="fw-bold text-dark font-monospace"><?= htmlspecialchars($equip->num_serie) ?></td>
                                                <td><?= htmlspecialchars($equip->designacao) ?></td>
                                                <td><?= htmlspecialchars($equip->marca . ' | ' . $equip->modelo) ?></td>
                                                <td>
                                                    <?php
                                                    // Exemplo de badges de criticidade dinâmicas
                                                    if (($equip->criticidade ?? '') === 'Alta'): ?>
                                                        <span class="badge bg-danger text-white rounded-pill px-2">Suporte de Vida</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark rounded-pill px-2">Padrão Clínico</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success border rounded-3 px-2">
                                                        <?= htmlspecialchars($equip->estado ?? 'Operacional') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                <i class="fa-solid fa-box-open me-2"></i>Nenhum dispositivo médico alocado a esta localização neste momento.
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