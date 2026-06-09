<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-5 shadow-sm border border-light-subtle main-container-height custom-card-rounded d-flex align-items-center justify-content-center">
                    
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fa-solid fa-circle-exclamation text-danger fa-4x"></i>
                        </div>
                        
                        <h3 class="fw-bold text-dark mb-2">Revogar ou Remover Contrato?</h3>
                        <p class="text-muted small mb-4">
                            Esta ação irá remover de forma permanente os dados de cobertura técnica do equipamento do sistema. O dispositivo passará a figurar como "Sem Cobertura Ativa".
                        </p>

                        <div class="bg-light p-3 border rounded-3 text-start mb-4 small text-secondary">
                            <div class="mb-2">
                                <strong class="text-dark">ID Contrato:</strong> 
                                <span class="font-monospace text-danger ms-1 fw-bold">SLA-ZOLL-045</span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-dark">Equipamento:</strong> 
                                <span class="ms-1">Desfibrilhador Zoll R (#INV-1102)</span>
                            </div>
                            <div>
                                <strong class="text-dark">Entidade:</strong> 
                                <span class="ms-1">Zoll Medical Portugal</span>
                            </div>
                        </div>

                        <form action="contratos.php" method="POST" id="formApagarContrato" name="form_apagar_contracto">
                            
                            <input type="hidden" id="apagarContratoID" name="contrato_id" value="SLA-ZOLL-045">

                            <div class="d-flex gap-3 justify-content-center">
                                <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium small">
                                    Cancelar, Manter
                                </a>
                                <button type="submit" id="btnConfirmarApagarContrato" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm small">
                                    <i class="fa-solid fa-trash me-2"></i>Confirmar Eliminação
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?> 