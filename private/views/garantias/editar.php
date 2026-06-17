<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// 1. Capturar e desencriptar o ID do contrato vindo do GET (Igual ao localizacoes)
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

    // 2. Processar a submissão do formulário (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $num_contrato = $_POST["num_contrato"] ?? "";
        $equipamento_id = $_POST["equipamento_id"] ?? "";
        $fornecedor_id = $_POST["fornecedor_id"] ?? "";
        $tipo = $_POST["tipo"] ?? "Garantia de Fábrica"; 
        $tem_contrato_manutencao = $_POST["tem_contrato_manutencao"] ?? "0";
        $data_inicio = $_POST["contrato_data_inicio"] ?? "";
        $data_fim = $_POST["contrato_data_fim"] ?? "";
        $periodicidade = $_POST["periodicidade"] ?? "";
        $observacoes = $_POST["observacoes"] ?? "";
        $url_externo = $_POST["url_externo"] ?? "";

        // Normalizações básicas
        $num_contrato = strtoupper(trim($num_contrato));
        $observacoes = trim($observacoes);
        $url_externo = trim($url_externo);

        // Validações de preenchimento
        if (empty($num_contrato)) { $erros[] = "O número do contrato é obrigatório."; }
        if (empty($data_inicio)) { $erros[] = "A data de início da garantia é obrigatória."; }
        if (empty($data_fim)) { $erros[] = "A data de fim de vigência é obrigatória."; }
        if (empty($equipamento_id)) { $erros[] = "Deve selecionar um equipamento vinculado."; }
        if (empty($fornecedor_id)) { $erros[] = "Deve selecionar um fornecedor técnico responsável."; }
        
        if (!empty($data_inicio) && !empty($data_fim) && strtotime($data_inicio) > strtotime($data_fim)) {
            $erros[] = "A data de início não pode ser posterior à data de fim de vigência.";
        }

        // Verificar duplicados noutros registos
        if (empty($erros)) {
            $sqlCheck = "SELECT COUNT(*) FROM garantias WHERE num_contrato = :num_contrato AND id != :id";
            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([':num_contrato' => $num_contrato, ':id' => $idContrato]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                $erros[] = "O número de contrato/apólice '$num_contrato' já está registado noutro documento.";
            }
        }

        // Se passar nas validações, executa o UPDATE
        if (empty($erros)) {
            $stmtUpdate = $ligacao->prepare("
                UPDATE garantias 
                SET num_contrato = :num_contrato, 
                    equipamento_id = :equipamento_id, 
                    fornecedor_id = :fornecedor_id, 
                    tipo = :tipo, 
                    tem_contrato_manutencao = :tem_contrato_manutencao, 
                    data_inicio = :data_inicio, 
                    data_fim = :data_fim, 
                    periodicidade = :periodicidade, 
                    url_externo = :url_externo, 
                    observacoes = :observacoes 
                WHERE id = :id
            ");

            $stmtUpdate->bindParam(':num_contrato', $num_contrato, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':equipamento_id', $equipamento_id, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':tem_contrato_manutencao', $tem_contrato_manutencao, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':data_inicio', $data_inicio, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':data_fim', $data_fim, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':periodicidade', $periodicidade, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':url_externo', $url_externo, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':observacoes', $observacoes, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':id', $idContrato, PDO::PARAM_INT);
            
            $stmtUpdate->execute();

            header('Location: contratos.php');
            exit;
        }
    }

    // 3. Carregar os dados atuais do registo alvo (Usando FETCH_OBJ igual ao teu modelo)
    $stmt = $ligacao->prepare("SELECT * FROM garantias WHERE id = :id");
    $stmt->bindParam(':id', $idContrato, PDO::PARAM_INT);
    $stmt->execute();
    $contrato = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$contrato) {
        header('Location: ' . BASE_URL . '/private/views/garantias/contratos.php');
        exit;
    }

    // Carregar os dados para as listas suspensas (Mantido em Array associativo para os loops)
    $listaEquipamentos = $ligacao->query("SELECT id, designacao FROM equipamentos ORDER BY designacao ASC")->fetchAll(PDO::FETCH_ASSOC);
    $listaFornecedores = $ligacao->query("SELECT id, nome FROM fornecedores ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $err) {
    $erros[] = "Erro no sistema de base de dados: " . $err->getMessage();
    $contrato = null;
    $listaEquipamentos = [];
    $listaFornecedores = [];
} finally {
    $ligacao = null; // Fecha a ligação como fizeste
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
                        <h2 class="fw-bold text-dark m-0">Modificar Contrato / Garantia: <span class="text-success"><?= htmlspecialchars($contrato->num_contrato) ?></span></h2>
                        <p class="text-muted small m-0">Atualize os dados estruturais, vigências ou coberturas de assistência do documento.</p>
                    </div>

                    <form action="editar.php?id=<?= $idContratoEncrypted ?>" method="POST" novalidate class="small text-secondary">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Número do Contrato / Apólice</label>
                                <input type="text" class="form-control font-monospace text-uppercase text-dark fw-bold" name="num_contrato" value="<?= htmlspecialchars($contrato->num_contrato) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Início</label>
                                <input type="date" class="form-control font-monospace" name="contrato_data_inicio" value="<?= htmlspecialchars($contrato->data_inicio) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Data de Fim de Vigência</label>
                                <input type="date" class="form-control font-monospace" name="contrato_data_fim" value="<?= htmlspecialchars($contrato->data_fim) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Equipamento Vinculado</label>
                                <select class="form-select text-secondary" name="equipamento_id" required>
                                    <option value="" disabled>Escolha o dispositivo...</option>
                                    <?php foreach ($listaEquipamentos as $equipamento) : ?>
                                        <option value="<?= $equipamento['id'] ?>" <?= ($contrato->equipamento_id == $equipamento['id']) ? 'selected' : '' ?>>
                                            #<?= $equipamento['id'] ?> - <?= htmlspecialchars($equipamento['designacao']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Entidade Técnica Responsável (Fornecedor)</label>
                                <select class="form-select text-secondary" name="fornecedor_id" required>
                                    <option value="" disabled>Selecione o Fornecedor...</option>
                                    <?php foreach ($listaFornecedores as $fornecedor) : ?>
                                        <option value="<?= $fornecedor['id'] ?>" <?= ($contrato->fornecedor_id == $fornecedor['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fornecedor['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Tipo de Vínculo</label>
                                <select class="form-select text-secondary" name="tipo">
                                    <option value="Garantia de Fábrica" <?= ($contrato->tipo == 'Garantia de Fábrica') ? 'selected' : '' ?>>Garantia de Fábrica</option>
                                    <option value="Contrato de Manutenção" <?= ($contrato->tipo == 'Contrato de Manutenção') ? 'selected' : '' ?>>Contrato de Manutenção</option>
                                    <option value="Outro" <?= ($contrato->tipo == 'Outro') ? 'selected' : '' ?>>Outro</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Tem Contrato de Manutenção?</label>
                                <select class="form-select text-secondary" name="tem_contrato_manutencao">
                                    <option value="1" <?= ($contrato->tem_contrato_manutencao == '1') ? 'selected' : '' ?>>Sim</option>
                                    <option value="0" <?= ($contrato->tem_contrato_manutencao == '0') ? 'selected' : '' ?>>Não</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Periodicidade de Calibração / Revisão</label>
                                <input type="text" class="form-control" name="periodicidade" value="<?= htmlspecialchars($contrato->periodicidade ?? '') ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark"><i class="fa-solid fa-link text-primary me-1"></i> Repositório / Link da Cloud</label>
                                <input type="url" class="form-control" name="url_externo" value="<?= htmlspecialchars($contrato->url_externo ?? '') ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">Observações / Cláusulas Gerais</label>
                                <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($contrato->observacoes ?? '') ?></textarea>
                            </div>

                            <div class="col-12 pt-3 border-top mt-4 d-flex gap-2 justify-content-end">
                                <a href="contratos.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Dados
                                </button>
                            </div>

                        </div>
                    </form>

                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger text-center mt-3" role="alert">
                            <?php foreach ($erros as $erro): ?>
                                <div><?= htmlspecialchars($erro) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>