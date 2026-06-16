<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
require_once __DIR__ . '/../../includes/validacoes.php';

$idLocEncrypted = $_GET['id_localizacoes'] ?? null;
$idLoc = aes_decrypt($idLocEncrypted);

if (!$idLoc || !is_numeric($idLoc)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/localizacoes.php');
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
    $stmt = $ligacao->prepare("SELECT * FROM localizacoes WHERE id = :id");
    $stmt->bindParam(':id', $idLoc, PDO::PARAM_INT);
    $stmt->execute();

    $localizacoes = $stmt->fetch(PDO::FETCH_OBJ);

    // Se não encontrou a localização, redireciona
    if (!$localizacoes) {
        header('Location: ' . BASE_URL . '/private/views/localizacoes/localizacoes.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $novoNome = $_POST['nome'] ?? '';
        $novoEdificio = $_POST['edificio'] ?? '';
        $novoPiso = $_POST['piso'] ?? '';
        $novoResponsavel = $_POST['responsavel'] ?? '';
        $novoObservacoes = $_POST['observacoes'] ?? '';

        $erros = [];
        $erros = array_merge($erros, validar_nome($novoNome) ?? []);
        $erros = array_merge($erros, validar_edificio($novoEdificio) ?? []);
        $erros = array_merge($erros, validar_piso($novoPiso) ?? []);
        $erros = array_merge($erros, validar_responsavel($novoResponsavel) ?? []);

        if (empty(trim($novoNome))) {
            $erro = "O nome não pode estar vazio.";
        } else {
            try {
                // Reutiliza a ligação $ligacao que já está aberta
                $stmt = $ligacao->prepare("
                    UPDATE localizacoes
                    SET nome = :nome,
                        edificio = :edificio,
                        piso = :piso,
                        responsavel = :responsavel,
                        observacoes = :observacoes,
                        atualizado_em = NOW()
                    WHERE id = :id
                ");

                #$stmt->bindParam(':nome', $novoNome);
                $stmt->bindParam(':nome', $novoNome, PDO::PARAM_STR);
                $stmt->bindParam(':edificio', $novoEdificio, PDO::PARAM_STR);
                $stmt->bindParam(':piso', $novoPiso, PDO::PARAM_INT);
                $stmt->bindParam(':responsavel', $novoResponsavel, PDO::PARAM_STR);
                $stmt->bindParam(':observacoes', $novoObservacoes, PDO::PARAM_STR);
                $stmt->bindParam(':id', $idLoc, PDO::PARAM_INT); // ou $
                $stmt->execute();

                // Mensagem de sucesso e redirecionamento (opcional)
                header('Location: localizacoes.php');
                exit;
            } catch (PDOException $err) {
                $erro = "Erro ao atualizar o nome: " . $err->getMessage();
            }
        }
    }
} catch (PDOException $err) {
    $erro = "Erro na ligação à base de dados.";
    $localizacoes = null;
}
// Fecha a ligação
$ligacao = null;


include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Modificar Localização<span class="text-success">
                                <?= htmlspecialchars($localizacoes->codigo) ?></span>
                        </h2>
                        <p class="text-muted small m-0">Atualize os dados estruturais ou altere a responsabilidade da
                            ala.</p>
                    </div>

                    <form action="editar.php?id_localizacoes=<?= $idLocEncrypted ?>" method="POST" novalidate class="small text-secondary">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="editCodigoLocalizacao" class="form-label fw-bold text-dark">ID da
                                    Localização</label>
                                <input type="text" class="form-control font-monospace text-uppercase bg-light"
                                    name="codigo" value="<?= htmlspecialchars($localizacoes->codigo) ?>" readonly>
                            </div>

                            <div class="col-md-8">
                                <label for="editNomeLocalizacao" class="form-label fw-bold text-dark">Nome do Serviço /
                                    Ala</label>
                                <input type="text" class="form-control" name="nome"
                                    value="<?= htmlspecialchars($localizacoes->nome) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editEdificioLocalizacao" class="form-label fw-bold text-dark">Edifício /
                                    Bloco</label>
                                <input type="text" class="form-control" name="edificio"
                                    value="<?= htmlspecialchars($localizacoes->edificio) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editPisoLocalizacao" class="form-label fw-bold text-dark">Piso</label>
                                <input type="number" class="form-control" id="editPisoLocalizacao"
                                    name="piso" value="<?= htmlspecialchars($localizacoes->piso) ?>"
                                    min="-2" max="7" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editResponsavelLocalizacao" class="form-label fw-bold text-dark">Responsável</label>
                                <input type="text" class="form-control" name="responsavel"
                                    value="<?= htmlspecialchars($localizacoes->responsavel) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editObservacoesLocalizacao" class="form-label fw-bold text-dark">Observações</label>
                                <input type="text" class="form-control" name="observacoes"
                                    value="<?= htmlspecialchars($localizacoes->observacoes) ?>">
                            </div>

                            <div class="col-12 pt-3 border-top mt-4 d-flex gap-2 justify-content-end">
                                <a href="localizacoes.php"
                                    class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnAtualizarLocalizacao"
                                    class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate me-2"></i>Atualizar Dados
                                </button>
                            </div>

                        </div>
                    </form>

                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger text-center" role="alert">
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