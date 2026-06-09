<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Modificar Fornecedor <span class="text-success">#Dräger
                                Portugal</span></h2>
                        <p class="text-muted small m-0">Mantenha os canais e e-mails de assistência biomédica sempre
                            atualizados.</p>
                    </div>

                    <form action="fornecedores.html" method="POST" class="row g-3 fw-medium text-secondary small">
                        <input type="hidden" name="id_fornecedor" value="1">

                        <div class="col-md-6">
                            <label for="edit_nome" class="form-label text-dark">Nome / Razão Social</label>
                            <input type="text" id="edit_nome" name="nome" class="form-control rounded-3"
                                value="Dräger Portugal Lda." required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_nif" class="form-label text-dark">NIF</label>
                            <input type="text" id="edit_nif" name="nif" class="form-control rounded-3" value="501234567"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_telefone" class="form-label text-dark">Telefone de Suporte Técnico</label>
                            <input type="tel" id="edit_telefone" name="telefone" class="form-control rounded-3"
                                value="+351 211 543 200" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_email" class="form-label text-dark">E-mail de Assistência Oficial</label>
                            <input type="email" id="edit_email" name="email" class="form-control rounded-3"
                                value="suporte.pt@draeger.com" required>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_morada" class="form-label text-dark">Endereço da Sede Comercial</label>
                            <input type="text" id="edit_morada" name="morada" class="form-control rounded-3"
                                value="Lisboa, Portugal">
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_nome" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-user-gear me-2"></i>Gestor de Conta / Técnico Responsável
                            </label>
                            <input type="text" id="edit_tecnico_nome" name="tecnico_nome" class="form-control rounded-3"
                                value="Eng. Carlos Mendes (Biomédica)" required>
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_telefone" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-phone-volume me-2"></i>Linha Direta do Técnico
                            </label>
                            <input type="tel" id="edit_tecnico_telefone" name="tecnico_telefone"
                                class="form-control rounded-3" value="+351 912 345 678" required>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="fornecedores.html" class="btn btn-light border rounded-pill px-4">Descartar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Ficha
                            </button>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?> 