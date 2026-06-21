<?php
require_once 'includes/funcoes.php';
redirect_if_not_logged();
start_session();

$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// --- INICIALIZAÇÃO DE VARIÁVEIS DAS ESTATÍSTICAS ---
$totalEquipamentos = 0;
$ativos = 0;
$manutencao = 0;
$inativos = 0;
$emServico = 0;
$garantiaExpirada = 0;
$semDocumentacao = 0;
$criticidadeElevada = 0;
$garantiasTrintaDias = 0;

// Variáveis para os Gráficos (armazenados em JSON para o Chart.js)
$dadosServicosJson = '[]';
$dadosLocalizacoesJson = '[]';
$dadosSuporteVidaJson = '[]';

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Contador Geral por Estados (Assumindo campo 'estado' na tabela equipamentos)
    // Ajuste as strings ('Ativo', 'Manutenção', etc.) conforme o que grava na sua BD
    $stmtEstados = $ligacao->query("
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN estado = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
            SUM(CASE WHEN estado = 'Manutenção' THEN 1 ELSE 0 END) AS manutencao,
            SUM(CASE WHEN estado = 'Inativo' THEN 1 ELSE 0 END) AS inativos,
            SUM(CASE WHEN estado = 'Em Serviço' THEN 1 ELSE 0 END) AS em_servico
        FROM equipamentos
    ");
    $resEstados = $stmtEstados->fetch(PDO::FETCH_OBJ);
    if ($resEstados) {
        $totalEquipamentos = $resEstados->total ?? 0;
        $ativos             = $resEstados->ativos ?? 0;
        $manutencao         = $resEstados->manutencao ?? 0;
        $inativos           = $resEstados->inativos ?? 0;
        $emServico          = $resEstados->em_servico ?? 0;
    }

    // 2. Garantias Expiradas e Próximas do Fim (tabela garantias)
    $hoje = date('Y-m-d');
    $proximos30Dias = date('Y-m-d', strtotime('+30 days'));

    $stmtGarantias = $ligacao->query("
        SELECT 
            SUM(CASE WHEN data_fim < '$hoje' THEN 1 ELSE 0 END) AS expiradas,
            SUM(CASE WHEN data_fim >= '$hoje' AND data_fim <= '$proximos30Dias' THEN 1 ELSE 0 END) AS proximas
        FROM garantias
    ");
    $resGarantias = $stmtGarantias->fetch(PDO::FETCH_OBJ);
    if ($resGarantias) {
        $garantiaExpirada   = $resGarantias->expiradas ?? 0;
        $garantiasTrintaDias = $resGarantias->proximas ?? 0;
    }

    // 3. Equipamentos Sem Documentação (LEFT JOIN verificando se o ID do documento é NULL)
    $stmtDocs = $ligacao->query("
        SELECT COUNT(e.id) AS total 
        FROM equipamentos e 
        LEFT JOIN documentacao d ON e.id = d.equipamento_id 
        WHERE d.id IS NULL
    ");
    $semDocumentacao = $stmtDocs->fetchColumn() ?: 0;

    // 4. Criticidade Elevada (Assumindo um campo 'criticidade' na tabela equipamentos)
    $stmtCrit = $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE criticidade = 'Alta' OR criticidade = 'Elevada'");
    $criticidadeElevada = $stmtCrit->fetchColumn() ?: 0;


    // --- DADOS PARA OS GRÁFICOS ---

    // Gráfico 1: Equipamentos por Serviço (Assumindo campo ou tabela 'servicos')
    // Se tiver tabela associada mude para INNER JOIN servicos s ON e.servico_id = s.id
    $stmtGrafServicos = $ligacao->query("
        SELECT servico AS label, COUNT(*) AS total 
        FROM equipamentos 
        WHERE servico IS NOT NULL AND servico != ''
        GROUP BY servico
    ");
    $dadosServicosJson = json_encode($stmtGrafServicos->fetchAll(PDO::FETCH_ASSOC));

    // Gráfico 2: Distribuição por Localização
    $stmtGrafLoc = $ligacao->query("
        SELECT l.nome AS label, COUNT(e.id) AS total 
        FROM equipamentos e
        INNER JOIN localizacoes l ON e.localizacao_id = l.id
        GROUP BY e.localizacao_id
    ");
    $dadosLocalizacoesJson = json_encode($stmtGrafLoc->fetchAll(PDO::FETCH_ASSOC));

    // Gráfico 3: Equipamentos de Suporte de Vida por Serviço
    // Assumindo um campo boolean ou string 'suporte_vida' (ex: 'Sim')
    $stmtGrafSuporte = $ligacao->query("
        SELECT servico AS label, COUNT(*) AS total 
        FROM equipamentos 
        WHERE (suporte_vida = 'Sim' OR suporte_vida = 1) AND servico IS NOT NULL AND servico != ''
        GROUP BY servico
    ");
    $dadosSuporteVidaJson = json_encode($stmtGrafSuporte->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $err) {
    // Silencioso em produção ou tratar o erro de forma amigável
    $erroBd = $err->getMessage();
}
$ligacao = null;

include '../assets/includes/head.php';

if (!empty($success_message)) : ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastSuccess" class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<body class="bg-page-light">

    <?php include '../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../assets/includes/sidebar/dashboard.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-12">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Total Equipamentos</h6>
                            <p class="fs-2 fw-bold text-dark m-0 font-monospace"><?= $totalEquipamentos ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos Ativos</h6>
                            <p class="fs-2 fw-bold text-success m-0 font-monospace"><?= $ativos ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos em Manutenção</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace"><?= $manutencao ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos Inativos</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $inativos ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos em (Serviço)</h6>
                            <p class="fs-2 fw-bold text-dark m-0 font-monospace"><?= $emServico ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos com Garantia Expirada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $garantiaExpirada ?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos sem Documentação Associada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $semDocumentacao ?></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Dispositivos de Criticidade Elevada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $criticidadeElevada ?></p>
                        </div>
                    </div>

                </div>


        </div>

        </main>
    </div>
    </div>

    <?php include '../assets/includes/footer.php'; ?>