<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
require_once __DIR__ . '/../../includes/validacoes.php';

// 1. Capturar e desencriptar o ID do fornecedor vindo do GET
$idFornecedorEncrypted = $_GET['id_fornecedores'] ?? null;
$idFornecedores = aes_decrypt($idFornecedorEncrypted);

if (!$idFornecedores || !is_numeric($idFornecedores)) {
    header('Location: fornecedores.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar e executar a query com segurança
    $stmt = $ligacao->prepare("SELECT * FROM fornecedores WHERE id = :id_fornecedores");

    $stmt->bindParam(':id_fornecedores', $idFornecedores, PDO::PARAM_INT);
    $stmt->execute();

    $fornecedores = $stmt->fetch(PDO::FETCH_OBJ);

    $tipoSelecionado = $_POST['tipo'] ?? $fornecedores->tipo;

    // 2. Se o formulário foi submetido via POST (Processar a Atualização)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $novoNome = $_POST['nome'] ?? '';
        $novoTipo = $_POST['tipo'] ?? '';
        $novoTelefone = $_POST["telefone"] ?? "";
        $novoEmail = $_POST["email"] ?? "";
        $novaMorada = $_POST["morada"] ?? "";
        $novoWebsite = $_POST["website"] ?? "";
        $novoTecnicoNome      = $_POST["tecnico_nome"] ?? "";
        $novoTecnicoTelefone  = $_POST["tecnico_telefone"] ?? "";
        $novasObservacoes  = $_POST["observacoes"] ?? "";

        // 1. Acumular erros das funções de validação
        $erros = [];
        $erros = array_merge($erros, validar_nome_fornecedor($novoNome) ?? []);
        $erros = array_merge($erros, validar_tipo_fornecedor($novoTipo) ?? []);
        $erros = array_merge($erros, validar_telefone_fornecedor($novoTelefone) ?? []);
        $erros = array_merge($erros, validar_email_fornecedor($novoEmail) ?? []);
        $erros = array_merge($erros, validar_morada_fornecedor($novaMorada) ?? []);
        $erros = array_merge($erros, validar_website_fornecedor($novoWebsite) ?? []);
        $erros = array_merge($erros, validar_tecnico_nome($novoTecnicoNome) ?? []);
        $erros = array_merge($erros, validar_tecnico_telefone($novoTecnicoTelefone) ?? []);

        // 2. Se não houver erros de validação, avançamos para a BD
        if (empty($erros)) {
            try {
                // Preparamos a query dentro do TRY para total segurança
                $stmt = $ligacao->prepare("
                    UPDATE fornecedores
                    SET nome = :nome, 
                        tipo = :tipo,
                        telefone = :telefone, 
                        email = :email, 
                        morada = :morada, 
                        website = :website,
                        tecnico_nome = :tecnico_nome, 
                        tecnico_telefone = :tecnico_telefone,
                        observacoes = :observacoes,
                        atualizado_em = NOW()
                    WHERE id = :id_fornecedores
                ");

                $stmt->bindParam(':nome', $novoNome, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $novoTipo, PDO::PARAM_STR);
                $stmt->bindParam(':telefone', $novoTelefone, PDO::PARAM_STR);
                $stmt->bindParam(':email', $novoEmail, PDO::PARAM_STR);
                $stmt->bindParam(':morada', $novaMorada, PDO::PARAM_STR);
                $stmt->bindParam(':website', $novoWebsite, PDO::PARAM_STR);
                $stmt->bindParam(':tecnico_nome', $novoTecnicoNome, PDO::PARAM_STR);
                $stmt->bindParam(':tecnico_telefone', $novoTecnicoTelefone, PDO::PARAM_STR);
                $stmt->bindParam(':observacoes', $novasObservacoes, PDO::PARAM_STR);

                $stmt->bindParam(':id_fornecedores', $idFornecedores, PDO::PARAM_INT);

                $stmt->execute();

                // Sucesso absoluto: Mensagem guardada e redirecionamento
                $_SESSION['success_message'] = "Fornecedor atualizado com sucesso.";
                header('Location: fornecedores.php');
                exit;
            } catch (PDOException $err) {
                // Se a BD falhar, injetamos no array global de erros
                $erros[] = "Erro ao atualizar a base de dados: " . $err->getMessage();
            }
        }
    }
} catch (PDOException $err) {
    // Este catch protege o SELECT inicial e a ligação geral à BD
    $erros[] = "Erro na ligação ou na leitura da base de dados.";
    $fornecedores = null;
}

// Fecha a ligação
$ligacao = null;

include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

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
                        <h2 class="fw-bold text-dark m-0">Modificar Fornecedor
                            <span class="text-success">
                                <?= htmlspecialchars($fornecedores->nome) ?></span>
                        </h2>
                        <p class="text-muted small m-0">Mantenha os canais e e-mails de assistência biomédica sempre atualizados.</p>
                    </div>

                    <form action="editar.php?id_fornecedores=<?= $idFornecedorEncrypted ?>" method="POST" class="row g-3 fw-medium text-secondary small">

                        <div class="col-md-5">
                            <label for="edit_nome" class="form-label text-dark">Nome / Razão Social</label>
                            <input type="text" id="edit_nome" name="nome" class="form-control rounded-3"
                                value="<?= htmlspecialchars($fornecedores->nome) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label for="edit_nif" class="form-label text-dark">Número de Identificação Fiscal</label>
                            <input type="text" id="edit_nif" name="nif" class="form-control rounded-3 bg-light"
                                value="<?= htmlspecialchars($fornecedores->nif) ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="titipo_fornecedorpo" class="form-label text-dark">Tipo de Fornecedor</label>
                            
                                       <select id="tipo_fornecedor" name="tipo" class="form-select rounded-3" required>
                                <option value="">Selecione...</option>

                                <option value="Fabricante"
                                    <?= $tipoSelecionado === 'Fabricante' ? 'selected' : '' ?>>
                                    Fabricante
                                </option>

                                <option value="Distribuidor"
                                    <?= $tipoSelecionado === 'Distribuidor' ? 'selected' : '' ?>>
                                    Distribuidor
                                </option>

                                <option value="Assistência Técnica"
                                    <?= $tipoSelecionado === 'Assistência Técnica' ? 'selected' : '' ?>>
                                    Assistência Técnica
                                </option>

                                <option value="Consumíveis"
                                    <?= $tipoSelecionado === 'Consumíveis' ? 'selected' : '' ?>>
                                    Consumíveis
                                </option>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <label for="edit_telefone" class="form-label text-dark">Telefone de Suporte Técnico</label>
                            <input type="tel" id="edit_telefone" name="telefone" class="form-control rounded-3"
                                value="<?= htmlspecialchars($fornecedores->telefone) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_email" class="form-label text-dark">E-mail de Assistência Oficial</label>
                            <input type="email" id="edit_email" name="email" class="form-control rounded-3"
                                value="<?= htmlspecialchars($fornecedores->email) ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_morada" class="form-label text-dark">Endereço da Sede Comercial</label>
                            <input type="text" id="edit_morada" name="morada" class="form-control rounded-3"
                                value="<?= htmlspecialchars($fornecedores->morada) ?>">
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_nome" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-user-gear me-2"></i>Gestor de Conta / Técnico Responsável
                            </label>
                            <input type="text" id="edit_tecnico_nome" name="tecnico_nome" class="form-control rounded-3"
                                value="<?= htmlspecialchars($fornecedores->tecnico_nome) ?>" required>
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_telefone" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-phone-volume me-2"></i>Linha Direta do Técnico
                            </label>
                            <input type="tel" id="edit_tecnico_telefone" name="tecnico_telefone"
                                class="form-control rounded-3" value="<?= htmlspecialchars($fornecedores->tecnico_telefone) ?>" required>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="fornecedores.php" class="btn btn-light border rounded-pill px-4">Descartar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Ficha
                            </button>
                        </div>
                    </form>

                    <div class="mt-5 pt-4 border-top">
                        <h4 class="fw-bold text-dark mb-1">Parque Médico Associado</h4>
                        <p class="text-muted small mb-3">Listagem de dispositivos médicos que possuem vínculo ativo com esta entidade.</p>

                        <?php if (empty($equipamentos_vinculados)): ?>
                            <div class="alert alert-light border border-dashed rounded-3 p-4 text-center text-muted">
                                <i class="fa-solid fa-folder-open fs-3 mb-2 d-block text-secondary"></i>
                                Nenhum equipamento médico está associado a este fornecedor de momento.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle border border-light-subtle rounded-3 overflow-hidden text-center small">
                                    <thead class="table-success text-dark">
                                        <tr>
                                            <th>Cód. Inventário</th>
                                            <th>Designação</th>
                                            <th>Marca / Modelo</th>
                                            <th>Tipo de Relação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($equipamentos_vinculados as $eq): ?>
                                            <tr>
                                                <td class="fw-bold text-success">#<?= htmlspecialchars($eq->codigo_inventario) ?></td>
                                                <td class="text-dark fw-medium"><?= htmlspecialchars($eq->designacao) ?></td>
                                                <td><?= htmlspecialchars($eq->marca) ?> / <?= htmlspecialchars($eq->modelo) ?></td>
                                                <td>
                                                    <span class="badge bg-opacity-10 rounded-pill px-3 py-1 
                                                        <?= $eq->tipo_relacao === 'Fabricante' ? 'bg-primary text-primary' : ($eq->tipo_relacao === 'Assistência Técnica' ? 'bg-danger text-danger' : 'bg-warning text-warning') ?>">
                                                        <?= htmlspecialchars($eq->tipo_relacao) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="../equipamentos/ver.php?id_equipamentos=<?= aes_encrypt($eq->id) ?>" class="btn btn-sm btn-outline-success rounded-circle">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>