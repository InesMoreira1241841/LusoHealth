<?php

// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de listagem
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

include '../../../assets/includes/head.php';

// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fazemos um LEFT JOIN para trazer os nomes legíveis do Equipamento e Fornecedor se necessário,
    // ou podes manter apenas o "SELECT *" da tabela garantias.
    $query = "SELECT g.*, e.designacao AS equipamento_nome, f.nome AS fornecedor_nome 
              FROM garantias g
              LEFT JOIN equipamentos e ON g.equipamento_id = e.id
              LEFT JOIN fornecedores f ON g.fornecedor_id = f.id";
              
    $resultados = $ligacao->query($query)->fetchAll(PDO::FETCH_OBJ);
    $erro = '';
} catch (PDOException $erro) {
    $erro = "Aconteceu um erro na ligação às garantias e contratos.";
    $resultados = [];
}

// Fecha a ligação
$ligacao = null;
?>

<body class="bg-page-light">
    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">

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

                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form class="row g-2 align-items-center small" id="formFiltroGarantias" name="form_filtro_garantias" method="GET">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-secondary" id="pesquisaGarantia" name="pesquisa_garantia" placeholder="Pesquisar por número de contrato, equipamento ou entidade técnica...">
                                </div>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" id="btnFiltrarGarantias" class="btn btn-outline-success rounded-pill fw-medium btn-sm py-2">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar Contratos
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($erro)) : ?>
                        <p class="text-center text-danger"><?= $erro ?></p>
                    <?php else : ?>
                        <?php if (count($resultados) == 0) : ?>
                            <p class="shadow-sm border rounded-3 custom-card-rounded mb-4 border-light-subtle text-center p-4">Não existem garantias ou contratos registados.</p>
                        <?php else : ?>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th>Nº Contrato / Apólice</th>
                                            <th>Equipamento Vinculado</th>
                                            <th>Tipo</th>
                                            <th>Vigência (Início | Fim)</th>
                                            <th>Entidade Responsável</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody class="small text-secondary">
                                        <?php foreach ($resultados as $contrato) : ?>
                                            <tr>
                                                <td class="font-monospace fw-bold text-dark"><?= htmlspecialchars($contrato->num_contrato) ?></td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">#<?= $contrato->equipamento_id ?></span> 
                                                    <?= htmlspecialchars($contrato->equipamento_nome ?? 'Equipamento Desconhecido') ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= ($contrato->tipo === 'Garantia de Fábrica') ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' ?> border-0">
                                                        <?= htmlspecialchars($contrato->tipo) ?>
                                                    </span>
                                                </td>
                                                <td class="font-monospace">
                                                    <?= date('d/m/Y', strtotime($contrato->data_inicio)) ?> | <?= date('d/m/Y', strtotime($contrato->data_fim)) ?>
                                                </td>
                                                <td><?= htmlspecialchars($contrato->fornecedor_nome ?? 'Não Atribuído') ?></td>

                                                <td class="text-end">
                                                    <div class="btn-group gap-1">
                                                        <a href="detalhes.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-secondary rounded-2" title="Visualizar Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>

                                                        <a href="editar.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-success rounded-2" title="Editar Contrato">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>

                                                        <a href="apagar.php?id=<?= aes_encrypt($contrato->id) ?>" class="btn btn-sm btn-outline-danger rounded-2" title="Remover Contrato">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        <?php endif; ?> <?php endif; ?> </div>

                <div class="col">
                    <p class="mb-5">Total de Contratos / Garantias: <strong> <?= count($resultados) ?> </strong></p>
                </div>

            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>