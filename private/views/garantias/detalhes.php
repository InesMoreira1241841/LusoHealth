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

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <span class="text-uppercase font-monospace text-muted small">Contrato de Manutenção</span>
                            <h2 class="fw-bold text-dark m-0">SLA-ZOLL-045</h2>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="contratos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                            </a>
                            <a href="editar.php" class="btn btn-success btn-sm rounded-pill px-3 fw-medium shadow-sm">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Renovar / Editar
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 small text-secondary">
                        <div class="col-md-6">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info text-success me-2"></i>Dados da Cobertura</h6>
                                <div class="mb-2"><strong>Tipo de Cobertura:</strong> SLA Manutenção Total</div>
                                <div class="mb-2"><strong>Entidade Responsável:</strong> Zoll Medical Portugal</div>
                                <div class="mb-2"><strong>Data de Início:</strong> <span class="font-monospace">01/01/2024</span></div>
                                <div class="mb-2"><strong>Data de Fim (Expiração):</strong> <span class="font-monospace text-danger fw-bold">01/01/2026</span></div>
                                <div>
                                    <strong>Estado Atual:</strong>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2 px-2 py-0.5 fw-bold ms-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> Expirado
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-heart-pulse text-success me-2"></i>Dispositivo Médico Beneficiário</h6>
                                <div class="mb-2"><strong>Código Inventário:</strong> <span class="badge bg-white text-dark border font-monospace">#INV-1102</span></div>
                                <div class="mb-2"><strong>Equipamento:</strong> Desfibrilhador Zoll R Series</div>
                                <div class="mb-2"><strong>Número de Série:</strong> <span class="font-monospace">SN-ZLL992104</span></div>
                                <div><strong>Localização Atual:</strong> Bloco Operatório - Sala 2</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light border rounded-3">
                                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-gavel text-success me-2"></i>Termos do SLA e Cláusulas Ativas</h6>
                                <p class="mb-0 text-dark-emphasis lh-base">
                                    Contrato SLA de cobertura total contra danos acidentais. Inclui substituição preventiva de baterias a cada 24 meses e equipa técnica no local num prazo máximo de 4 horas após a abertura do chamado crítico. Inclui calibrações anuais com emissão de certificados de metrologia em conformidade com as normas ISO 13485.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 