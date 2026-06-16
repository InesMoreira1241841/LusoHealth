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

// Captura os dados dos filtros via GET (se existirem)
$pesquisa = $_GET['pesquisa'] ?? '';
$estado = $_GET['estado'] ?? '';

// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Construção da Query Dinâmica com LEFT JOIN para verificar contratos na tabela garantias
    // Usamos um COUNT para saber quantas garantias/contratos ativos (ou totais) o fornecedor tem
    $sql = "SELECT f.*, 
            COUNT(g.id) as total_contratos 
            FROM fornecedores f
            LEFT JOIN garantias g ON f.id = g.fornecedor_id
            WHERE 1=1";
    
    $params = [];
    
    // Aplicar filtro de pesquisa (NIF ou Nome)
    if (!empty($pesquisa)) {
        $sql .= " AND (f.nif LIKE :pesquisa OR f.nome LIKE :pesquisa)";
        $params[':pesquisa'] = '%' . $pesquisa . '%';
    }
    
    $sql .= " GROUP BY f.id";
    
    // Aplicar filtro do estado com base no COUNT de contratos vinculados
    if ($estado === 'Ativo') {
        $sql .= " HAVING total_contratos > 0";
    } elseif ($estado === 'Sem Contrato') {
        $sql .= " HAVING total_contratos = 0";
    }
    
    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $erro = '';
} catch (PDOException $excecao) {
    $erro = "Aconteceu um erro na ligação ou na consulta dos dados.";
    $resultados = [];
}

// Fecha a ligação
$ligacao = null; 
?>

<body class="bg-page-light">
    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <section
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Parceiros e Fornecedores Técnicos</h2>
                            <p class="text-muted small m-0">Entidades parceiras, fabricantes e prestadores de assistência biomédica.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Novo Fornecedor
                        </a>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form action="fornecedores.php" method="GET" class="row g-2 align-items-center small">

                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <input type="text" id="pesquisa_fornecedor" name="pesquisa"
                                        class="form-control border-start-0 ps-0"
                                        value="<?= htmlspecialchars($pesquisa) ?>"
                                        placeholder="Pesquisar por NIF ou Nome da Entidade...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <select id="filtro_estado" name="estado" class="form-select">
                                    <option value="">Todos os Estados de Parceria</option>
                                    <option value="Ativo" <?= $estado === 'Ativo' ? 'selected' : '' ?>>Com Contrato de Manutenção Ativo</option>
                                    <option value="Sem Contrato" <?= $estado === 'Sem Contrato' ? 'selected' : '' ?>>Sem Contrato Vinculado</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill fw-medium">
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
                                            <th>NIF / Registo</th>
                                            <th>Nome da Entidade</th>
                                            <th>Contacto Principal</th>
                                            <th>E-mail de Suporte</th>
                                            <th>Contratos Vinculados</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small text-secondary">

                                        <?php foreach ($resultados as $fornecedor) : ?>
                                            <tr>
                                                <td class="fw-bold text-dark"><?= htmlspecialchars($fornecedor->nif) ?></td>
                                                <td><?= htmlspecialchars($fornecedor->nome) ?></td>
                                                <td><?= htmlspecialchars($fornecedor->telefone ?? 'N/A') ?></td>
                                                <td>
                                                    <?php if (!empty($fornecedor->email)): ?>
                                                        <code class="text-success"><?= htmlspecialchars($fornecedor->email) ?></code>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Não associado</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($fornecedor->total_contratos > 0) : ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">
                                                            Contrato Ativo (<?= $fornecedor->total_contratos ?>)
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-3">
                                                            Sem Contrato
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group gap-1">
                                                        <a href="detalhes.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>"
                                                            class="btn btn-sm btn-outline-secondary rounded-2"
                                                            title="Ver Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        <a href="editar.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>" 
                                                            class="btn btn-sm btn-outline-success rounded-2"
                                                            title="Editar">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <a href="apagar.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>"
                                                            class="btn btn-sm btn-outline-danger rounded-2 btn-delete-equipment"
                                                            title="Eliminar">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        
                                    </tbody>
                                </table>
                            </div>

                        <?php endif; ?> <?php endif; ?> </section>

                <div class="col mt-3">
                    <p class="mb-5">Total de Fornecedores: <strong> <?= count($resultados) ?> </strong></p>
                </div>

            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>