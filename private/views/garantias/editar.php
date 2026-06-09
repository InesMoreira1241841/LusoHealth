<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">
                    <div class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Modificar Contrato / Garantia</h2>
                            <p class="text-muted small m-0">Atualize as datas de vigência ou renove coberturas de assistência técnica vencidas.</p>
                        </div>
                        <a href="contratos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <form action="contratos.php" method="POST" id="formEditarContrato" name="form_editar_contrato" class="row g-3 fw-medium text-secondary small">
                        
                        <input type="hidden" id="editarContratoID" name="contrato_id_original" value="SLA-ZOLL-045">

                        <div class="col-md-4">
                            <label for="numContrato" class="form-label text-dark fw-bold">Número do Contrato / Apólice</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-dark fw-bold" id="numContrato" name="num_contrato" value="SLA-ZOLL-045" required>
                        </div>

                        <div class="col-md-4">
                            <label for="contratoDataInicio" class="form-label text-dark fw-bold">Data de Início</label>
                            <input type="date" class="form-control font-monospace" id="contratoDataInicio" name="contrato_data_inicio" value="2024-01-01" required>
                        </div>

                        <div class="col-md-4">
                            <label for="contratoDataFim" class="form-label text-dark fw-bold">Data de Fim de Vigência</label>
                            <input type="date" class="form-control font-monospace border-danger text-danger fw-bold" id="contratoDataFim" name="contrato_data_fim" value="2026-01-01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contratoEquipamentoAlvo" class="form-label text-dark fw-bold">Equipamento Vinculado</label>
                            <select class="form-select text-secondary" id="contratoEquipamentoAlvo" name="contrato_equipamento_alvo" required>
                                <option value="INV-0091">#INV-0091 - Ventilador Pulmonar</option>
                                <option value="INV-1102" selected>#INV-1102 - Desfibrilhador Zoll R</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="entidadeResponsavel" class="form-label text-dark fw-bold">Entidade Técnica Responsável</label>
                            <select class="form-select text-secondary" id="entidadeResponsavel" name="entidade_responsavel" required>
                                <option value="Drager">Dräger Portugal Lda.</option>
                                <option value="Philips">Philips Medical Systems S.A.</option>
                                <option value="Zoll" selected>Zoll Medical Portugal</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="contratoClausulas" class="form-label text-dark fw-bold">Cláusulas de Manutenção Preventiva e Observações</label>
                            <textarea class="form-control" id="contratoClausulas" name="contrato_clausulas" rows="3">Contrato SLA de cobertura total contra danos acidentais. Inclui substituição preventiva de baterias a cada 24 meses e equipa técnica no local num prazo máximo de 4 horas após a abertura do chamado crítico.</textarea>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Descartar</a>
                            <button type="submit" id="btnAtualizarContrato" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?> 