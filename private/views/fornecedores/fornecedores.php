<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
?>

<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

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
                            <p class="text-muted small m-0">Entidades parceiras, fabricantes e prestadores de
                                assistência biomédica.</p>
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
                                        placeholder="Pesquisar por NIF, Razão Social ou Marca...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <select id="filtro_estado" name="estado" class="form-select">
                                    <option value="">Todos os Estados de Parceria</option>
                                    <option value="Ativo">Com Contrato de Manutenção Ativo</option>
                                    <option value="Sem Contrato">Sem Contrato Vinculado</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill fw-medium">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                                </button>
                            </div>

                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th>NIF / Registo</th>
                                    <th>Nome da Entidade</th>
                                    <th>Contacto Principal</th>
                                    <th>E-mail de Suporte</th>
                                    <th>Contratos Ativos</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr>
                                    <td class="fw-bold text-dark">NIF-501234567</td>
                                    <td>Dräger Portugal Lda.</td>
                                    <td>+351 211 543 200</td>
                                    <td><code class="text-success">suporte.pt@draeger.com</code></td>
                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">
                                            Contrato Ativo
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <div class="btn-group gap-1">
                                                <a href="detalhes.php"
                                                    class="btn btn-sm btn-outline-secondary rounded-2"
                                                    title="Ver Detalhes"><i class="fa-solid fa-eye"></i></a>
                                                <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2"
                                                    title="Editar"><i class="fa-solid fa-pen"></i></a>
                                                <a href="apagar.php"
                                                    class="btn btn-sm btn-outline-danger rounded-2 btn-delete-equipment"
                                                    title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                            
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>

                </section>

            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>