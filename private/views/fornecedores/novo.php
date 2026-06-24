<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados
    $nome = $_POST["nome"] ?? "";
    $nif = $_POST["nif"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $email = $_POST["email"] ?? "";
    $morada = $_POST["morada"] ?? "";
    $website = $_POST["website"] ?? "";
    $tecnico_nome = $_POST["tecnico_nome"] ?? "";
    $tecnico_telefone = $_POST["tecnico_telefone"] ?? "";
    $observacoes = $_POST["observacoes"] ?? "";

    // A. Normalizar entrada
    $nome = ucwords(strtolower(trim($nome)));
    $nif = trim($nif);
    $telefone = trim($telefone) ?: null;
    $email = strtolower(trim($email)) ?: null;
    $morada = trim($morada) ?: null;
    $website = strtolower(trim($website)) ?: null;
    $tecnico_nome = ucwords(strtolower(trim($tecnico_nome))) ?: null;
    $tecnico_telefone = trim($tecnico_telefone) ?: null;
    $observacoes = trim($observacoes) ?: null;

    // 2. Validar todos os dados acumulando os erros corretamente
    $erros = [];
    $erros = array_merge($erros, validar_nome_fornecedor($nome) ?? []);
    $erros = array_merge($erros, validar_nif($nif) ?? []);
    $erros = array_merge($erros, validar_tipo_fornecedor($tipo) ?? []);
    $erros = array_merge($erros, validar_telefone_fornecedor($telefone) ?? []);
    $erros = array_merge($erros, validar_email_fornecedor($email) ?? []);
    $erros = array_merge($erros, validar_website_fornecedor($website) ?? []);
    $erros = array_merge($erros, validar_morada_fornecedor($morada) ?? []);
    $erros = array_merge($erros, validar_tecnico_nome($tecnico_nome) ?? []);
    $erros = array_merge($erros, validar_tecnico_telefone($tecnico_telefone) ?? []);

    // 3. Se não houver erros de formato, validar regras de negócio na BD
    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // --- VERIFICAÇÃO DE DUPLICADO AQUI ---
            $sqlCheck = "SELECT COUNT(*) FROM fornecedores WHERE nif = :nif";
            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([':nif' => $nif]);

            if ($stmtCheck->fetchColumn() > 0) {
                // Se já existir, injetamos o erro no teu array global de erros
                $erros[] = "O NIF '$nif' já está registado para outro fornecedor. O NIF deve ser único.";
            }

            // 4. Se continuar sem erros (NIF único), faz-se o INSERT
            if (empty($erros)) {
                $sql = "INSERT INTO fornecedores (
                            nome, nif, tipo, telefone, email, morada, website, tecnico_nome, tecnico_telefone, observacoes, criado_em) 
                        VALUES (
                            :nome, :nif, :tipo, :telefone, :email, :morada, :website, :tecnico_nome, :tecnico_telefone, :observacoes, NOW())";

                $stmt = $ligacao->prepare($sql);
                $stmt->execute([
                    ':nome'             => $nome,
                    ':nif'              => $nif,
                    ':tipo'             => $tipo,
                    ':telefone'         => $telefone,
                    ':email'            => $email,
                    ':morada'           => $morada,
                    ':website'          => $website,
                    ':tecnico_nome'     => $tecnico_nome,
                    ':tecnico_telefone' => $tecnico_telefone,
                    ':observacoes'      => $observacoes
                ]);

                $_SESSION['success_message'] = "Fornecedor registado com sucesso.";
                header('Location: fornecedores.php');
                exit;
            }
        } catch (PDOException $err) {
            $erros[] = "Erro ao gravar os dados: " . $err->getMessage();
        } finally {
            $ligacao = null;
        }
    }
}

include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Registar Fornecedor / Fabricante</h2>
                        <p class="text-muted small m-0">Insira os dados da entidade externa para vinculação a equipamentos e contratos.</p>
                    </div>

                    <!-- Área de erros -->
                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger" role="alert">
                            <strong>Foram encontrados os seguintes erros:</strong>
                            <ul class="mb-0">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?= htmlspecialchars($erro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="#" method="POST" class="small text-secondary">

                        <div class="row g-3 fw-medium">

                            <div class="col-md-5">
                                <label for="nome_fornecedor" class="form-label fw-bold text-dark">Nome / Razão Social</label>
                                <input type="text" id="nome_fornecedor" name="nome" class="form-control"
                                    placeholder="Ex: Siemens Healthineers Portugal" required
                                    value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                            </div>

                            <div class="col-md-3">
                                <label for="nif_fornecedor" class="form-label fw-bold text-dark">NIF (Identificação Fiscal)</label>
                                <input type="text" id="nif_fornecedor" name="nif" class="form-control font-monospace"
                                    placeholder="Ex: 500123456" required
                                    value="<?= htmlspecialchars($_POST['nif'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="tipo_fornecedor" class="form-label fw-bold text-dark">Tipo de Fornecedor</label>
                                <select id="tipo_fornecedor" name="tipo" class="form-select" required>
                                    <option value="" selected disabled>Selecione uma opção...</option>
                                    <option value="Fabricante" <?= (($_POST['tipo'] ?? '') === 'Fabricante') ? 'selected' : '' ?>>Fabricante</option>
                                    <option value="Distribuidor" <?= (($_POST['tipo'] ?? '') === 'Distribuidor') ? 'selected' : '' ?>>Distribuidor</option>
                                    <option value="Assistência Técnica" <?= (($_POST['tipo'] ?? '') === 'Assistência Técnica') ? 'selected' : '' ?>>Assistência Técnica</option>
                                    <option value="Consumíveis" <?= (($_POST['tipo'] ?? '') === 'Consumíveis') ? 'selected' : '' ?>>Consumíveis</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="telefone_fornecedor" class="form-label fw-bold text-dark">Telefone Geral</label>
                                <input type="tel" id="telefone_fornecedor" name="telefone" class="form-control"
                                    placeholder="Ex: +351 210 000 000"
                                    value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="email_fornecedor" class="form-label fw-bold text-dark">E-mail de Assistência</label>
                                <input type="email" id="email_fornecedor" name="email" class="form-control"
                                    placeholder="Ex: suporte@empresa.com"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="website_fornecedor" class="form-label fw-bold text-dark">Website Oficial</label>
                                <input type="url" id="website_fornecedor" name="website" class="form-control"
                                    placeholder="Ex: https://www.empresa.com"
                                    value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                            </div>

                            <div class="col-md-12">
                                <label for="morada_fornecedor" class="form-label fw-bold text-dark">Endereço da Sede</label>
                                <input type="text" id="morada_fornecedor" name="morada" class="form-control"
                                    placeholder="Rua, Código Postal, Cidade"
                                    value="<?= htmlspecialchars($_POST['morada'] ?? '') ?>">
                            </div>

                            <div class="col-md-6 mt-4 pt-2">
                                <label for="tecnico_responsavel" class="form-label fw-bold text-success">
                                    <i class="fa-solid fa-user-gear me-2"></i>Gestor de Conta / Técnico Responsável
                                </label>
                                <input type="text" id="tecnico_responsavel" name="tecnico_nome" class="form-control font-monospace"
                                    placeholder="Ex: Eng. Carlos Mendes"
                                    value="<?= htmlspecialchars($_POST['tecnico_nome'] ?? '') ?>">
                            </div>

                            <div class="col-md-6 mt-4 pt-2">
                                <label for="telefone_tecnico" class="form-label fw-bold text-success">
                                    <i class="fa-solid fa-phone-volume me-2"></i>Linha Direta do Técnico
                                </label>
                                <input type="tel" id="telefone_tecnico" name="tecnico_telefone" class="form-control font-monospace"
                                    placeholder="Ex: +351 912 345 678"
                                    value="<?= htmlspecialchars($_POST['tecnico_telefone'] ?? '') ?>">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="observacoes_fornecedor" class="form-label fw-bold text-dark">Observações Adicionais</label>
                                <input type="text" id="observacoes_fornecedor" name="observacoes" class="form-control font-monospace text-uppercase"
                                    value="<?= htmlspecialchars($_POST['observacoes'] ?? '') ?>">
                            </div>

                            <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end">
                                <a href="fornecedores.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnSalvarFornecedor" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Gravar Fornecedor
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>