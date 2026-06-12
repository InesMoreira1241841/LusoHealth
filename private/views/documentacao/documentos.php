<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            
            <?php include '../../../assets/includes/sidebar/documentacao.php' ?>

            <main class="col-md-9 col-lg-10">
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

                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form class="row g-2 align-items-center small">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-secondary"
                                        placeholder="Pesquisar documento ou código de inventário...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select text-secondary">
                                    <option value="">Todas as Tipologias</option>
                                    <option value="Manual">Manual do Utilizador / Serviço</option>
                                    <option value="Calibracao">Certificado de Calibração</option>
                                    <option value="Conformidade">Declaração de Conformidade CE</option>
                                    <option value="Relatorio">Relatório Técnico</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select text-secondary">
                                    <option value="">Todos os Estados</option>
                                    <option value="Valido">Dentro da Validade</option>
                                    <option value="Expirado">Documento Expirado / Alerta</option>
                                    <option value="N/A">Sem Validade Aplicável</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="button" class="btn btn-outline-success rounded-pill fw-medium btn-sm py-2">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border border-light-subtle">
                            <thead class="table-light text-secondary small text-uppercase font-monospace" style="font-size: 0.75rem;">
                                <tr>
                                    <th>Tipo de Documento</th>
                                    <th>Nome do Ficheiro</th>
                                    <th>Equipamento Alvo</th>
                                    <th>Data de Validade</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="small text-secondary">
                                <tr>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2 px-2 py-1 fw-bold">
                                            Manual de Serviço
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        <i class="fa-regular fa-file-pdf text-danger me-2 fs-6"></i>manual_tecnico_evita_v500.pdf
                                    </td>
                                    <td><span class="badge bg-light text-dark border font-monospace">#INV-0091</span> Ventilador Dräger Evita</td>
                                    <td><span class="text-muted font-monospace">N/A</span></td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="#" class="btn btn-sm btn-outline-secondary rounded-2" title="Descarregar Ficheiro">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                            <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2" title="Editar Metadados">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="apagar.php" class="btn btn-sm btn-outline-danger rounded-2" title="Remover Registo">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span 
                                        class="badge badge-calibracao border px-2 py-1 rounded-3 fw-medium">
                                            Certificado Calibração
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        <i class="fa-regular fa-file-pdf text-danger me-2 fs-6"></i>calibracao_zoll_2026.pdf
                                    </td>
                                    <td><span class="badge bg-light text-dark border font-monospace">#INV-1102</span> Desfibrilhador Zoll R</td>
                                    <td class="text-danger fw-bold font-monospace">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>31/12/2026
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="#" class="btn btn-sm btn-outline-secondary rounded-2" title="Descarregar Ficheiro">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                            <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2" title="Editar Metadados">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="apagar.php" class="btn btn-sm btn-outline-danger rounded-2" title="Remover Registo">
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