<?php
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

$idContratoEncrypted = $_GET['id'] ?? null;
$idContrato = aes_decrypt($idContratoEncrypted);

if (!$idContrato || !is_numeric($idContrato)) {
    header('Location: ' . BASE_URL . '/private/views/garantias/contratos.php');
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

    $stmt = $ligacao->prepare("SELECT * FROM garantias WHERE id = :id");
    $stmt->bindParam(':id', $idContrato, PDO::PARAM_INT);
    $stmt->execute();

    $contrato = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$contrato) {
        header('Location: contratos.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $num_contrato = $_POST["num_contrato"] ?? "";
        $equipamento_id = $_POST["equipamento_id"] ?? "";
        $fornecedor_id = $_POST["fornecedor_id"] ?? "";
        $tipo = $_POST["tipo"] ?? "";
        $data_inicio = $_POST["data_inicio"] ?? "";
        $data_fim = $_POST["data_fim"] ?? "";
        $periodicidade = $_POST["periodicidade"] ?? "";
        $url_externo = $_POST["url_externo"] ?? "";
        $observacoes = $_POST["observacoes"] ?? "";
        $ficheiro = $_FILES["ficheiro_contrato"] ?? [];

        $erros = [];
        $erros = array_merge($erros, validar_num_contrato($num_contrato));
        $erros = array_merge($erros, validar_garantia_equipamento_id($equipamento_id));
        $erros = array_merge($erros, validar_garantia_fornecedor_id($fornecedor_id));
        $erros = array_merge($erros, validar_tipo_garantia($tipo));
        $erros = array_merge($erros, validar_datas_garantia($data_inicio, $data_fim));
        $erros = array_merge($erros, validar_periodicidade($periodicidade));

        if (trim($url_externo) !== '') {
            if (!filter_var(trim($url_externo), FILTER_VALIDATE_URL)) {
                $erros[] = "O Link para a Cloud inserido não é válido.";
            }
        }

        if (empty($erros)) {
            $sqlCheck = "SELECT COUNT(*) FROM garantias
                     WHERE num_contrato = :num_contrato
                     AND id != :id";

            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([
                ':num_contrato' => $num_contrato,
                ':id' => $idContrato
            ]);

            if ($stmtCheck->fetchColumn() > 0) {
                $erros[] = "O número de contrato/apólice '$num_contrato' já está registado.";
            }
        }

        $ficheiro_path_antigo = $contrato->ficheiro_path;
        $nome_ficheiro_final = $ficheiro_path_antigo;
        $pasta_upload = __DIR__ . '/../../../uploads/';
        $remover_ficheiro = isset($_POST['remover_ficheiro']) && $_POST['remover_ficheiro'] === '1';

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
                $nome_ficheiro_final = "CTR_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";

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

        if (empty($erros)) {

            $fornecedor_final = trim($fornecedor_id) === '' ? null : (int)$fornecedor_id;
            $url_final = trim($url_externo) === '' ? null : trim($url_externo);

            $stmt = $ligacao->prepare("
            UPDATE garantias
            SET num_contrato = :num_contrato,
                equipamento_id = :equipamento_id,
                fornecedor_id = :fornecedor_id,
                tipo = :tipo,
                data_inicio = :data_inicio,
                data_fim = :data_fim,
                periodicidade = :periodicidade,
                ficheiro_path = :ficheiro_path,
                url_externo = :url_externo,
                observacoes = :observacoes
            WHERE id = :id
        ");

            $stmt->execute([
                ':num_contrato' => $num_contrato,
                ':equipamento_id' => $equipamento_id,
                ':fornecedor_id' => $fornecedor_final,
                ':tipo' => $tipo,
                ':data_inicio' => $data_inicio,
                ':data_fim' => $data_fim,
                ':periodicidade' => $periodicidade,
                ':ficheiro_path' => $nome_ficheiro_final,
                ':url_externo' => $url_final,
                ':observacoes' => $observacoes,
                ':id' => $idContrato
            ]);

            if (
                !empty($ficheiro_path_antigo) &&
                $ficheiro_path_antigo !== $nome_ficheiro_final
            ) {
                $caminho_antigo = $pasta_upload . $ficheiro_path_antigo;
                if (is_file($caminho_antigo)) {
                    unlink($caminho_antigo);
                }
            }

            $_SESSION['success_message'] = "Contrato atualizado com sucesso.";
            header('Location: contratos.php');
            exit;
        }

        $contrato->num_contrato = $num_contrato;
        $contrato->equipamento_id = $equipamento_id;
        $contrato->fornecedor_id = $fornecedor_id;
        $contrato->tipo = $tipo;
        $contrato->data_inicio = $data_inicio;
        $contrato->data_fim = $data_fim;
        $contrato->periodicidade = $periodicidade;
        $contrato->url_externo = $url_externo;
        $contrato->observacoes = $observacoes;
        $contrato->ficheiro_path = $nome_ficheiro_final;
    }

    $listaEquipamentos = $ligacao
        ->query("SELECT id, designacao 
                 FROM equipamentos 
                 WHERE estado != 'Abatido'
                 ORDER BY designacao ASC")
        ->fetchAll(PDO::FETCH_ASSOC);

    $listaFornecedores = $ligacao
        ->query("SELECT id, nome 
                 FROM fornecedores 
                 WHERE arquivado = 0
                 ORDER BY nome ASC")
        ->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $err) {
    $erros[] = "Erro na base de dados: " . $err->getMessage();
    $contrato = null;
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

            <?php include '../../../assets/includes/sidebar/garantias.php' ?>

            <main class="col-md-9 col-lg-10">

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">
                            Modificar Contrato / Garantia
                            <span class="text-success">
                                <?= htmlspecialchars($contrato->num_contrato) ?>
                            </span>
                        </h2>
                        <p class="text-muted small m-0">
                            Atualize os dados estruturais, vigências ou coberturas de assistência do documento.
                        </p>
                    </div>

                    <form action="editar.php?id=<?= $idContratoEncrypted ?>"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                        class="small text-secondary">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Número do Contrato / Apólice</label>
                                <input
                                    type="text"
                                    class="form-control font-monospace text-uppercase"
                                    name="num_contrato"
                                    value="<?= htmlspecialchars($contrato->num_contrato) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Início</label>
                                <input
                                    type="date"
                                    class="form-control font-monospace"
                                    name="data_inicio"
                                    value="<?= htmlspecialchars($contrato->data_inicio) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Fim de Vigência</label>
                                <input
                                    type="date"
                                    class="form-control font-monospace"
                                    name="data_fim"
                                    value="<?= htmlspecialchars($contrato->data_fim) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Equipamento Vinculado</label>
                                <select class="form-select text-secondary" name="equipamento_id">
                                    <option value="" disabled>Escolha o dispositivo...</option>

                                    <?php foreach ($listaEquipamentos as $equipamento): ?>
                                        <option
                                            value="<?= $equipamento['id'] ?>"
                                            <?= ($contrato->equipamento_id == $equipamento['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($equipamento['designacao']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Entidade Técnica Responsável (Fornecedor)</label>
                                <select class="form-select text-secondary" name="fornecedor_id">
                                    <option value="">Sem fornecedor associado</option>

                                    <?php foreach ($listaFornecedores as $fornecedor): ?>
                                        <option
                                            value="<?= $fornecedor['id'] ?>"
                                            <?= ($contrato->fornecedor_id == $fornecedor['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fornecedor['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Tipo de Vínculo</label>
                                <select class="form-select text-secondary" name="tipo">
                                    <option value="Garantia de Fábrica"
                                        <?= ($contrato->tipo == 'Garantia de Fábrica') ? 'selected' : '' ?>>
                                        Garantia de Fábrica
                                    </option>

                                    <option value="Contrato de Manutenção"
                                        <?= ($contrato->tipo == 'Contrato de Manutenção') ? 'selected' : '' ?>>
                                        Contrato de Manutenção
                                    </option>

                                    <option value="Outro"
                                        <?= ($contrato->tipo == 'Outro') ? 'selected' : '' ?>>
                                        Outro
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Periodicidade de Calibração / Revisão</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="periodicidade"
                                    placeholder="Ex: Semestral, Anual"
                                    value="<?= htmlspecialchars($contrato->periodicidade ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Observações Gerais</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="observacoes"
                                    value="<?= htmlspecialchars($contrato->observacoes ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fa-solid fa-file-pdf text-danger me-1"></i> Apólice em PDF
                                    </label>

                                    <input type="hidden" name="remover_ficheiro" id="remover_ficheiro" value="0">

                                    <div id="ficheiro_atual_wrapper"
                                        class="<?= empty($contrato->ficheiro_path) ? 'd-none' : '' ?> d-flex align-items-center justify-content-between bg-white border rounded-2 px-3 py-2 mb-2">
                                        <span class="text-truncate">
                                            <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                                            <span id="nome_ficheiro_atual"><?= htmlspecialchars($contrato->ficheiro_path ?? '') ?></span>
                                        </span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" title="Remover ficheiro" onclick="removerFicheiroAtual()">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                    <input type="file" class="form-control" name="ficheiro_contrato" id="ficheiro_contrato" accept=".pdf"
                                        onchange="document.getElementById('remover_ficheiro').value = '0';">

                                    <div class="form-text xsmall text-muted">Apenas documentos em formato PDF (Máx. 5MB).</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fa-solid fa-link text-primary me-1"></i>
                                        Repositório / Link da Cloud
                                    </label>

                                    <input
                                        type="url"
                                        class="form-control"
                                        name="url_externo"
                                        value="<?= htmlspecialchars($contrato->url_externo ?? '') ?>">

                                    <div class="form-text xsmall text-muted">
                                        Link para a pasta partilhada no OneDrive/Drive institucional.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end">
                                <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>
                                    Atualizar Dados
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </main>

        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>