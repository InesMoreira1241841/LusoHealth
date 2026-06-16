<?php

// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado

include '../../../assets/includes/head.php';

// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $resultados = $ligacao->query("SELECT * FROM garantias")->fetchAll(PDO::FETCH_OBJ);
    $erro = '';
} catch (PDOException $erro) {
    $erro = "Aconteceu um erro na ligação.";
    $resultados = [];
}
// Fecha a ligação
$ligacao = null;
?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="bg-white p-4 shadow-sm border border-light-subtle">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Contratos de Manutenção e Garantias Hospitalares</h2>
                            <p class="text-muted small m-0">Monitorização de apólices, vigências de marcas e SLA
                                acordados.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-file-signature me-2"></i>Registar Apólice / Contrato
                        </a>
                    </div>

                    <!-- Filtro -->
                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form class="row g-2 align-items-center small">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        placeholder="Pesquisar por Nº de Contrato ou Equipamento...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Todos os Fornecedores</option>
                                    <option value="Draeger">Dräger Portugal Lda.</option>
                                    <option value="Philips">Philips Medical Systems</option>
                                    <option value="Siemens">Siemens Healthineers</option>
                                    <option value="BBraun">B. Braun Medical</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Todos os Estados</option>
                                    <option value="Ativa">Garantia / Contrato Ativo</option>
                                    <option value="Expirada">Cobertura Expirada</option>
                                    <option value="Critico">Prestes a Caducar (< 30 dias)</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-medium">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($erro)) : ?>
                        <p class="text-center text-danger"><?= $erro ?></p>
                    <?php else : ?>
                        <?php if (count($resultados) == 0) : ?>
                            <p class="shadow-sm border rounded-3 custom-card-rounded mb-4 border-light-subtle text-center p-4">Não existem fornecedores registados.</p>
                        <?php else : ?>

                            <div class="table-responsive">

                                <table class="table table-hover align-middle">

                                    <thead class="table-light text-secondary small text-uppercase">

                                        <tr>
                                            <th>Nº Contrato</th>
                                            <th>Equipamento</th>
                                            <th>Entidade Responsável</th>
                                            <th>Fim de Vigência</th>
                                            <th>Estado Cobertura</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody class="small text-secondary">

                                        <?php foreach ($resultados as $garantias) : ?>

                                            <tr>
                                                <td class="fw-bold text-dark"><?= $garantias->num_contrato ?></td>
<!-- ainda falta colocar estes interligados com a DB
                                                <td>Ventilador Dräger Evita</td>
                                                <td>Dräger Portugal Lda.</td>
                                                <td class="fw-medium text-dark">31/12/2026</td>
                                                <td><span class="badge bg-success rounded-pill px-2">Garantia Ativa</span></td>
                                        -->
                                                
                                                <td class="text-end">

                                                    <div class="btn-group gap-1">

                                                        <a href="detalhes.php?id_garantias=<?= aes_encrypt($garantias->id) ?>"
                                                            class="btn btn-sm btn-outline-secondary rounded-2"
                                                            title="Visualizar Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>

                                                        <a href="editar.php?id_garantias=<?= aes_encrypt($garantias->id) ?>"
                                                            class="btn btn-sm btn-outline-success rounded-2"
                                                            title="Editar Garantia">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>

                                                        <a href="apagar.php?id_garantias=<?= aes_encrypt($garantias->id) ?>" 
                                                            class="btn btn-sm btn-outline-danger rounded-2"
                                                            title="Remover garantia">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>

                                                    </div>
                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php endif; ?> <!-- Fecha o if (count($resultados) == 0) -->
                    <?php endif; ?> <!-- Fecha o if (!empty($erro)) -->

                </div>

                <div class="col">
                    <p class="mb-5">Total de Garantias: <strong> <?= count($resultados) ?> </strong></p>
                </div>

            </main>

        </div>

    </div>

    <?php include '../../../assets/includes/footer.php'; ?>