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
                            <h2 class="fw-bold text-dark m-0">Modificar Metadados do Documento</h2>
                            <p class="text-muted small m-0">Atualize as especificações, prazos de validade ou altere o ficheiro técnico associado.</p>
                        </div>
                        <a href="documentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <form action="documentos.php" method="POST" enctype="multipart/form-data" id="formEditarDocumento" name="form_editar_documento" class="small text-secondary">
                        <div class="row g-3">
                            
                            <div class="col-md-6">
                                <label for="editEquipamentoAlvo" class="form-label fw-bold text-dark">Equipamento Alvo</label>
                                <select class="form-select" id="editEquipamentoAlvo" name="edit_equipamento_alvo" required>
                                    <option value="INV-0091">Ventilador Dräger Evita (#INV-0091)</option>
                                    <option value="INV-1102" selected>Desfibrilhador Zoll R (#INV-1102)</option>
                                    <option value="INV-0455">Monitor Multiparamétrico Philips (#INV-0455)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editTipoDocumento" class="form-label fw-bold text-dark">Tipologia Documental</label>
                                <select class="form-select" id="editTipoDocumento" name="edit_tipo_documento" required>
                                    <option value="Manual">Manual do Utilizador / Serviço</option>
                                    <option value="Calibracao" selected>Certificado de Calibração / Ensaio</option>
                                    <option value="Conformidade">Declaração de Conformidade CE</option>
                                    <option value="Relatorio">Relatório de Manutenção Preventiva</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editFicheiroDocumento" class="form-label fw-bold text-dark">Substituir Ficheiro (Opcional - PDF, Max 10MB)</label>
                                <input type="file" class="form-control" id="editFicheiroDocumento" name="edit_ficheiro_documento" accept=".pdf">
                                <div class="form-text text-dark-emphasis fw-medium mt-2">
                                    <i class="fa-regular fa-file-pdf text-danger me-1"></i> Ficheiro atual: <span class="font-monospace text-decoration-underline">calibracao_zoll_2026.pdf</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="editDataValidade" class="form-label fw-bold text-dark">Data de Validade</label>
                                <input type="date" class="form-control font-monospace" id="editDataValidade" name="edit_data_validade" value="2026-12-31">
                                <div class="form-text">Mantenha atualizado para evitar falhas em auditorias clínicas.</div>
                            </div>

                            <div class="col-12">
                                <label for="editNotasDocumento" class="form-label fw-bold text-dark">Notas Técnicas / Observações</label>
                                <textarea class="form-control" id="editNotasDocumento" name="edit_notas_documento" rows="3" placeholder="Insira detalhes adicionais sobre o documento...">Certificado emitido após calibração anual dos módulos de pacing e ECG do desfibrilhador.</textarea>
                            </div>

                            <div class="col-12 pt-3 border-top mt-4 d-flex gap-2 justify-content-end">
                                <a href="documentos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnAtualizarDocumento" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>Guardar Alterações
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </main>

        </div>

    </div>

<?php include '../../../assets/includes/footer.php'; ?> 