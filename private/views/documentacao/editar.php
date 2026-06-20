<?php
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

$idDocumentoEncrypted = $_GET['id'] ?? null;
$idDocumento = aes_decrypt($idDocumentoEncrypted);

if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: documentos.php');
    exit;
}

$erros = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM documentos WHERE id = :id");
    $stmt->bindParam(':id', $idDocumento, PDO::PARAM_INT);
    $stmt->execute();

    $documento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$documento) {
        header('Location: documentos.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome_documento = $_POST['nome_documento'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $equipamento_id = $_POST['equipamento_id'] ?? '';
        $fornecedor_id = $_POST['fornecedor_id'] ?? '';
        $data_documento = $_POST['data_documento'] ?? '';
        $data_validade = $_POST['data_validade'] ?? '';
        $notas = $_POST['notas'] ?? '';
        $url_externo = $_POST['url_externo'] ?? '';
        $ficheiro = $_FILES['ficheiro_documento'] ?? [];
        $remover_ficheiro = isset($_POST['remover_ficheiro']) && $_POST['remover_ficheiro'] === '1';

        $erros = array_merge($erros, validar_nome_documento($nome_documento) ?? []);
        $erros = array_merge($erros, validar_tipo_documento($tipo) ?? []);
        $erros = array_merge($erros, validar_documento_equipamento_id($equipamento_id) ?? []);
        $erros = array_merge($erros, validar_documento_fornecedor_id($fornecedor_id) ?? []);
        $erros = array_merge($erros, validar_associacao_documento($equipamento_id, $fornecedor_id) ?? []);
        $erros = array_merge($erros, validar_datas_documento($data_documento, $data_validade) ?? []);

        // --- Ficheiro / Link: lógica própria de edição ---
        // (não reutiliza validar_caminhos_documento porque já pode existir um ficheiro gravado)
        $ficheiro_path_antigo = $documento->ficheiro_path;
        $nome_ficheiro_final = $ficheiro_path_antigo;
        $pasta_upload = __DIR__ . '/../../../uploads/';

        if (
            empty($erros) &&
            !empty($ficheiro) &&
            isset($ficheiro['error']) &&
            $ficheiro['error'] === UPLOAD_ERR_OK
        ) {
            $ext = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));

            if ($ext !== 'pdf') {
                $erros[] = "Apenas ficheiros PDF são permitidos.";
            } elseif ($ficheiro['size'] > 5 * 1024 * 1024) {
                $erros[] = "O PDF não pode exceder 5MB.";
            } else {
                $nome_ficheiro_final = "DOC_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";

                if (!is_dir($pasta_upload)) {
                    mkdir($pasta_upload, 0755, true);
                }

                if (!move_uploaded_file($ficheiro['tmp_name'], $pasta_upload . $nome_ficheiro_final)) {
                    $erros[] = "Não foi possível guardar o PDF.";
                }
            }
        } elseif (empty($erros) && $remover_ficheiro && !empty($ficheiro_path_antigo)) {
            $nome_ficheiro_final = null;
        }

        $url_final = trim($url_externo) !== '' ? trim($url_externo) : null;

        if ($url_final !== null && !filter_var($url_final, FILTER_VALIDATE_URL)) {
            $erros[] = "O Link para a Cloud inserido não é válido.";
        }

        // Verificação final: depois de aplicar upload/remoção, tem de sobrar pelo menos um dos dois
        if (empty($erros) && empty($nome_ficheiro_final) && empty($url_final)) {
            $erros[] = "O documento tem de ter um ficheiro PDF ou um link externo associado.";
        }

        if (empty($erros)) {

            $stmt = $ligacao->prepare("
                UPDATE documentos
                SET equipamento_id = :equipamento_id,
                    fornecedor_id = :fornecedor_id,
                    tipo = :tipo,
                    nome_documento = :nome_documento,
                    data_documento = :data_documento,
                    data_validade = :data_validade,
                    notas = :notas,
                    ficheiro_path = :ficheiro_path,
                    url_externo = :url_externo
                WHERE id = :id
            ");

            $stmt->execute([
                ':equipamento_id' => trim($equipamento_id) !== '' ? $equipamento_id : null,
                ':fornecedor_id'  => trim($fornecedor_id) !== '' ? $fornecedor_id : null,
                ':tipo'           => $tipo,
                ':nome_documento' => $nome_documento,
                ':data_documento' => $data_documento,
                ':data_validade'  => trim($data_validade) !== '' ? $data_validade : null,
                ':notas'          => $notas,
                ':ficheiro_path'  => $nome_ficheiro_final,
                ':url_externo'    => $url_final,
                ':id'             => $idDocumento
            ]);

            // Só apaga o ficheiro físico antigo depois do UPDATE ter sucesso
            if (!empty($ficheiro_path_antigo) && $ficheiro_path_antigo !== $nome_ficheiro_final) {
                $caminho_antigo = $pasta_upload . $ficheiro_path_antigo;
                if (is_file($caminho_antigo)) {
                    unlink($caminho_antigo);
                }
            }

            $_SESSION['success_message'] = "Documento atualizado com sucesso.";
            header('Location: documentos.php');
            exit;
        }

        // Repor valores no objeto para os campos ficarem preenchidos (sticky fields)
        $documento->nome_documento = $nome_documento;
        $documento->tipo = $tipo;
        $documento->equipamento_id = $equipamento_id;
        $documento->fornecedor_id = $fornecedor_id;
        $documento->data_documento = $data_documento;
        $documento->data_validade = $data_validade;
        $documento->notas = $notas;
        $documento->url_externo = $url_externo;
        $documento->ficheiro_path = $nome_ficheiro_final;
    }

    $listaEquipamentos = $ligacao
        ->query("SELECT id, designacao FROM equipamentos WHERE estado != 'Abatido' ORDER BY designacao ASC")
        ->fetchAll(PDO::FETCH_ASSOC);

    $listaFornecedores = $ligacao
        ->query("SELECT id, nome FROM fornecedores WHERE arquivado = 0 ORDER BY nome ASC")
        ->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $err) {
    $erros[] = "Erro na base de dados: " . $err->getMessage();
    $documento = null;
    $listaEquipamentos = [];
    $listaFornecedores = [];
}

$ligacao = null;

include '../../../assets/includes/head.php';
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/documentacao.php' ?>

            <main class="col-md-9 col-lg-10">

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Modificar Metadados do Documento</h2>
                            <p class="text-muted small m-0">Atualize as especificações, prazos de validade ou altere o ficheiro técnico associado.</p>
                        </div>
                        <a href="documentos.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>

                    <form action="editar.php?id=<?= $idDocumentoEncrypted ?>" method="POST" enctype="multipart/form-data" id="formEditarDocumento" name="form_editar_documento" class="small text-secondary">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="nomeDocumento" class="form-label fw-bold text-dark">Nome do Documento</label>
                                <input type="text" class="form-control" id="nomeDocumento" name="nome_documento"
                                    value="<?= htmlspecialchars($documento->nome_documento ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="editTipoDocumento" class="form-label fw-bold text-dark">Tipologia Documental</label>
                                <select class="form-select" id="editTipoDocumento" name="tipo">
                                    <option value="Manual" <?= ($documento->tipo === 'Manual') ? 'selected' : '' ?>>Manual (Utilizador / Serviço)</option>
                                    <option value="Calibracao" <?= ($documento->tipo === 'Calibracao') ? 'selected' : '' ?>>Certificado de Calibração</option>
                                    <option value="Conformidade" <?= ($documento->tipo === 'Conformidade') ? 'selected' : '' ?>>Declaração de Conformidade CE</option>
                                    <option value="Relatorio" <?= ($documento->tipo === 'Relatorio') ? 'selected' : '' ?>>Relatório Técnico</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editEquipamentoAlvo" class="form-label fw-bold text-dark">Equipamento Associado</label>
                                <select class="form-select" id="editEquipamentoAlvo" name="equipamento_id">
                                    <option value="">Nenhum</option>
                                    <?php foreach ($listaEquipamentos as $equipamento): ?>
                                        <option value="<?= $equipamento['id'] ?>" <?= ($documento->equipamento_id == $equipamento['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($equipamento['designacao']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editFornecedorAlvo" class="form-label fw-bold text-dark">Fornecedor Associado</label>
                                <select class="form-select" id="editFornecedorAlvo" name="fornecedor_id">
                                    <option value="">Nenhum</option>
                                    <?php foreach ($listaFornecedores as $fornecedor): ?>
                                        <option value="<?= $fornecedor['id'] ?>" <?= ($documento->fornecedor_id == $fornecedor['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fornecedor['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="editDataDocumento" class="form-label fw-bold text-dark">Data de Emissão</label>
                                <input type="date" class="form-control font-monospace" id="editDataDocumento" name="data_documento"
                                    value="<?= htmlspecialchars($documento->data_documento ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="editDataValidade" class="form-label fw-bold text-dark">Data de Validade</label>
                                <input type="date" class="form-control font-monospace" id="editDataValidade" name="data_validade"
                                    value="<?= htmlspecialchars($documento->data_validade ?? '') ?>">
                                <div class="form-text">Mantenha atualizado para evitar falhas em auditorias clínicas.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-file-pdf text-danger me-1"></i> Ficheiro PDF
                                </label>

                                <input type="hidden" name="remover_ficheiro" id="remover_ficheiro" value="0">

                                <div id="ficheiro_atual_wrapper"
                                    class="<?= empty($documento->ficheiro_path) ? 'd-none' : '' ?> d-flex align-items-center justify-content-between bg-light border rounded-2 px-3 py-2 mb-2">
                                    <span class="text-truncate">
                                        <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                                        <span id="nome_ficheiro_atual"><?= htmlspecialchars($documento->ficheiro_path ?? '') ?></span>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" title="Remover ficheiro" onclick="removerFicheiroAtual()">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>

                                <input type="file" class="form-control" name="ficheiro_documento" id="ficheiro_documento" accept=".pdf"
                                    onchange="document.getElementById('remover_ficheiro').value = '0';">

                                <div class="form-text xsmall text-muted">Apenas documentos em formato PDF (Máx. 5MB).</div>
                            </div>

                            <div class="col-md-6">
                                <label for="editUrlExterno" class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-link text-primary me-1"></i> Link da Cloud
                                </label>
                                <input type="url" class="form-control" id="editUrlExterno" name="url_externo"
                                    value="<?= htmlspecialchars($documento->url_externo ?? '') ?>">
                                <div class="form-text xsmall text-muted">Alternativa ao PDF. Tem de existir pelo menos um dos dois.</div>
                            </div>

                            <div class="col-12">
                                <label for="editNotasDocumento" class="form-label fw-bold text-dark">Notas Técnicas / Observações</label>
                                <textarea class="form-control" id="editNotasDocumento" name="notas" rows="3"><?= htmlspecialchars($documento->notas ?? '') ?></textarea>
                            </div>

                            <div class="col-12 pt-3 border-top mt-4 d-flex gap-2 justify-content-end">
                                <a href="documentos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnAtualizarDocumento" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>Guardar Alterações
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </main>

        </div>

    </div>

    <?php include '../../../assets/includes/footer.php'; ?>