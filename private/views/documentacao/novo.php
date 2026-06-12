<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            
            <?php include '../../../assets/includes/sidebar/documentacao.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">
                    
                    <div class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Vincular Novo Documento Técnico</h2>
                            <p class="text-muted small m-0">Adicione manuais ou relatórios, mapeando-os diretamente ao inventário físico.</p>
                        </div>
                        <a href="documentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <form action="documentos.php" method="POST" enctype="multipart/form-data" id="formNovoDocumento" name="form_novo_documento" class="row g-3 fw-medium text-secondary small">
                        
                        <div class="col-md-6">
                            <label for="tipoDocumento" class="form-label text-dark fw-bold">Tipo de Documento</label>
                            <select class="form-select text-secondary" id="tipoDocumento" name="tipo_documento" required>
                                <option value="" selected disabled>Selecione a tipologia...</option>
                                <option value="Manual_Utilizador">Manual do Utilizador</option>
                                <option value="Manual_Tecnico">Manual de Serviço / Técnico</option>
                                <option value="Certificado_Calibracao">Certificado de Calibração / Metrologia</option>
                                <option value="Declaracao_CE">Declaração de Conformidade CE</option>
                                <option value="Relatorio_Intervencao">Relatório Técnico de Intervenção</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="equipamentoAlvo" class="form-label text-dark fw-bold">Equipamento Associado</label>
                            <select class="form-select text-secondary" id="equipamentoAlvo" name="equipamento_alvo" required>
                                <option value="" selected disabled>Vincular ao código de inventário...</option>
                                <option value="INV-0091">#INV-0091 - Ventilador Pulmonar (Dräger)</option>
                                <option value="INV-1102">#INV-1102 - Desfibrilhador R Series (Zoll)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dataEmissao" class="form-label text-dark fw-bold">Data de Emissão / Upload</label>
                            <input type="date" class="form-control text-secondary" id="dataEmissao" name="data_emissao" required>
                        </div>

                        <div class="col-md-6">
                            <label for="dataValidade" class="form-label text-dark fw-bold">Data de Validade (Se aplicável)</label>
                            <input type="date" class="form-control text-secondary" id="dataValidade" name="data_validade">
                        </div>

                        <div class="col-md-12">
                            <label for="ficheiroDocumento" class="form-label text-dark fw-bold">Ficheiro Digital (Apenas PDF de acordo com as normas)</label>
                            <input type="file" class="form-control text-secondary" id="ficheiroDocumento" name="ficheiro_documento" accept=".pdf" required>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="documentos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Voltar</a>
                            <button type="submit" id="btnCarregarDocumento" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Carregar Documento
                            </button>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 