<?php include '../../includes/head/views.php'; ?>

    <?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">

                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Inventário de Equipamentos Médicos</h2>
                            <p class="text-muted small m-0">Controlo de dispositivos ativos, criticidade e estados
                                operacionais.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Registar Equipamento
                        </a>
                    </div>

                    <!-- Filtro -->
                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form action="equipamentos.php" method="GET" class="row g-2 align-items-center small">

                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <input type="text" id="pesquisa_equipamento" name="pesquisa"
                                        class="form-control border-start-0 ps-0"
                                        placeholder="Pesquisar por ID, Equipamento ou Nº Série...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <select id="filtro_estado_equipamento" name="estado" class="form-select">
                                    <option value="">Todos os Estados Operacionais</option>
                                    <option value="Operacional">Operacional</option>
                                    <option value="Avariado">Avariado</option>
                                    <option value="Manutencao">Em Manutenção</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill fw-medium">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle border-top">

                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th scope="col">Código Interno</th>
                                    <th scope="col">Equipamento / Modelo</th>
                                    <th scope="col">Serviço</th>
                                    <th scope="col">Criticidade Clínica</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>

                            <tbody class="small text-secondary">
                                <tr>
                                    <td class="fw-bold text-dark">[Código Interno]</td>

                                    <td>[Nome Equipamento] <span class="text-muted small d-block">[Dräger] |
                                            [Modelo]</span></td>

                                    <td>[Serviço]</td>

                                    <td>
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill fw-medium">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>[Criticidade]
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3 fw-medium">[Estado]</span>
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php" class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Ver Detalhes"><i class="fa-solid fa-eye"></i></a>
                                            <a href="editar.php" class="btn btn-sm btn-outline-success rounded-2"
                                                title="Editar"><i class="fa-solid fa-pen"></i></a>
                                            <a href="apagar.php"
                                                class="btn btn-sm btn-outline-danger rounded-2 btn-delete-equipment"
                                                title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                            <a href="arquivar.php" class="btn btn-sm btn-outline-primary rounded-2"
                                                title="Arquivar"><i class="fa-solid fa-box-archive"></i></a>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>

                        </table>

                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3 pb-2 border-bottom">
                            <div>
                                <h3 class="fw-bold text-dark m-0">
                                    <i class="fa-solid fa-box-archive text-primary me-2"></i>
                                    Equipamentos Arquivados
                                </h3>
                                <p class="text-muted small m-0">
                                    Equipamentos retirados de circulação ou descontinuados.
                                </p>
                            </div>
                        </div>

                        <table class="table table-hover align-middle border-top">

                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th scope="col">Código Interno</th>
                                    <th scope="col">Equipamento / Modelo</th>
                                    <th scope="col">Serviço</th>
                                    <th scope="col">Criticidade Clínica</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="text-end">Detalhes</th>
                                </tr>
                            </thead>

                            <tbody class="small text-secondary">
                                <tr>
                                    <td class="fw-bold text-dark">[Código Interno]</td>

                                    <td>[Nome Equipamento] <span class="text-muted small d-block">[Dräger] |
                                            [Modelo]</span></td>

                                    <td>[Serviço]</td>

                                    <td>
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill fw-medium">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>[Criticidade]
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3 fw-medium">[Estado]</span>
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-group gap-1">
                                            <a href="detalhes.php" class="btn btn-sm btn-outline-secondary rounded-2"
                                                title="Ver Detalhes"><i class="fa-solid fa-eye"></i></a>
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

<?php include '../../includes/footer.php'; ?> 