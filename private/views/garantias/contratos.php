<?php include '../../../assets/includes/head.php'; ?>

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
                                <tr>
                                    <td class="fw-bold text-dark">#CTR-DRG-2026</td>
                                    <td>Ventilador Dräger Evita</td>
                                    <td>Dräger Portugal Lda.</td>
                                    <td class="fw-medium text-dark">31/12/2026</td>
                                    <td><span class="badge bg-success rounded-pill px-2">Garantia Ativa</span></td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php" class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Ver Detalhes do Contrato">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2"
                                                title="Editar Contrato">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="apagar.php" class="btn btn-sm btn-outline-danger rounded-2"
                                                title="Remover Registo">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">#GAR-BBR-2025</td>
                                    <td>Bomba de Infusão B.Braun</td>
                                    <td>B. Braun Medical</td>
                                    <td class="text-danger fw-bold"><i
                                            class="fa-solid fa-triangle-exclamation me-1"></i>Expirou Há 30 Dias</td>
                                    <td><span class="badge bg-danger rounded-pill px-2">Expirado / Sem Cobertura</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php" class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Ver Detalhes do Contrato">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2"
                                                title="Editar Contrato">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="apagar.php" class="btn btn-sm btn-outline-danger rounded-2"
                                                title="Remover Registo">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 