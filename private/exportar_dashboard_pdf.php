<?php
require_once 'includes/funcoes.php';
redirect_if_not_logged();
start_session();

// Exportação do Dashboard em formato HTML imprimível (PDF via Ctrl+P / Print to PDF)
// Não requer bibliotecas externas como FPDF

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

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Estados dos equipamentos
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

    // Garantias
    $hoje = date('Y-m-d');
    $proximos30Dias = date('Y-m-d', strtotime('+30 days'));
    $stmtGarantias = $ligacao->prepare("
        SELECT 
            SUM(CASE WHEN data_fim < :hoje THEN 1 ELSE 0 END) AS expiradas,
            SUM(CASE WHEN data_fim >= :hoje2 AND data_fim <= :proximos THEN 1 ELSE 0 END) AS proximas
        FROM garantias
        WHERE arquivado = 0
    ");
    $stmtGarantias->execute([':hoje' => $hoje, ':hoje2' => $hoje, ':proximos' => $proximos30Dias]);
    $resGarantias = $stmtGarantias->fetch(PDO::FETCH_OBJ);
    if ($resGarantias) {
        $garantiaExpirada    = $resGarantias->expiradas ?? 0;
        $garantiasTrintaDias = $resGarantias->proximas ?? 0;
    }

    // Sem documentação
    $stmtDocs = $ligacao->query("
        SELECT COUNT(*)
        FROM equipamentos e
        WHERE e.id NOT IN (
            SELECT DISTINCT d.equipamento_id FROM documentos d
        )
        AND e.estado != 'Abatido'
    ");
    $semDocumentacao = $stmtDocs->fetchColumn() ?: 0;

    // Criticidade elevada
    $stmtCrit = $ligacao->query("
        SELECT COUNT(*)
        FROM equipamentos
        WHERE criticidade = 'Alta' OR criticidade = 'Suporte de vida'
    ");
    $criticidadeElevada = $stmtCrit->fetchColumn() ?: 0;

} catch (PDOException $e) {
    error_log($e->getMessage());
    $erroBd = 'Erro ao obter dados da base de dados.';
} finally {
    $ligacao = null;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório Dashboard — LusoHealth</title>
    <style>
        @media print { .no-print { display: none; } body { margin: 0; } }
        body { font-family: Arial, sans-serif; color: #333; padding: 30px; }
        .header { text-align: center; border-bottom: 3px solid #2e7d32; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { color: #2e7d32; font-size: 22px; margin: 0 0 4px; }
        .header p { color: #666; font-size: 12px; margin: 0; }
        h2 { font-size: 15px; color: #1b5e20; border-left: 4px solid #4caf50; padding-left: 10px; margin-top: 28px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th { background: #e8f5e9; color: #2e7d32; text-align: left; padding: 8px 12px; border: 1px solid #c8e6c9; }
        td { padding: 8px 12px; border: 1px solid #ddd; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .badge-danger { background: #ffebee; color: #c62828; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
        .badge-warn { background: #fff8e1; color: #f57f17; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
        .badge-ok { background: #e8f5e9; color: #2e7d32; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
        .btn-print { background: #4caf50; color: white; border: none; padding: 10px 24px; border-radius: 20px; cursor: pointer; font-size: 14px; margin-bottom: 20px; }
        .btn-back { background: #fff; color: #2e7d32; border: 1px solid #4caf50; padding: 10px 24px; border-radius: 20px; cursor: pointer; font-size: 14px; margin-bottom: 20px; margin-right: 8px; text-decoration: none; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 12px; }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px;">
    <a href="home.php" class="btn-back">← Voltar ao Dashboard</a>
    <button class="btn-print" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>
</div>

<div class="header">
    <h1>LusoHealth — Relatório do Dashboard</h1>
    <p>Gerado em: <?= date('d/m/Y \à\s H:i') ?> | Utilizador: <?= htmlspecialchars($_SESSION['username'] ?? '—') ?></p>
</div>

<?php if ($erroBd): ?>
    <p style="color:red;"><?= htmlspecialchars($erroBd) ?></p>
<?php endif; ?>

<h2>Indicadores Gerais de Equipamentos</h2>
<table>
    <tr><th>Indicador</th><th>Valor</th></tr>
    <tr><td>Total de Equipamentos</td><td><?= $totalEquipamentos ?></td></tr>
    <tr><td>Equipamentos Ativos</td><td><span class="badge-ok"><?= $ativos ?></span></td></tr>
    <tr><td>Em Manutenção</td><td><?= $emManutencao ?></td></tr>
    <tr><td>Em Calibração</td><td><?= $emCalibracao ?></td></tr>
    <tr><td>Em Quarentena</td><td><?= $emQuarentena ?></td></tr>
    <tr><td>Abatidos</td><td><?= $abatidos ?></td></tr>
</table>

<h2>Indicadores de Risco</h2>
<table>
    <tr><th>Indicador</th><th>Valor</th></tr>
    <tr><td>Garantias Expiradas</td><td><?= $garantiaExpirada > 0 ? '<span class="badge-danger">' . $garantiaExpirada . '</span>' : $garantiaExpirada ?></td></tr>
    <tr><td>Garantias a Expirar nos Próximos 30 Dias</td><td><?= $garantiasTrintaDias > 0 ? '<span class="badge-warn">' . $garantiasTrintaDias . '</span>' : $garantiasTrintaDias ?></td></tr>
    <tr><td>Equipamentos sem Documentação</td><td><?= $semDocumentacao > 0 ? '<span class="badge-warn">' . $semDocumentacao . '</span>' : $semDocumentacao ?></td></tr>
    <tr><td>Criticidade Elevada (Alta + Suporte de Vida)</td><td><?= $criticidadeElevada ?></td></tr>
</table>

<div class="footer">
    LusoHealth | Gestão de Inventário Hospitalar de Equipamentos Médicos &copy; <?= date('Y') ?>
</div>

</body>
</html>
