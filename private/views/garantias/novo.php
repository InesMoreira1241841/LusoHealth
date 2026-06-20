<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados textuais e referências
    $num_contrato = $_POST["num_contrato"] ?? "";
    $equipamento_id = $_POST["equipamento_id"] ?? "";
    $fornecedor_id = $_POST["fornecedor_id"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $data_inicio = $_POST["data_inicio"] ?? "";
    $data_fim = $_POST["data_fim"] ?? "";
    $periodicidade = $_POST["periodicidade"] ?? "";
    $ficheiro = $_FILES["ficheiro_contrato"] ?? [];
    $url_externo = $_POST["url_externo"] ?? "";
    $observacoes = $_POST["observacoes"] ?? "";

    // 2. Validar os dados acumulando os erros corretamente

    $erros = [];
    $erros = array_merge($erros, validar_num_contrato($num_contrato) ?? []);
    $erros = array_merge($erros, validar_garantia_equipamento_id($equipamento_id) ?? []);
    $erros = array_merge($erros, validar_garantia_fornecedor_id($fornecedor_id) ?? []);
    $erros = array_merge($erros, validar_tipo_garantia($tipo) ?? []);
    $erros = array_merge($erros, validar_datas_garantia($data_inicio, $data_fim) ?? []);
    $erros = array_merge($erros, validar_periodicidade($periodicidade) ?? []);
    $erros = array_merge($erros, validar_caminhos_garantia($ficheiro, $url_externo) ?? []);

    // 4. Se não houver erros, validar duplicados e inserir na BD
    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificação de duplicado
            $sqlCheck = "SELECT COUNT(*) FROM garantias WHERE num_contrato = :num_contrato";
            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([':num_contrato' => $num_contrato]);

            if ($stmtCheck->fetchColumn() > 0) {
                $erros[] = "O número de contrato/apólice '$num_contrato' já está registado.";
            }

            // 5. INSERT
            if (empty($erros)) {

                $nome_ficheiro_final = null;

                if (
                    !empty($ficheiro) &&
                    isset($ficheiro['error']) &&
                    $ficheiro['error'] === UPLOAD_ERR_OK
                ) {
                    $nome_ficheiro_final = "CTR_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";

                    $pasta_upload = __DIR__ . '/../../../uploads/';

                    if (!is_dir($pasta_upload)) {
                        mkdir($pasta_upload, 0755, true);
                    }

                    if (!move_uploaded_file(
                        $ficheiro['tmp_name'],
                        $pasta_upload . $nome_ficheiro_final
                    )) {
                        throw new Exception("Não foi possível guardar o PDF.");
                    }
                }

                $sql = "INSERT INTO garantias (
                            num_contrato, equipamento_id, fornecedor_id, tipo, data_inicio, 
                            data_fim, periodicidade, ficheiro_path, url_externo, observacoes) 
                        VALUES (
                            :num_contrato, :equipamento_id, :fornecedor_id, :tipo, :data_inicio, 
                            :data_fim, :periodicidade, :ficheiro_path, :url_externo, :observacoes)";

                $stmt = $ligacao->prepare($sql);
                $stmt->execute([
                    ':num_contrato' => $num_contrato,
                    ':equipamento_id' => $equipamento_id,
                    ':fornecedor_id' => trim($fornecedor_id) !== '' ? $fornecedor_id : null,
                    ':tipo' => $tipo,
                    ':data_inicio' => $data_inicio,
                    ':data_fim' => $data_fim,
                    ':periodicidade' => $periodicidade,
                    ':ficheiro_path' => !empty($nome_ficheiro_final) ? $nome_ficheiro_final : null,
                    ':url_externo' => (trim($url_externo) !== '') ? trim($url_externo) : null,
                    ':observacoes' => $observacoes
                ]);

                $_SESSION['success_message'] = "Registo efetuado com sucesso.";
                header('Location: contratos.php');
                exit;
            }
        } catch (Exception $err) {
            $erros[] = "Erro ao gravar: " . $err->getMessage();
        } finally {
            $ligacao = null;
        }
    }
}

// CARREGAR DADOS DINÂMICOS PARA OS COMPONENTES SELECT
try {
    $ligacao = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL Corrigido: Seleciona apenas o ID automático e a Designação
    $listaEquipamentos = $ligacao->query("SELECT id, designacao FROM equipamentos WHERE estado != 'Abatido' ORDER BY designacao ASC")->fetchAll(PDO::FETCH_ASSOC);
    $listaFornecedores = $ligacao->query("SELECT id, nome FROM fornecedores WHERE arquivado = 0 ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $err) {
    $erros[] = "Erro ao carregar listas (Equipamentos/Fornecedores): " . $err->getMessage();
    $listaEquipamentos = [];
    $listaFornecedores = [];
} finally {
    $ligacao = null;
}

include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Criar Vínculo de Garantia / Contrato</h2>
                        <p class="text-muted small m-0">Insira as balizas temporais, defina o tipo de cobertura e anexe a documentação obrigatória.</p>
                    </div>

                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger shadow-sm" role="alert">
                            <h5 class="alert-heading fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Aviso do Sistema:</h5>
                            <ul class="mb-0">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?= htmlspecialchars($erro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="#" method="POST" enctype="multipart/form-data" class="small text-secondary">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Número do Contrato / Apólice</label>
                                <input type="text" class="form-control font-monospace text-uppercase"
                                    name="num_contrato" placeholder="Ex: CTR-9821-2026 ou GRT-123456"
                                    value="<?= htmlspecialchars($_POST['num_contrato'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Início</label>
                                <input type="date" class="form-control font-monospace"
                                    name="data_inicio"
                                    value="<?= htmlspecialchars($_POST['data_inicio'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Fim de Vigência</label>
                                <input type="date" class="form-control font-monospace"
                                    name="data_fim"
                                    value="<?= htmlspecialchars($_POST['data_fim'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Equipamento Vinculado</label>
                                <select class="form-select text-secondary" name="equipamento_id">
                                    <option value="" selected disabled>Escolha o dispositivo...</option>

                                    <?php foreach ($listaEquipamentos as $equipamento) : ?>
                                        <option value="<?= $equipamento['id'] ?>" <?= (($_POST['equipamento_id'] ?? '') == $equipamento['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($equipamento['designacao']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Entidade Técnica Responsável (Fornecedor)</label>
                                <select class="form-select text-secondary" name="fornecedor_id">
                                    <option value="" selected disabled>Selecione o Fornecedor...</option>
                                    <?php foreach ($listaFornecedores as $fornecedor) : ?>
                                        <option
                                            value="<?= $fornecedor['id'] ?>" <?= (($_POST['fornecedor_id'] ?? '') == $fornecedor['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fornecedor['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Tipo de Vínculo</label>
                                <select class="form-select text-secondary" name="tipo">
                                    <option value="Garantia de Fábrica" <?= (($_POST['tipo'] ?? '') == 'Garantia de Fábrica') ? 'selected' : '' ?>>Garantia de Fábrica</option>
                                    <option value="Contrato de Manutenção" <?= (($_POST['tipo'] ?? '') == 'Contrato de Manutenção') ? 'selected' : '' ?>>Contrato de Manutenção</option>
                                    <option value="Outro" <?= (($_POST['tipo'] ?? '') == 'Outro') ? 'selected' : '' ?>>Outro</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Periodicidade de Calibração / Revisão</label>
                                <input type="text" class="form-control" name="periodicidade" placeholder="Ex: Semestral, Anual" value="<?= htmlspecialchars($_POST['periodicidade'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Observações Gerais</label>
                                <input type="text" class="form-control" name="observacoes" value="<?= htmlspecialchars($_POST['observacoes'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    <label class="form-label fw-bold text-dark"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Upload Direto da Apólice (PDF)</label>
                                    <input type="file" class="form-control" name="ficheiro_contrato" accept=".pdf">
                                    <div class="form-text xsmall text-muted">Apenas documentos em formato PDF (Máx. 5MB).</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    <label class="form-label fw-bold text-dark"><i class="fa-solid fa-link text-primary me-1"></i> Repositório / Link da Cloud</label>
                                    <input type="url" class="form-control" name="url_externo" placeholder="https://drive.google.com/..."
                                        value="<?= htmlspecialchars($_POST['url_externo'] ?? '') ?>">
                                    <div class="form-text xsmall text-muted">Link para a pasta partilhada no OneDrive/Drive institucional.</div>
                                </div>
                            </div>

                            <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end">
                                <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-shield-halved me-2"></i>Validar Contrato
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>