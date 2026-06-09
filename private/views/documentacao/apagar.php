<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row g-4">

        <?php include '../../includes/sidebar/documentacao.php' ?>

        <main class="col-md-9 col-lg-10">
            <div
                class="bg-white p-5 shadow-sm border border-light-subtle main-container-height custom-card-rounded d-flex align-items-center justify-content-center">

                <div class="text-center" style="max-width: 550px;">
                    <div class="mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-danger fa-4x animate-pulse"></i>
                    </div>

                    <h3 class="fw-bold text-dark mb-2">Remover Documento Técnico?</h3>
                    <p class="text-muted small mb-4">
                        Está prestes a eliminar permanentemente o registo e o ficheiro associado do sistema. Esta ação
                        pode afetar o histórico de conformidade do dispositivo médico.
                    </p>

                    <div class="bg-light p-3 border rounded-3 text-start mb-4 small text-secondary">
                        <div class="mb-2">
                            <strong class="text-dark">Ficheiro:</strong>
                            <span class="font-monospace text-danger ms-1"><i
                                    class="fa-regular fa-file-pdf me-1"></i>calibracao_zoll_2026.pdf</span>
                        </div>
                        <div class="mb-2">
                            <strong class="text-dark">Tipologia:</strong>
                            <span
                                class="badge bg-info-subtle text-info-emphasis border border-info-subtle ms-1 fw-bold">Certificado
                                Calibração</span>
                        </div>
                        <div>
                            <strong class="text-dark">Equipamento Vinculado:</strong>
                            <span class="ms-1">Desfibrilhador Zoll R (<span
                                    class="font-monospace bg-white border px-1 rounded">#INV-1102</span>)</span>
                        </div>
                    </div>

                    <form action="documentos.php" method="POST" id="formApagarDocumento" name="form_apagar_documento">

                        <input type="hidden" id="apagarDocumentoID" name="documento_id" value="DOC-2026-0042">

                        <div class="d-flex gap-3 justify-content-center">
                            <a href="documentos.php" class="btn btn-light border rounded-pill px-4 fw-medium small">
                                Cancelar, Manter
                            </a>
                            <button type="submit" id="btnConfirmarApagar"
                                class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm small">
                                <i class="fa-solid fa-trash-can me-2"></i>Confirmar Eliminação
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>