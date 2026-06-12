<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Serviços e Localizações Hospitalares</h2>
                            <p class="text-muted small m-0">Gestão de alas físicas, pisos e distribuição de dispositivos
                                biomédicos.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Adicionar Serviço
                        </a>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form class="row g-2 align-items-center small" id="formFiltroLocalizacoes"
                            name="form_filtro_localizacoes" method="GET">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-secondary"
                                        id="pesquisaLocalizacao" name="pesquisa_localizacao"
                                        placeholder="Pesquisar por designação, ID de edifício ou serviço...">
                                </div>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" id="btnFiltrarLocalizacoes"
                                    class="btn btn-outline-success rounded-pill fw-medium btn-sm py-2">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar Espaços
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th>ID Serviço</th>
                                    <th>Nome do Serviço / Ala</th>
                                    <th>Piso / Bloco</th>
                                    <th>Dispositivos Alocados</th>
                                    <th>Responsável Técnico</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr>
                                    <td class="fw-bold text-dark">#SRV-UCI</td>
                                    <td class="fw-medium">Unidade de Cuidados Intensivos (UCI)</td>
                                    <td>Piso 2 - Bloco Central</td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">14
                                            Dispositivos</span>
                                    </td>
                                    <td>Dr. Ricardo Santos</td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php?id=UCI"
                                                class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Visualizar Detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="editar.php?id=UCI"
                                                class="btn btn-sm btn-outline-success rounded-2"
                                                title="Editar Localização">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="apagar.php?id=UCI" class="btn btn-sm btn-outline-danger rounded-2"
                                                title="Remover Localização">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">#SRV-IMG</td>
                                    <td class="fw-medium">Serviço de Imagiologia e Radiologia</td>
                                    <td>Piso 0 - Ala Sul</td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">8
                                            Dispositivos</span>
                                    </td>
                                    <td>Eng.ª Marta Silva</td>
                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php?id=IMG"
                                                class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Visualizar Detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="editar.php?id=IMG"
                                                class="btn btn-sm btn-outline-success rounded-2"
                                                title="Editar Localização">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="apagar.php?id=IMG" class="btn btn-sm btn-outline-danger rounded-2"
                                                title="Remover Localização">
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