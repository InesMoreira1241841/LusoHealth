<?php
 
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------
 
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
 
include '../../../assets/includes/head.php';
 
// --------------------------------------------------------------------
// ARQUIVAMENTO: controlo de visualização de localizações arquivadas
// --------------------------------------------------------------------
// Esta secção (e a coluna "arquivado") não fazem parte das fichas do
// professor — é uma necessidade própria deste projeto, para nunca
// eliminar localizações em definitivo.
//
// Por defeito a página só mostra localizações ativas (arquivado = 0).
// Quando o utilizador clica em "Ver localizações arquivadas", a página
// recarrega com ?ver_arquivadas=1 e passamos também a mostrar a tabela
// secundária com as localizações arquivadas (arquivado = 1).
// Usei um parâmetro GET simples (sem JavaScript) porque é o mesmo
// estilo já seguido no resto do projeto (ex: IDs encriptados por GET).
$mostrarArquivadas = isset($_GET['ver_arquivadas']) && $_GET['ver_arquivadas'] == '1';
 
// Ligação e execução da query
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    // Lista principal: apenas localizações ativas (não arquivadas)
    $stmt = $ligacao->prepare("SELECT * FROM localizacoes WHERE arquivado = 0");
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
 
    // Lista de arquivadas: só é pedida à BD se o utilizador quiser vê-la,
    // para não sobrecarregar a página com dados que normalmente não são precisos.
    $arquivadas = [];
    if ($mostrarArquivadas) {
        $stmtArq = $ligacao->prepare("SELECT * FROM localizacoes WHERE arquivado = 1");
        $stmtArq->execute();
        $arquivadas = $stmtArq->fetchAll(PDO::FETCH_OBJ);
    }
 
    $erro = '';
} catch (PDOException $erro) {
    $erro = "Aconteceu um erro na ligação.";
    $resultados = [];
    $arquivadas = [];
}
// Fecha a ligação
$ligacao = null;
?>
 
<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->
 
    <?php include '../../../assets/includes/header.php'; ?>
 
    <div class="container-fluid mt-4">
        <div class="row g-4">
 
            <?php include '../../../assets/includes/sidebar/localizacoes.php' ?>
 
            <main class="col-md-9 col-lg-10">
 
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">
 
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h2 class="fw-bold text-dark m-0">Serviços e Localizações Hospitalares</h2>
                            <p class="text-muted small m-0">Gestão de alas físicas, pisos e distribuição de dispositivos
                                biomédicos.</p>
                        </div>
                        <a href="novo.php" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i>Adicionar Localização
                        </a>
                    </div>
 
                    <!-- Filtro -->
                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <form class="row g-2 align-items-center small" id="formFiltroLocalizacoes"
                            name="form_filtro_localizacoes" method="GET">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-secondary"
                                        id="pesquisaLocalizacao" name="pesquisa_localizacao"
                                        placeholder="Pesquisar por designação, ID de edifício ou serviço...">
                                </div>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" id="btnFiltrarLocalizacoes"
                                    class="btn btn-outline-success rounded-pill fw-medium btn-sm py-2">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrar Espaços
                                </button>
                            </div>
                        </form>
                        <!--
                            NOTA: este filtro ainda não está ligado à query SQL (é apenas
                            visual, herdado da página original). Mantive-o como estava,
                            sem o implementar, para não acrescentar funcionalidades que
                            não foram pedidas. Se mais tarde quiseres, basta acrescentar
                            uma cláusula WHERE nome LIKE :pesquisa à query principal.
                        -->
                    </div>
 
                    <?php if (!empty($erro)) : ?>
                        <p class="text-center text-danger"><?= htmlspecialchars($erro) ?></p>
                    <?php else : ?>
                        <?php if (count($resultados) == 0) : ?>
                            <p class="shadow-sm border rounded-3 custom-card-rounded mb-4 border-light-subtle text-center p-4">Não existem localizações registadas.</p>
                        <?php else : ?>
 
                            <div class="table-responsive">
 
                                <table class="table table-hover align-middle">
 
                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th>ID Serviço</th>
                                            <th>Nome</th>
                                            <th>Edifício | Piso</th>
                                            <th>Responsável Técnico</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
 
                                    <tbody class="small text-secondary">
 
                                        <?php foreach ($resultados as $localizacoes) : ?>
 
                                            <tr>
                                                <td><?= htmlspecialchars($localizacoes->codigo) ?></td>
                                                <td><?= htmlspecialchars($localizacoes->nome) ?></td>
                                                <td><?= htmlspecialchars($localizacoes->edificio . ' | Piso ' . $localizacoes->piso) ?></td>
                                                <td><?= htmlspecialchars($localizacoes->responsavel) ?></td>
 
                                                <td class="text-end">
 
                                                    <div class="btn-group gap-1">
 
                                                        <a href="detalhes.php?id_localizacoes=<?= aes_encrypt($localizacoes->id) ?>"
                                                            class="btn btn-sm btn-outline-secondary rounded-2"
                                                            title="Visualizar Detalhes">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
 
                                                        <a href="editar.php?id_localizacoes=<?= aes_encrypt($localizacoes->id) ?>"
                                                            class="btn btn-sm btn-outline-success rounded-2"
                                                            title="Editar Localização">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
 
                                                        <!--
                                                            ALTERADO: ícone e título mudaram de "trash/Remover" para
                                                            "box-archive/Arquivar", porque esta ação já não elimina
                                                            a localização — apenas marca como arquivada.
                                                        -->
                                                        <a href="apagar.php?id_localizacoes=<?= aes_encrypt($localizacoes->id) ?>"
                                                            class="btn btn-sm btn-outline-danger rounded-2"
                                                            title="Arquivar Localização">
                                                            <i class="fa-solid fa-box-archive"></i>
                                                        </a>
 
                                                    </div>
 
                                                </td>
                                            </tr>
 
                                        <?php endforeach; ?>
 
                                    </tbody>
 
                                </table>
 
                            </div>
 
                        <?php endif; ?> <!-- Fecha o if (count($resultados) == 0) -->
                    <?php endif; ?> <!-- Fecha o if (!empty($erro)) -->
                </div>
 
                <div class="col">
                    <p class="mb-2">Total de Localizações Ativas: <strong> <?= count($resultados) ?> </strong></p>
                </div>
 
                <!-- ====================================================================== -->
                <!-- SECÇÃO DE LOCALIZAÇÕES ARQUIVADAS (funcionalidade extra deste projeto)   -->
                <!-- ====================================================================== -->
                <div class="bg-white p-4 mb-5 shadow-sm border border-light-subtle custom-card-rounded">
                    <?php if (!$mostrarArquivadas) : ?>
                        <a href="localizacoes.php?ver_arquivadas=1" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="fa-solid fa-box-archive me-2"></i>Desejas visualizar as localizações arquivadas?
                        </a>
                    <?php else : ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-muted m-0">
                                <i class="fa-solid fa-box-archive me-2"></i>Localizações Arquivadas
                            </h5>
                            <a href="localizacoes.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                                Ocultar arquivadas
                            </a>
                        </div>
 
                        <?php if (count($arquivadas) == 0) : ?>
                            <p class="text-muted small mb-0">Não existem localizações arquivadas.</p>
                        <?php else : ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle small text-secondary">
                                    <thead class="table-light text-secondary text-uppercase">
                                        <tr>
                                            <th>ID Serviço</th>
                                            <th>Nome</th>
                                            <th>Edifício | Piso</th>
                                            <th>Responsável</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($arquivadas as $loc) : ?>
                                            <tr class="text-muted">
                                                <td><?= htmlspecialchars($loc->codigo) ?></td>
                                                <td><?= htmlspecialchars($loc->nome) ?></td>
                                                <td><?= htmlspecialchars($loc->edificio . ' | Piso ' . $loc->piso) ?></td>
                                                <td><?= htmlspecialchars($loc->responsavel) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!--
                                NOTA: não implementei um botão "Restaurar" aqui porque não foi
                                pedido. Se vieres a precisar, é só criar um link tipo
                                restaurar.php?id_localizacoes=... que faça
                                UPDATE localizacoes SET arquivado = 0 WHERE id = :id
                                (mesmo padrão de confirmar_apagar.php, mas ao contrário).
                            -->
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
 
            </main>
 
        </div>
 
    </div>
 
    <?php include '../../../assets/includes/footer.php'; ?>