<?php

// Inclusão dos ficheiros base de segurança e conexão
require_once __DIR__ . '/../../includes/funcoes.php';

include '../../../assets/includes/head.php';

start_session();
redirect_if_not_logged();

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Captura o modo de visualização. Se não for especificado "arquivados", mostra os ativos (0)
$ver_arquivados = (isset($_GET['modo']) && $_GET['modo'] === 'arquivados') ? 1 : 0;

// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT
            g.id,
            g.num_contrato,
            g.tipo,
            g.data_inicio,
            g.data_fim,
            g.arquivado,
            e.designacao AS equipamento_nome,
            f.nome AS fornecedor_nome
        FROM garantias g
        LEFT JOIN equipamentos e ON g.equipamento_id = e.id
        LEFT JOIN fornecedores f ON g.fornecedor_id = f.id
        WHERE g.arquivado = :arquivado
        ORDER BY g.num_contrato ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        ':arquivado' => $ver_arquivados
    ]);

    $lista_garantias = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Erro ao aceder à base de dados.";
    $lista_garantias = [];
}

$ligacao = null;
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="btn-group shadow-sm rounded-pill p-1 bg-light border" role="group">
                        <a href="contratos.php" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 0 ? 'btn-success shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-folder-open me-1"></i> Ativas
                        </a>
                        <a href="contratos.php?modo=arquivados" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 1 ? 'btn-danger shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-box-archive me-1"></i> Arquivadas
                        </a>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Garantias e Contratos de Manutenção</h2>
                            <p class="text-muted small m-0">Gestão de coberturas técnicas, apólices de fábrica e contratos de assistência ativa.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Adicionar Contrato / Garantia
                        </a>
                    </div>

                    <?php if (!empty($success_message)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle" id="tabela">

                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center">Nº Contrato / Apólice</th>
                                    <th class="text-center">Equipamento Vinculado</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Vigência (Início | Fim)</th>
                                    <th class="text-center">Entidade Responsável</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody class="small text-secondary">

                                <?php foreach ($lista_garantias as $contrato) : ?>

                                    <tr>
                                        <td class="text-center font-monospace fw-bold text-dark">
                                            <?= htmlspecialchars($contrato->num_contrato) ?>
                                        </td>
                                        <td class="text-center text-dark">
                                            <?= !empty($contrato->equipamento_nome)
                                                ? htmlspecialchars($contrato->equipamento_nome)
                                                : '<span class="text-muted">Sem equipamento</span>' ?>
                                        </td>
                                        <td class="text-center">
                                            <span class=" badge <?= ($contrato->tipo === 'Garantia de Fábrica') ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' ?> border-0">
                                                <?= htmlspecialchars($contrato->tipo) ?>
                                            </span>
                                        </td>
                                        <td class="text-center font-monospace">
                                            <?= htmlspecialchars($contrato->data_inicio) ?> | <?= htmlspecialchars($contrato->data_fim) ?>
                                        </td>
                                        <td class="text-center text-dark">
                                            <?= !empty($contrato->fornecedor_nome)
                                                ? htmlspecialchars($contrato->fornecedor_nome)
                                                : '<span class="text-muted">Sem fornecedor</span>' ?>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group gap-1">
                                                <a href="detalhes.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-secondary rounded-2" title="Visualizar Detalhes">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <?php if ($ver_arquivados === 0): ?>

                                                    <a href="editar.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-success rounded-2" title="Editar">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="arquivar.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-danger rounded-2"
                                                        title="Arquivar" onclick="return confirm('Tem a certeza que deseja arquivar este contrato?');">
                                                        <i class="fa-solid fa-box-archive"></i>
                                                    </a>

                                                <?php else: ?>
                                                    <a href="desarquivar.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-primary rounded-2"
                                                        title="Desarquivar" onclick="return confirm('Deseja restaurar este contrato?');">
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