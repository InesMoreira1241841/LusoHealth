<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Ficha Contratual do Parceiro</h2>
                            <p class="text-muted small m-0">Histórico de assistência técnica e garantias associadas.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="fornecedores.html" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                            </a>
                            <a href="editar.html?id=1"
                                class="btn btn-success btn-sm rounded-pill px-3 fw-medium shadow-sm">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Editar Fornecedor
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 text-secondary small mb-4">
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                                style="font-size: 0.7rem;">Entidade</span>
                            <p class="text-dark fw-bold fs-5 m-0">Dräger Portugal Lda.</p>
                            <p class="text-muted m-0 mt-1">NIF: 501234567</p>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                                style="font-size: 0.7rem;">Contactos Oficiais</span>
                            <p class="m-0 text-dark fw-medium"><i class="fa-solid fa-phone me-2 text-muted"></i>+351 211
                                543 200</p>
                            <p class="m-0 text-dark fw-medium mt-1"><i
                                    class="fa-solid fa-envelope me-2 text-muted"></i>suporte.pt@draeger.com</p>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                                style="font-size: 0.7rem;">Vigência Coletiva</span>
                            <p class="m-0 mt-1">
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3">
                                    Parceria em Conformidade
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 border row g-3 mx-0 small">
                        <div class="col-md-6 border-end border-light-subtle">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                                style="font-size: 0.7rem;">Gestor de Conta / Técnico</span>
                            <p class="text-success fw-bold m-0"><i class="fa-solid fa-user-gear me-2"></i>Eng. Carlos
                                Mendes (Biomédica)</p>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1"
                                style="font-size: 0.7rem;">Linha Direta de Suporte</span>
                            <p class="text-dark fw-bold m-0"><i
                                    class="fa-solid fa-phone-volume me-2 text-success"></i>+351 912 345 678</p>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-file-signature text-success me-2"></i>Apólices de Garantia e Contratos
                            Ativos
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle small text-secondary">
                                <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">
                                    <tr>
                                        <th>Nº Contrato</th>
                                        <th>Dispositivo Coberto</th>
                                        <th>Tipo de Cobertura</th>
                                        <th>Vencimento</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-dark">#CTR-DRG-2026</td>
                                        <td>Ventilador Pulmonar (SN-9821-X)</td>
                                        <td>Manutenção Preventiva Total (SLA 24h)</td>
                                        <td>31/12/2026</td>
                                        <td>
                                            <span class="badge bg-success text-white px-2 rounded-pill">Vigente</span>
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

<?php include '../../includes/footer.php'; ?> 