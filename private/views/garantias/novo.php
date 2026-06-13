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
                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Criar Vínculo de Garantia / Contrato</h2>
                        <p class="text-muted small m-0">Insira as balizas temporais e as obrigações da empresa prestadora de assistência.</p>
                    </div>

                    <form action="contratos.php" method="POST" id="formNovoContrato" name="form_novo_contrato" class="row g-3 fw-medium text-secondary small">
                        
                        <div class="col-md-4">
                            <label for="numContrato" class="form-label text-dark fw-bold">Número do Contrato / Apólice</label>
                            <input type="text" class="form-control font-monospace text-uppercase" id="numContrato" name="num_contrato" required placeholder="Ex: CTR-9821-2026">
                        </div>

                        <div class="col-md-4">
                            <label for="contratoDataInicio" class="form-label text-dark fw-bold">Data de Início da Garantia</label>
                            <input type="date" class="form-control font-monospace" id="contratoDataInicio" name="contrato_data_inicio" required>
                        </div>

                        <div class="col-md-4">
                            <label for="contratoDataFim" class="form-label text-dark fw-bold">Data de Fim de Vigência</label>
                            <input type="date" class="form-control font-monospace" id="contratoDataFim" name="contrato_data_fim" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contratoEquipamentoAlvo" class="form-label text-dark fw-bold">Equipamento Vinculado</label>
                            <select class="form-select text-secondary" id="contratoEquipamentoAlvo" name="contrato_equipamento_alvo" required>
                                <option value="" selected disabled>Escolha o dispositivo...</option>
                                <option value="INV-0091">#INV-0091 - Ventilador Pulmonar</option>
                                <option value="INV-1102">#INV-1102 - Desfibrilhador Zoll R</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="entidadeResponsavel" class="form-label text-dark fw-bold">Entidade Técnica Responsável</label>
                            <select class="form-select text-secondary" id="entidadeResponsavel" name="entidade_responsavel" required>
                                <option value="" selected disabled>Selecione o Fornecedor...</option>
                                <option value="Drager">Dräger Portugal Lda.</option>
                                <option value="Philips">Philips Medical Systems S.A.</option>
                                <option value="Zoll">Zoll Medical Portugal</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="contratoClausulas" class="form-label text-dark fw-bold">Cláusulas de Manutenção Preventiva e Observações</label>
                            <textarea class="form-control" id="contratoClausulas" name="contrato_clausulas" rows="3"
                                placeholder="Descreva a periodicidade de calibrações, tempo de resposta técnico (SLA) contratado..."></textarea>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                            <button type="submit" id="btnValidarContrato" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                <i class="fa-solid fa-shield-halved me-2"></i>Validar Contrato
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

<?php include '../../../assets/includes/footer.php'; ?> 