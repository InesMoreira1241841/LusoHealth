<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">
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
                            <a href="editar.php" class="btn btn-success btn-sm rounded-pill px-3 fw-medium">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Editar Equipamento
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 text-secondary small mb-5">
                        <div class="col-md-4">
                            <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Equipamento</span>
                            <p class="text-dark fw-bold fs-5 m-0">Ventilador Pulmonar (Evita 4)</p>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Criticidade Atribuída</span>
                            <p class="m-0 mt-1">
                                <span class="badge bg-danger text-white rounded-pill px-2 py-1">Suporte de Vida</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted small fw-bold text-uppercase opacity-75">Fornecedor Responsável</span>
                            <p class="text-dark fw-semibold m-0 mt-1">
                                <i class="fa-solid fa-industry me-1 text-success"></i> Dräger Portugal Lda.
                            </p>
                        </div>
                    </div>

                    <div class="mb-5 pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-folder-open text-success me-2"></i>Repositório de Documentação Obrigatória
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fa-solid fa-file-pdf fa-2xl text-danger"></i>
                                        <div>
                                            <h6 class="m-0 fw-bold text-dark small">Manual_Utilizador_V1.pdf</h6>
                                            <small class="text-muted">Manual Clínico de Operação • 5.4 MB</small>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-white border shadow-sm rounded-circle text-secondary">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fa-solid fa-award fa-2xl text-primary"></i>
                                        <div>
                                            <h6 class="m-0 fw-bold text-dark small">Certificado_Calibracao_CE.pdf</h6>
                                            <small class="text-muted">Conformidade e Segurança Elétrica • 1.2 MB</small>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-white border shadow-sm rounded-circle text-secondary">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-file-signature text-success me-2"></i>Apólices de Garantia e Contratos de Manutenção
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle small text-secondary">
                                <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">
                                    <tr>
                                        <th>Nº Referência</th>
                                        <th>Tipo de Contrato</th>
                                        <th>Data Início</th>
                                        <th>Vigência Fim</th>
                                        <th>Estado da Cobertura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-dark">#GAR-DRG-2026</td>
                                        <td>Garantia de Fábrica Integral (Peças incluídas)</td>
                                        <td>12/04/2024</td>
                                        <td>12/04/2027</td>
                                        <td>
                                            <span class="badge bg-success text-white px-2 rounded-pill">Ativa e Válida</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>

        </div>
        
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 