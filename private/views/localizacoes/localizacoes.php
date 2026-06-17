<?php

// Inclusão dos ficheiros base de segurança e conexão
require_once __DIR__ . '/../../includes/funcoes.php';

include '../../../assets/includes/head.php';

start_session();
redirect_if_not_logged();

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Captura o modo de visualização. Se não for especificado "arquivados", mostra os ativos (0)
$ver_arquivados = (isset($_GET['modo']) && $_GET['modo'] === 'arquivados') ? 1 : 0;


// Bloco Try/Catch para puxar os dados por PDO
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // O valor de 'arquivado = :arquivado' muda dinamicamente
    $sql = "SELECT id, codigo, nome, edificio, piso, responsavel FROM localizacoes WHERE arquivado = :arquivado ORDER BY edificio ASC";
    $stmt = $ligacao->prepare($sql);
    $stmt->execute([':arquivado' => $ver_arquivados]);
    $lista_localizacoes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Erro ao aceder à base de dados.";
    $lista_localizacoes = [];
}
?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <!-- // INCLUSÃO DOS COMPONENTES VISUAIS REUTILIZÁVEIS -->
    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <!-- // INCLUSÃO DOS COMPONENTES VISUAIS REUTILIZÁVEIS -->
            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="btn-group shadow-sm rounded-pill p-1 bg-light border" role="group">
                        <a href="localizacoes.php" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 0 ? 'btn-primary shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-folder-open me-1"></i> Ativas
                        </a>
                        <a href="localizacoes.php?modo=arquivados" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 1 ? 'btn-danger shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-box-archive me-1"></i> Arquivadas
                        </a>
                    </div>
                </div>

                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Serviços e Localizações Hospitalares</h2>
                            <p class="text-muted small m-0">Gestão de alas físicas, pisos e distribuição de dispositivos biomédicos.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Nova Localização
                        </a>
                    </div>

                    <?php if (!empty($success_message)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle" id="tabela-localizacoes-hospitalares">

                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center">Código Interno</th>
                                    <th class="text-center">Serviço | Unidade</th>
                                    <th class="text-center">Edifício | Bloco</th>
                                    <th class="text-center">Piso</th>
                                    <th class="text-center">Responsável</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody class="small text-secondary">
                                <?php if (empty($lista_localizacoes)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Nenhuma localização ativa encontrada no sistema.
                                        </td>
                                    </tr>

                                <?php else: ?>
                                    <?php foreach ($lista_localizacoes as $loc) : ?>

                                        <tr>
                                            <td class="text-center fw-bold text-back"><?= htmlspecialchars($loc->codigo) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($loc->nome) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($loc->edificio) ?></td>
                                            <td class="text-center">
                                                <?= $loc->piso == 0 ? 'Piso 0 (R/C)' : 'Piso ' . htmlspecialchars($loc->piso) ?>
                                            </td>
                                            <td class="text-center">
                                                <?= !empty($loc->responsavel) ? htmlspecialchars($loc->responsavel) : '<span class="text-muted-italic">Não atribuído</span>' ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="detalhes.php?id=<?= aes_encrypt($loc->id) ?>" class="btn btn-sm btn-outline-secondary rounded-2" title="Visualizar Detalhes">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <?php if ($ver_arquivados === 0): ?>

                                                    <a href="editar.php?id=<?= aes_encrypt($loc->id) ?>" class="btn btn-sm btn-outline-success rounded-2" title="Editar">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="arquivar.php?id=<?= aes_encrypt($loc->id) ?>" class="btn btn-sm btn-outline-danger rounded-2"
                                                        title="Arquivar" onclick="return confirm('Tem a certeza que deseja arquivar esta localização?');">
                                                        <i class="fa-solid fa-box-archive"></i>
                                                    </a>

                                                <?php else: ?>
                                                    <a href="desarquivar.php?id=<?= aes_encrypt($loc->id) ?>" class="btn btn-sm btn-outline-primary rounded-2"
                                                        title="Desarquivar" onclick="return confirm('Deseja restaurar esta localização para o estado ativo?');">
                                                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                    </a>

                                                <?php endif; ?>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </main>

        </div>

    </div>

    <?php
    // INCLUSÃO DO RODAPÉ REUTILIZÁVEL (Ficha 09)
    include '../../../assets/includes/footer.php';
    ?>