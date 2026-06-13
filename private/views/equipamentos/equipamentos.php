<?php

// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado

include '../../../assets/includes/head.php';

// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $resultados = $ligacao->query("SELECT * FROM equipamentos")->fetchAll(PDO::FETCH_OBJ);
    $erro = '';
} catch (PDOException $err) {
    $erro = "Aconteceu um erro na ligação.";
    $resultados = [];
}
// Fecha a ligação
$ligacao = null;


?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

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

                    <?php if (!empty($erro)) : ?>
                        <p class="text-center text-danger"><?= $erro ?></p>
                    <?php else : ?>
                        <?php if (count($resultados) == 0) : ?>
                            <p class="shadow-sm border rounded-3 custom-card-rounded mb-4 border-light-subtle text-center p-4">Não existem clientes registados.</p>
                        <?php else : ?>

                            <div class="table-responsive">

                                <table id="tabela-equipamentos" class="table table-hover align-middle border-top">

                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th scope="col">Equipamento</th>
                                            <th scope="col">Código Interno</th>
                                            <th scope="col">Marca | Modelo</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Criticidade Clínica</th>
                                            <th scope="col" class="text-end">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody class="small text-secondary">

                                        <?php foreach ($resultados as $equipamentos) : ?>

                                            <tr>
                                                <td><?= $equipamentos->designacao ?></td>
                                                <td><?= $equipamentos->codigo_inventario ?></td>
                                                <td><?= $equipamentos->marca . ' | ' . $equipamentos->modelo ?></td>
                                                <td><?= $equipamentos->estado ?></td>
                                                <td><?= $equipamentos->criticidade ?></td>
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
                                        <?php endforeach; ?>
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

                            <?php endif; ?> <!-- Fecha o if (count($resultados) == 0) -->
                        <?php endif; ?> <!-- Fecha o if (!empty($erro)) -->

                        <div class="col">
                            <p class="mb-5">Total de Equipamentos: <strong> <?= count($resultados) ?> </strong></p>
                        </div>

                            </div>

            </main>

        </div>

    </div>

    <script>
        // tradução para português
        $(document).ready(function() {
            // datatable
            $('#tabela-clientes').DataTable({
                pageLength: 5,
                pagingType: "full_numbers",
                language: {
                    decimal: "",
                    emptyTable: "Sem dados disponíveis na tabela.",
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                    infoEmpty: "Mostrando 0 até 0 de 0 registos",
                    infoFiltered: "(Filtrando _MAX_ total de registos)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrando _MENU_ registos por página.",
                    loadingRecords: "Carregando...",
                    processing: "Processando...",
                    search: "Filtrar:",
                    zeroRecords: "Nenhum registro encontrado.",
                    paginate: {
                        first: "Primeira",
                        last: "Última",
                        next: "Seguinte",
                        previous: "Anterior"
                    },
                    aria: {
                        sortAscending: ": ative para classificar a coluna em ordem crescente.",
                        sortDescending: ": ative para classificar a coluna em ordem decrescente."
                    }
                }
            });
        })
    </script>


    <?php include '../../../assets/includes/footer.php'; ?>