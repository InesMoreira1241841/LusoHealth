<?php 
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 

require_once __DIR__ . '/../../includes/funcoes.php'; 
redirect_if_not_logged(); 

// Inicializar variáveis de erro
$erros = [];

// 1. Capturar e desencriptar o ID do fornecedor vindo do GET
$idFornecedorEncrypted = $_GET['id_fornecedores'] ?? null; 
$idFornecedor = aes_decrypt($idFornecedorEncrypted);

if (!$idFornecedor || !is_numeric($idFornecedor)) {
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

    // 2. Se o formulário foi submetido via POST (Processar a Atualização)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome              = $_POST["nome"] ?? "";
        $telefone          = $_POST["telefone"] ?? "";
        $email             = $_POST["email"] ?? "";
        $morada            = $_POST["morada"] ?? "";
        $tecnico_nome      = $_POST["tecnico_nome"] ?? "";
        $tecnico_telefone  = $_POST["tecnico_telefone"] ?? "";

        // Validações básicas no servidor
        if (empty($nome)) $erros[] = "O campo Nome / Razão Social é obrigatório.";
        if (empty($email)) $erros[] = "O e-mail de assistência oficial é obrigatório.";

        if (empty($erros)) {
            $sqlUp = "UPDATE fornecedores SET 
                        nome = :nome, 
                        telefone = :telefone, 
                        email = :email, 
                        morada = :morada, 
                        tecnico_nome = :tecnico_nome, 
                        tecnico_telefone = :tecnico_telefone
                      WHERE id = :id";
            
            $stmtUp = $ligacao->prepare($sqlUp);
            $stmtUp->execute([
                ':nome'             => $nome,
                ':telefone'         => $telefone,
                ':email'            => $email,
                ':morada'           => $morada,
                ':tecnico_nome'     => $tecnico_nome,
                ':tecnico_telefone' => $tecnico_telefone,
                ':id'               => $idFornecedor
            ]);

            header('Location: fornecedores.php');
            exit;
        }
    }

    // 3. Procurar os dados atuais do fornecedor para preencher o formulário
    $stmt = $ligacao->prepare("SELECT * FROM fornecedores WHERE id = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: fornecedores.php');
        exit;
    }

    // 4. QUERY: Procurar todos os equipamentos associados a este fornecedor pela tabela pivot
    $sqlEquipamentos = "SELECT e.id, e.codigo_inventario, e.designacao, e.marca, e.modelo, ef.tipo_relacao 
                        FROM equipamentos e
                        INNER JOIN equipamento_fornecedor ef ON e.id = ef.equipamento_id
                        WHERE ef.fornecedor_id = :fornecedor_id
                        ORDER BY e.designacao ASC";
    
    $stmtEquip = $ligacao->prepare($sqlEquipamentos);
    $stmtEquip->execute([':fornecedor_id' => $idFornecedor]);
    $equipamentos_vinculados = $stmtEquip->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $err) {
    $erros[] = "Erro no sistema ou na ligação à base de dados: " . $err->getMessage();
}
?>

<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">

<?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/fornecedores.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Modificar Fornecedor <span class="text-success">#<?= htmlspecialchars($fornecedor->nome ?? '') ?></span></h2>
                        <p class="text-muted small m-0">Mantenha os canais e e-mails de assistência biomédica sempre atualizados.</p>
                    </div>

                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id_fornecedores=<?= $idFornecedorEncrypted ?>" method="POST" class="row g-3 fw-medium text-secondary small">

                        <div class="col-md-6">
                            <label for="edit_nome" class="form-label text-dark">Nome / Razão Social</label>
                            <input type="text" id="edit_nome" name="nome" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['nome'] ?? $fornecedor->nome ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_nif" class="form-label text-dark">NIF (Imutável)</label>
                            <input type="text" id="edit_nif" name="nif" class="form-control rounded-3 bg-light" 
                                value="<?= htmlspecialchars($fornecedor->nif ?? '') ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_telefone" class="form-label text-dark">Telefone de Suporte Técnico</label>
                            <input type="tel" id="edit_telefone" name="telefone" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['telefone'] ?? $fornecedor->telefone ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_email" class="form-label text-dark">E-mail de Assistência Oficial</label>
                            <input type="email" id="edit_email" name="email" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['email'] ?? $fornecedor->email ?? '') ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_morada" class="form-label text-dark">Endereço da Sede Comercial</label>
                            <input type="text" id="edit_morada" name="morada" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['morada'] ?? $fornecedor->morada ?? '') ?>">
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_nome" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-user-gear me-2"></i>Gestor de Conta / Técnico Responsável
                            </label>
                            <input type="text" id="edit_tecnico_nome" name="tecnico_nome" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['tecnico_nome'] ?? $fornecedor->tecnico_nome ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mt-4 pt-2">
                            <label for="edit_tecnico_telefone" class="form-label text-dark fw-bold text-success">
                                <i class="fa-solid fa-phone-volume me-2"></i>Linha Direta do Técnico
                            </label>
                            <input type="tel" id="edit_tecnico_telefone" name="tecnico_telefone"
                                class="form-control rounded-3" value="<?= htmlspecialchars($_POST['tecnico_telefone'] ?? $fornecedor->tecnico_telefone ?? '') ?>" required>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="fornecedores.php" class="btn btn-light border rounded-pill px-4">Descartar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Ficha
                            </button>
                        </div>
                    </form>

                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger mt-3 rounded-3" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?= htmlspecialchars($erro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

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