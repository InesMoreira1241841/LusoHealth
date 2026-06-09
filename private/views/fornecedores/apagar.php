<?php include '../../includes/head/views.php'; ?>

<?php include '../../includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle d-flex align-items-center justify-content-center main-container-height custom-card-rounded">

                    <div class="text-center p-4 bg-danger-subtle border border-danger-subtle rounded-4 shadow-sm mx-auto"
                        style="max-width: 550px;">
                        <i class="fa-solid fa-building-circle-xmark text-danger fa-3xl mb-3"></i>
                        <h4 class="fw-bold text-danger">Remover Fornecedor do Sistema?</h4>

                        <p class="text-muted small my-3 lh-base">
                            Tem a certeza que deseja excluir a entidade <strong>Dräger Portugal Lda.</strong>? Esta
                            operação quebrará os links de suporte de todos os ventiladores e incubadoras associados na
                            base de dados do hospital.
                        </p>

                        <form action="fornecedores.html" method="POST" class="mt-4">
                            <input type="hidden" id="id_fornecedor_eliminar" name="id_fornecedor" value="1">

                            <div class="d-flex gap-2 justify-content-center">
                                <a href="fornecedores.html" class="btn btn-light border rounded-pill px-4 fw-medium">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm">
                                    Confirmar Eliminação
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </main>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?> 