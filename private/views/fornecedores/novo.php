<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Registar Fornecedor / Fabricante</h2>
                        <p class="text-muted small m-0">Insira os dados da entidade externa para vinculação a
                            equipamentos e contratos.</p>
                    </div>

                    <form action="fornecedores.html" method="POST" class="row g-3 fw-medium text-secondary small">
                        <div class="col-md-6">
                            <label for="nome_fornecedor" class="form-label text-dark">Nome / Razão Social</label>
                            <input type="text" id="nome_fornecedor" name="nome" class="form-control rounded-3" required
                                placeholder="Ex: Siemens Healthineers Portugal">
                        </div>

                        <div class="col-md-6">
                            <label for="nif_fornecedor" class="form-label text-dark">NIF (Número de Identificação
                                Fiscal)</label>
                            <input type="text" id="nif_fornecedor" name="nif" class="form-control rounded-3" required
                                placeholder="Ex: 500123456">
                        </div>

                        <div class="col-md-6">
                            <label for="telefone_fornecedor" class="form-label text-dark">Telefone Geral da
                                Empresa</label>
                            <input type="tel" id="telefone_fornecedor" name="telefone" class="form-control rounded-3"
                                required placeholder="Ex: +351 210 000 000">
                        </div>

                        <div class="col-md-6">
                            <label for="email_fornecedor" class="form-label text-dark">E-mail de Assistência
                                Oficial</label>
                            <input type="email" id="email_fornecedor" name="email" class="form-control rounded-3"
                                required placeholder="Ex: suporte@empresa.com">
                        </div>

                        <div class="col-md-12">
                            <label for="morada_fornecedor" class="form-label text-dark">Endereço da Sede
                                Comercial</label>
                            <input type="text" id="morada_fornecedor" name="morada" class="form-control rounded-3"
                                placeholder="Rua, Código Postal, Cidade">
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="tecnico_responsavel" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-user-gear me-2"></i>Gestor de Conta / Técnico Responsável
                            </label>
                            <input type="text" id="tecnico_responsavel" name="tecnico_nome"
                                class="form-control rounded-3" required
                                placeholder="Ex: Eng. Carlos Mendes (Biomédica)">
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="telefone_tecnico" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-phone-volume me-2"></i>Linha Direta do Técnico
                            </label>
                            <input type="tel" id="telefone_tecnico" name="tecnico_telefone"
                                class="form-control rounded-3" required placeholder="Ex: +351 912 345 678">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="fornecedores.html" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Fornecedor
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 