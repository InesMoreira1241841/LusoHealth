<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded d-flex align-items-center justify-content-center">
                    
                    <div class="text-center p-4 bg-danger-subtle border border-danger-subtle rounded-4 shadow-sm row col-11 col-sm-9 col-md-8 col-lg-6">
                        <div class="col-12">
                            <i class="fa-solid fa-hospital-crack text-danger fa-3xl mb-3"></i>
                            <h4 class="fw-bold text-danger">Encerrar / Remover Localização?</h4>
                            <p class="text-muted small my-3 lh-base">
                                Tem a certeza que deseja eliminar o serviço <strong>Unidade de Cuidados Intensivos (#SRV-UCI)</strong>? 
                                O sistema só permitirá a remoção se todos os equipamentos vinculados a este espaço forem previamente transferidos para outra localização ativa.
                            </p>
                            
                            <form action="localizacoes.php" method="POST">
                                <input type="hidden" name="codigo_id" value="SRV-UCI">
                                
                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <a href="localizacoes.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm">
                                        <i class="fa-solid fa-trash-can me-2"></i>Confirmar Fecho de Ala
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 