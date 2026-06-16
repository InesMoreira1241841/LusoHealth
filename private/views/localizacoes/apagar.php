<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado

$idEncrypted = $_GET['id_localizacoes'] ?? null;
$id = aes_decrypt($idEncrypted);
if (!$id || !is_numeric($id)) {
    header('Location: localizadores.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $stmt = $ligacao->prepare("SELECT codigo, nome, edificio, piso, responsavel, observacoes 
    FROM localizacoes WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $localizacoes = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$localizacoes) {
        header('Location: localizacoes.php');
        exit;
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
    exit;
}

include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">
                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded d-flex align-items-center justify-content-center">

                    <div class="text-center p-4 bg-danger-subtle border border-danger-subtle rounded-4 shadow-sm row col-11 col-sm-9 col-md-8 col-lg-6">
                        <div class="col-12">
                            <i class="fa-solid fa-hospital-crack text-danger fa-3xl mb-3"></i>
                            <h4 class="fw-bold text-danger">Encerrar / Remover Localização?</h4>
                            <p class="text-muted small my-3 lh-base">
                                Tem a certeza que deseja eliminar o serviço <strong>Unidade de Cuidados Intensivos (#SRV-UCI)</strong>?
                                O sistema só permitirá a remoção se todos os equipamentos vinculados a este espaço forem previamente transferidos para outra localização ativa.
                            </p>

                            <form action="localizacoes.php" method="POST">
                                <input type="hidden" name="codigo_id" value="SRV-UCI">

                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <a href="localizacoes.php" class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm">
                                        <i class="fa-solid fa-trash-can me-2"></i>Confirmar Fecho de Ala
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>