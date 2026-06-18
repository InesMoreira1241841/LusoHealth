<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de equipamentos
// -------------------------------------------------------------------- 
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

include '../../../assets/includes/head.php';

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Captura o modo de visualização. Se não for especificado "arquivados", mostra os em circulação (0)
$ver_arquivados = (isset($_GET['modo']) && $_GET['modo'] === 'arquivados') ? 1 : 0;

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // O valor de 'arquivado = :arquivado' muda dinamicamente
    $sql = "SELECT * FROM equipamentos WHERE arquivado = :arquivado ORDER BY designacao ASC";
    $stmt = $ligacao->prepare($sql);
    $stmt->execute([':arquivado' => $ver_arquivados]);
    $lista_equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Erro ao aceder à base de dados.";
    $lista_equipamentos = [];
}
$ligacao = null;
?>

<body class="bg-page-light">

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="btn-group shadow-sm rounded-pill p-1 bg-light border" role="group">
                        <a href="equipamentos.php" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 0 ? 'btn-primary shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-folder-open me-1"></i> Em Circulação
                        </a>
                        <a href="equipamentos.php?modo=arquivados" class="btn btn-sm px-3 rounded-pill fw-medium <?= $ver_arquivados === 1 ? 'btn-danger shadow-sm' : 'text-secondary' ?>">
                            <i class="fa-solid fa-box-archive me-1"></i> Arquivados
                        </a>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Inventário de Equipamentos Médicos</h2>
                            <p class="text-muted small m-0">Controlo de dispositivos em circulação, criticidade e estados operacionais.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Registar Equipamento
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

                        <table class="table table-hover align-middle" id="tabela-equipamentos">

                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center">Equipamento</th>
                                    <th class="text-center">Código Interno</th>
                                    <th class="text-center">Marca | Modelo</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Criticidade Clínica</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody class="small text-secondary">
                                <?php if (empty($lista_equipamentos)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Nenhum equipamento <?= $ver_arquivados === 1 ? 'arquivado' : 'em circulação' ?> encontrado no sistema.
                                        </td>
                                    </tr>

                                <?php else: ?>
                                    <?php foreach ($lista_equipamentos as $equip) : ?>

                                        <tr>
                                            <td class="text-center fw-semibold text-dark"><?= htmlspecialchars($equip->designacao) ?></td>
                                            <td class="text-center font-monospace small"><?= htmlspecialchars($equip->codigo_inventario) ?></td>
                                            <td><?= htmlspecialchars($equip->marca . ' | ' . $equip->modelo) ?></td>
                                            <td>
                                                <span class="text-center badge <?= $equip->estado === 'Operacional' ? 'bg-success-subtle text-success border' : 'bg-warning-subtle text-warning border' ?> rounded-3 px-2">
                                                    <?= htmlspecialchars($equip->estado) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-center badge <?= $equip->criticidade === 'Alta' ? 'bg-danger text-white' : 'bg-light text-secondary border' ?> rounded-pill px-2">
                                                    <?= htmlspecialchars($equip->criticidade) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="detalhes.php?id_equipamentos=<?= aes_encrypt($equip->id) ?>" class="btn btn-sm btn-outline-secondary rounded-2" title="Visualizar Detalhes">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <?php if ($ver_arquivados === 0): ?>

                                                    <a href="editar.php?id_equipamentos=<?= aes_encrypt($equip->id) ?>" class="btn btn-sm btn-outline-success rounded-2" title="Editar">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="arquivar.php?id_equipamentos=<?= aes_encrypt($equip->id) ?>" class="btn btn-sm btn-outline-danger rounded-2"
                                                        title="Arquivar" onclick="return confirm('Tem a certeza que deseja arquivar este equipamento?');">
                                                        <i class="fa-solid fa-box-archive"></i>
                                                    </a>

                                                <?php else: ?>
                                                    <a href="desarquivar.php?id_equipamentos=<?= aes_encrypt($equip->id) ?>" class="btn btn-sm btn-outline-primary rounded-2"
                                                        title="Desarquivar" onclick="return confirm('Deseja restaurar este equipamento o estado em circulação?');">
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

    <?php include '../../../assets/includes/footer.php'; ?>