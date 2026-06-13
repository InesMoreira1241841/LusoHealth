<?php 
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php'; 
redirect_if_not_logged(); 
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
?>

<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle d-flex align-items-center justify-content-center main-container-height custom-card-rounded">
                    
                    <div class="text-center p-4 bg-danger-subtle border border-danger-subtle rounded-4 shadow-sm mx-auto" style="max-width: 550px;">
                        <i class="fa-solid fa-radiation text-danger fa-3xl mb-3"></i>
                        <h4 class="fw-bold text-danger">Retirar Equipamento do Inventário?</h4>
                        
                        <p class="text-muted small my-3 lh-base">
                            Tem a certeza que deseja dar a baixa definitiva do <strong>Ventilador Pulmonar (SN-9821-X)</strong>? 
                            Esta ação removerá o histórico técnico e desassociará de forma permanente todas as garantias e documentos associados no LusoHealth.
                        </p>
                        
                        <form action="equipamentos.php" method="POST" class="mt-4">
                            <input type="hidden" name="num_serie" value="SN-9821-X">
                            
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="equipamentos.php" class="btn btn-light border rounded-pill px-4 fw-medium">
                                    Cancelar e Voltar
                                </a>
                                <button type="submit" name="action" value="delete" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm">
                                    Confirmar Abatimento
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </main>
        </div>
    </div>
    
<?php include '../../../assets/includes/footer.php'; ?> 