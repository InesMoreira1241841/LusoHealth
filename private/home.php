<?php
require_once 'includes/funcoes.php';
redirect_if_not_logged();
start_session();

$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// --- INICIALIZAÇÃO DE VARIÁVEIS DAS ESTATÍSTICAS ---
$totalEquipamentos = 0;
$ativos = 0;
$emManutencao = 0;
$emCalibracao = 0;
$emQuarentena = 0;
$abatidos = 0;
$garantiaExpirada = 0;
$garantiasTrintaDias = 0;
$semDocumentacao = 0;
$criticidadeElevada = 0;
$erroBd = '';

// Variáveis para os Gráficos (armazenados em JSON para o Chart.js)
$dadosServicosJson = '[]';
$dadosEdificiosJson = '[]';
$dadosSuporteVidaJson = '[]';

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Contador geral por estado
    $stmtEstados = $ligacao->query("
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN estado = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
            SUM(CASE WHEN estado = 'Em manutenção' THEN 1 ELSE 0 END) AS em_manutencao,
            SUM(CASE WHEN estado = 'Em calibração' THEN 1 ELSE 0 END) AS em_calibracao,
            SUM(CASE WHEN estado = 'Em quarentena' THEN 1 ELSE 0 END) AS em_quarentena,
            SUM(CASE WHEN estado = 'Abatido' THEN 1 ELSE 0 END) AS abatidos
        FROM equipamentos
    ");
    $resEstados = $stmtEstados->fetch(PDO::FETCH_OBJ);
    if ($resEstados) {
        $totalEquipamentos = $resEstados->total ?? 0;
        $ativos             = $resEstados->ativos ?? 0;
        $emManutencao       = $resEstados->em_manutencao ?? 0;
        $emCalibracao       = $resEstados->em_calibracao ?? 0;
        $emQuarentena       = $resEstados->em_quarentena ?? 0;
        $abatidos           = $resEstados->abatidos ?? 0;
    }

    // 2. Garantias
    $hoje = date('Y-m-d');
    $proximos30Dias = date('Y-m-d', strtotime('+30 days'));

    $stmtGarantias = $ligacao->prepare("
        SELECT 
            SUM(CASE WHEN data_fim < :hoje THEN 1 ELSE 0 END) AS expiradas,
            SUM(CASE WHEN data_fim >= :hoje2 AND data_fim <= :proximos THEN 1 ELSE 0 END) AS proximas
        FROM garantias
        WHERE arquivado = 0
    ");
    $stmtGarantias->execute([
        ':hoje' => $hoje,
        ':hoje2' => $hoje,
        ':proximos' => $proximos30Dias
    ]);
    $resGarantias = $stmtGarantias->fetch(PDO::FETCH_OBJ);
    if ($resGarantias) {
        $garantiaExpirada    = $resGarantias->expiradas ?? 0;
        $garantiasTrintaDias = $resGarantias->proximas ?? 0;
    }

    // 3. Equipamentos sem documentação
    $stmtDocs = $ligacao->query("
        SELECT COUNT(*) 
        FROM equipamentos e
        WHERE e.id NOT IN (
            SELECT DISTINCT d.equipamento_id FROM documentos d
        )
        AND e.estado != 'Abatido'
    ");
    $semDocumentacao = $stmtDocs->fetchColumn() ?: 0;

    // 4. Criticidade elevada
    $stmtCrit = $ligacao->query("
        SELECT COUNT(*) FROM equipamentos 
        WHERE criticidade = 'Alta' OR criticidade = 'Suporte de vida'
    ");
    $criticidadeElevada = $stmtCrit->fetchColumn() ?: 0;

    // --- CONSULTAS DOS GRÁFICOS ---
    
    // Gráfico 1: Equipamentos por Serviço
    $stmtGrafServicos = $ligacao->query("
        SELECT l.nome AS label, COUNT(e.id) AS total 
        FROM equipamentos e
        INNER JOIN localizacoes l ON e.localizacao_id = l.id
        GROUP BY l.nome
        ORDER BY total DESC
    ");
    $dadosServicosJson = json_encode($stmtGrafServicos->fetchAll(PDO::FETCH_ASSOC));

    // Gráfico 2: Distribuição por Edifício
    $stmtGrafEdificios = $ligacao->query("
        SELECT l.edificio AS label, COUNT(e.id) AS total 
        FROM equipamentos e
        INNER JOIN localizacoes l ON e.localizacao_id = l.id
        GROUP BY l.edificio
        ORDER BY total DESC
    ");
    $dadosEdificiosJson = json_encode($stmtGrafEdificios->fetchAll(PDO::FETCH_ASSOC));

    // Gráfico 3: Equipamentos de Suporte de Vida por Serviço
    $stmtGrafSuporte = $ligacao->query("
        SELECT l.nome AS label, COUNT(e.id) AS total 
        FROM equipamentos e
        INNER JOIN localizacoes l ON e.localizacao_id = l.id
        WHERE e.criticidade = 'Suporte de vida'
        GROUP BY l.nome
        ORDER BY total DESC
    ");
    $dadosSuporteVidaJson = json_encode($stmtGrafSuporte->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $err) {
    error_log($err->getMessage());
    $erroBd = "Não foi possível carregar todos os indicadores do dashboard.";
}
$ligacao = null;

include '../assets/includes/head.php';
?>

<?php if (!empty($success_message)) : ?>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Dashboard</h3>
                        <p class="text-muted small mb-0">Resumo geral do parque tecnológico hospitalar</p>
                    </div>
                    <a href="exportar_dashboard_pdf.php" class="btn btn-danger shadow-sm rounded-pill px-4">
                        <i class="fa-solid fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>

                <?php if (!empty($erroBd)) : ?>
                    <div class="alert alert-warning"><?= htmlspecialchars($erroBd) ?></div>
                <?php endif; ?>

                <div class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-12">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Total Equipamentos</h6>
                            <p class="fs-2 fw-bold text-dark m-0 font-monospace"><?= $totalEquipamentos ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos Ativos</h6>
                            <p class="fs-2 fw-bold text-success m-0 font-monospace"><?= $ativos ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Em Manutenção</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace"><?= $emManutencao ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Em Calibração</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace"><?= $emCalibracao ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Em Quarentena</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $emQuarentena ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Abatidos</h6>
                            <p class="fs-2 fw-bold text-secondary m-0 font-monospace"><?= $abatidos ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Garantia Expirada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $garantiaExpirada ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Garantia a Expirar (30 dias)</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace"><?= $garantiasTrintaDias ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Sem Documentação Associada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $semDocumentacao ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Dispositivos de Criticidade Elevada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace"><?= $criticidadeElevada ?></p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Equipamentos por Serviço</h6>
                            <canvas id="graficoServicos"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Distribuição por Edifício</h6>
                            <canvas id="graficoEdificios"></canvas>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Suporte de Vida por Serviço</h6>
                            <canvas id="graficoSuporteVida"></canvas>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        window.dadosServicos = <?= $dadosServicosJson ?>;
        window.dadosEdificios = <?= $dadosEdificiosJson ?>;
        window.dadosSuporteVida = <?= $dadosSuporteVidaJson ?>;
    </script>

    <?php include '../assets/includes/footer.php'; ?>