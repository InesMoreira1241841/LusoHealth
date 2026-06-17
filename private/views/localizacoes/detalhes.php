// Página - localizacoes/detalhes.php

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

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Ficha Informativa da Localização</h2>
                            <p class="text-muted small m-0">Equipamentos alocados e infraestrutura técnica da ala.</p>
                        </div>
                        <a href="localizacoes.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <div class="row g-4 text-secondary small mb-5">
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Ala Clínica / Serviço</span>
                            <p class="text-dark fw-bold fs-5 m-0">Unidade de Cuidados Intensivos (UCI)</p>
                            <span class="badge bg-success-subtle text-success border font-monospace mt-1">SRV-UCI</span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Infraestrutura Física</span>
                            <p class="text-dark fw-semibold m-0"><i class="fa-solid fa-layer-group me-1 text-success"></i> Piso 2 • Bloco Central</p>
                            <p class="text-muted m-0 mt-1" style="font-size: 0.8rem;"><i class="fa-solid fa-plug text-muted me-1"></i> Redundância Elétrica / Terra Isolada</p>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase fw-bold opacity-75 mb-1" style="font-size: 0.7rem;">Responsabilidade Técnica</span>
                            <p class="text-dark fw-semibold m-0"><i class="fa-solid fa-user-shield me-1 text-success"></i> Dr. Ricardo Santos</p>
                            <p class="text-muted m-0" style="font-size: 0.8rem;">Contacto de Piquete Ativo</p>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-heart-pulse text-success me-2"></i>Dispositivos Médicos Atualmente Alocados
                        </h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle small text-secondary">
                                <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">
                                    <tr>
                                        <th>Nº Série</th>
                                        <th>Equipamento</th>
                                        <th>Marca / Modelo</th>
                                        <th>Criticidade</th>
                                        <th>Estado Técnico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-dark font-monospace">SN-9821-X</td>
                                        <td>Ventilador Pulmonar</td>
                                        <td>Dräger / Evita 4</td>
                                        <td><span class="badge bg-danger text-white rounded-pill px-2">Suporte de Vida</span></td>
                                        <td><span class="badge bg-success-subtle text-success border rounded-3 px-2">Operacional</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-dark font-monospace">SN-1022-M</td>
                                        <td>Monitor Multiparamétrico</td>
                                        <td>Philips / IntelliVue</td>
                                        <td><span class="badge bg-warning text-dark rounded-pill px-2">Alta Criticidade</span></td>
                                        <td><span class="badge bg-success-subtle text-success border rounded-3 px-2">Operacional</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 