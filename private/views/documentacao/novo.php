<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de criação
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// --------------------------------------------------------------------

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

$erros = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados textuais e referências
    $nome_documento = $_POST["nome_documento"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $equipamento_id = $_POST["equipamento_id"] ?? "";
    $fornecedor_id = $_POST["fornecedor_id"] ?? "";
    $data_documento = $_POST["data_documento"] ?? "";
    $data_validade = $_POST["data_validade"] ?? "";
    $notas = $_POST["notas"] ?? "";
    $ficheiro = $_FILES["ficheiro_documento"] ?? [];
    $url_externo = $_POST["url_externo"] ?? "";

    // 2. Validar os dados acumulando os erros corretamente
    // 2. Validar os dados acumulando os erros corretamente
    $erros = array_merge($erros, validar_nome_documento($nome_documento) ?? []);
    $erros = array_merge($erros, validar_tipo_documento($tipo) ?? []);
    $erros = array_merge($erros, validar_documento_equipamento_id($equipamento_id) ?? []);
    $erros = array_merge($erros, validar_documento_fornecedor_id($fornecedor_id) ?? []);
    $erros = array_merge($erros, validar_associacao_documento($equipamento_id, $fornecedor_id) ?? []);
    $erros = array_merge($erros, validar_datas_documento($data_documento, $data_validade) ?? []);
    $erros = array_merge($erros, validar_caminhos_documento($ficheiro, $url_externo) ?? []);

    // 3. Se não houver erros, inserir na BD
    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $nome_ficheiro_final = null;

            if (
                !empty($ficheiro) &&
                isset($ficheiro['error']) &&
                $ficheiro['error'] === UPLOAD_ERR_OK
            ) {
                $nome_ficheiro_final = "DOC_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";

                $pasta_upload = __DIR__ . '/../../../uploads/';

                if (!is_dir($pasta_upload)) {
                    mkdir($pasta_upload, 0755, true);
                }

                if (!move_uploaded_file($ficheiro['tmp_name'], $pasta_upload . $nome_ficheiro_final)) {
                    throw new Exception("Não foi possível guardar o PDF.");
                }
            }

            $sql = "INSERT INTO documentos (
                        equipamento_id, fornecedor_id, tipo, nome_documento, data_documento,
                        data_validade, notas, ficheiro_path, url_externo)
                    VALUES (
                        :equipamento_id, :fornecedor_id, :tipo, :nome_documento, :data_documento,
                        :data_validade, :notas, :ficheiro_path, :url_externo)";

            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':equipamento_id' => trim($equipamento_id) !== '' ? $equipamento_id : null,
                ':fornecedor_id'  => trim($fornecedor_id) !== '' ? $fornecedor_id : null,
                ':tipo'           => $tipo,
                ':nome_documento' => $nome_documento,
                ':data_documento' => $data_documento,
                ':data_validade'  => trim($data_validade) !== '' ? $data_validade : null,
                ':notas'          => $notas,
                ':ficheiro_path'  => $nome_ficheiro_final,
                ':url_externo'    => trim($url_externo) !== '' ? trim($url_externo) : null,
            ]);

            $_SESSION['success_message'] = "Documento associado com sucesso.";
            header('Location: documentos.php');
            exit;
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

    $listaEquipamentos = $ligacao->query("SELECT id, designacao FROM equipamentos WHERE estado != 'Abatido' ORDER BY designacao ASC")->fetchAll(PDO::FETCH_ASSOC);
    $listaFornecedores = $ligacao->query("SELECT id, nome FROM fornecedores WHERE arquivado = 0 ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $err) {
    $erros[] = "Erro ao carregar listas (Equipamentos/Fornecedores): " . $err->getMessage();
    $listaEquipamentos = [];
    $listaFornecedores = [];
} finally {
    $ligacao = null;
}

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/documentacao.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Vincular Novo Documento Técnico</h2>
                            <p class="text-muted small m-0">Adicione manuais ou relatórios, mapeando-os diretamente ao inventário físico.</p>
                        </div>
                        <a href="documentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
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

                    <form action="#" method="POST" enctype="multipart/form-data" id="formNovoDocumento" name="form_novo_documento" class="row g-3 fw-medium text-secondary small">

                        <div class="col-md-6">
                            <label for="nomeDocumento" class="form-label text-dark fw-bold">Nome do Documento</label>
                            <input type="text" class="form-control" id="nomeDocumento" name="nome_documento"
                                placeholder="Ex: Manual Técnico Evita V500"
                                value="<?= htmlspecialchars($_POST['nome_documento'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="tipoDocumento" class="form-label text-dark fw-bold">Tipo de Documento</label>
                            <select class="form-select text-secondary" id="tipoDocumento" name="tipo">
                                <option value="" disabled <?= empty($_POST['tipo']) ? 'selected' : '' ?>>Selecione a tipologia...</option>
                                <option value="Manual" <?= (($_POST['tipo'] ?? '') === 'Manual') ? 'selected' : '' ?>>Manual (Utilizador / Serviço)</option>
                                <option value="Calibracao" <?= (($_POST['tipo'] ?? '') === 'Calibracao') ? 'selected' : '' ?>>Certificado de Calibração</option>
                                <option value="Conformidade" <?= (($_POST['tipo'] ?? '') === 'Conformidade') ? 'selected' : '' ?>>Declaração de Conformidade CE</option>
                                <option value="Relatorio" <?= (($_POST['tipo'] ?? '') === 'Relatorio') ? 'selected' : '' ?>>Relatório Técnico</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="equipamentoAlvo" class="form-label text-dark fw-bold">Equipamento Associado</label>
                            <select class="form-select text-secondary" id="equipamentoAlvo" name="equipamento_id">
                                <option value="">Nenhum (não vinculado a equipamento)</option>
                                <?php foreach ($listaEquipamentos as $equipamento) : ?>
                                    <option value="<?= $equipamento['id'] ?>" <?= (($_POST['equipamento_id'] ?? '') == $equipamento['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($equipamento['designacao']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="fornecedorAlvo" class="form-label text-dark fw-bold">Fornecedor Associado</label>
                            <select class="form-select text-secondary" id="fornecedorAlvo" name="fornecedor_id">
                                <option value="">Nenhum</option>
                                <?php foreach ($listaFornecedores as $fornecedor) : ?>
                                    <option value="<?= $fornecedor['id'] ?>" <?= (($_POST['fornecedor_id'] ?? '') == $fornecedor['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($fornecedor['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dataEmissao" class="form-label text-dark fw-bold">Data de Emissão</label>
                            <input type="date" class="form-control text-secondary" id="dataEmissao" name="data_documento"
                                value="<?= htmlspecialchars($_POST['data_documento'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="dataValidade" class="form-label text-dark fw-bold">Data de Validade (Se aplicável)</label>
                            <input type="date" class="form-control text-secondary" id="dataValidade" name="data_validade"
                                value="<?= htmlspecialchars($_POST['data_validade'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border border-light-subtle">
                                <label class="form-label fw-bold text-dark"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Ficheiro Digital (PDF)</label>
                                <input type="file" class="form-control" id="ficheiroDocumento" name="ficheiro_documento" accept=".pdf">
                                <div class="form-text xsmall text-muted">Apenas documentos em formato PDF (Máx. 5MB).</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border border-light-subtle">
                                <label class="form-label fw-bold text-dark"><i class="fa-solid fa-link text-primary me-1"></i> Repositório / Link da Cloud</label>
                                <input type="url" class="form-control" name="url_externo" placeholder="https://drive.google.com/..."
                                    value="<?= htmlspecialchars($_POST['url_externo'] ?? '') ?>">
                                <div class="form-text xsmall text-muted">Alternativa ao upload direto do PDF.</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="notas" class="form-label text-dark fw-bold">Notas</label>
                            <input type="text" class="form-control" id="notas" name="notas"
                                value="<?= htmlspecialchars($_POST['notas'] ?? '') ?>">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="documentos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                            <button type="submit" id="btnCarregarDocumento" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Carregar Documento
                            </button>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>