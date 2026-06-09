<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Modificar Serviço <span class="text-success">#SRV-UCI</span>
                        </h2>
                        <p class="text-muted small m-0">Atualize os dados estruturais ou altere a responsabilidade da
                            ala.</p>
                    </div>

                    <form action="localizacoes.php" method="POST" id="formEditarLocalizacao"
                        name="form_editar_localizacao" class="small text-secondary">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="editCodigoLocalizacao" class="form-label fw-bold text-dark">Código da
                                    Localização</label>
                                <input type="text" class="form-control font-monospace text-uppercase bg-light"
                                    id="editCodigoLocalizacao" name="codigo_localizacao" value="SRV-UCI" readonly>
                            </div>

                            <div class="col-md-8">
                                <label for="editNomeLocalizacao" class="form-label fw-bold text-dark">Nome do Serviço /
                                    Ala</label>
                                <input type="text" class="form-control" id="editNomeLocalizacao" name="nome_localizacao"
                                    value="Unidade de Cuidados Intensivos" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editEdificioLocalizacao" class="form-label fw-bold text-dark">Edifício /
                                    Bloco</label>
                                <select class="form-select" id="editEdificioLocalizacao" name="edificio_localizacao"
                                    required>
                                    <option value="A">Bloco A - Geral</option>
                                    <option value="B" selected>Bloco B - Cirúrgico</option>
                                    <option value="C">Bloco C - Pediatria e Urgências</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editPisoLocalizacao" class="form-label fw-bold text-dark">Piso / Piso
                                    Técnico</label>
                                <input type="number" class="form-control" id="editPisoLocalizacao"
                                    name="piso_localizacao" value="2" min="-2" max="7" required>
                            </div>

                            <div class="col-12 pt-3 border-top mt-4 d-flex gap-2 justify-content-end">
                                <a href="localizacoes.php"
                                    class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnAtualizarLocalizacao"
                                    class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Dados
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?> 