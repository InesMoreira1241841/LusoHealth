<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle">
                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Registar Dispositivo Médico</h2>
                        <p class="text-muted small m-0">Introduza os dados de inventário e atribuição de segurança
                            clínica.</p>
                    </div>

                    <form action="equipamentos.php" method="POST" class="row g-3 fw-medium text-secondary small">
                        <div class="col-md-6">
                            <label for="nome_equipamento" class="form-label text-dark">Nome do Equipamento</label>
                            <input type="text" id="nome_equipamento" name="nome" class="form-control rounded-3" required
                                placeholder="Ex: Monitor de Sinais Vitais">
                        </div>

                        <div class="col-md-6">
                            <label for="marca_equipamento" class="form-label text-dark">Marca / Fabricante</label>
                            <input type="text" id="marca_equipamento" name="marca" class="form-control rounded-3"
                                required placeholder="Ex: Philips">
                        </div>

                        <div class="col-md-6">
                            <label for="num_serie" class="form-label text-dark">Número de Série (S/N)</label>
                            <input type="text" id="num_serie" name="num_serie" class="form-control rounded-3" required
                                placeholder="Ex: SN-987654321">
                        </div>

                        <div class="col-md-6">
                            <label for="localizacao_equipamento" class="form-label text-dark">Localização / Piso</label>
                            <select id="localizacao_equipamento" name="localizacao_id" class="form-select rounded-3"
                                required>
                                <option value="">Selecione a Sala/Serviço...</option>
                                <option value="1">Urgências - Sala 3</option>
                                <option value="2">Bloco Operatório Central</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="equipamentos.php" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Equipamento
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 