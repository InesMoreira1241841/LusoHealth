<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Inicia a sessão (se necessário) e verifica se o utilizador está autenticado

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. QUERY DOS TIPOS DE ENTRADA (O que fizemos antes)
    $stmt_tipos = $ligacao->query("SELECT DISTINCT tipo_entrada FROM equipamentos WHERE tipo_entrada IS NOT NULL AND tipo_entrada != '' ORDER BY tipo_entrada ASC");
    $tipos_existentes = $stmt_tipos->fetchAll(PDO::FETCH_COLUMN);

    // 2. NOVA QUERY: Buscar todas as categorias para o Select
    $stmt_categorias = $ligacao->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
    $categorias_existentes = $stmt_categorias->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $tipos_existentes = [];
    $categorias_existentes = []; // Evita que a página desarme se a BD falhar
}


// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recolher dados
    $nome = $_POST["designacao"] ?? "";
    $codigo_interno = $_POST["codigo_inventario"] ?? "";
    $num_serie = $_POST["num_serie"] ?? "";
    $marca = $_POST["marca"] ?? "";
    $modelo = $_POST["modelo"] ?? "";
    $fabricante = $_POST["fabricante"] ?? "";
    $ano_fabrico = $_POST["ano_fabrico"] ?? "";
    $data_aquisicao = $_POST["data_aquisicao"] ?? "";
    $custo = $_POST["custo_aquisicao"] ?? "";
    $categoria_id = $_POST["categoria_id"] ?? "";
    $tipo_entrada = $_POST["tipo_entrada"] ?? "";
    $estado = $_POST["estado"] ?? "";
    $criticidade = $_POST["criticidade"] ?? "";
    $localizacao_id = $localizacao_id["localizacao_id"] ?? "";
    $observacoes = $observacoes["observacoes"] ?? "";
}

?>

<?php include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <?php include '../../../assets/includes/sidebar/equipamentos.php'; ?>

            <main class="col-md-9 col-lg-10">

                <div class="bg-white p-4 shadow-sm border border-light-subtle">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Registar Dispositivo Médico</h2>
                        <p class="text-muted small m-0">Introduza os dados de inventário e atribuição de segurança
                            clínica.</p>
                    </div>

                    <form action=# method="POST" class="row g-3 fw-medium text-secondary small">

                        <div class="col-md-4">
                            <label for="designacao" class="form-label text-dark">Nome do Equipamento</label>
                            <input type="text" id="designacao" name="designacao" class="form-control rounded-3"
                                required placeholder="Ex: Monitor de Sinais Vitais">
                                value="<?= $_POST['designação'] ?? '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="codigo_inventario" class="form-label text-dark">Código Interno</label>
                            <input type="text" id="codigo_inventario" name="codigo_inventario" class="form-control rounded-3"
                                required placeholder="Ex: EQ-12345-XWZ">
                                value="<?= $_POST['codigo_inevntario'] ?? '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="num_serie" class="form-label text-dark">Número de Série (S/N)</label>
                            <input type="text" id="num_serie" name="num_serie" class="form-control rounded-3"
                                required placeholder="Ex: MP5-2022-45873">
                                value="<?= $_POST['num_serie'] ?? '' ?>">
                        </div>

                        <div class="col-md-2">
                            <label for="marca" class="form-label text-dark">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control rounded-3"
                                required placeholder="Ex: Philips"
                                value="<?= $_POST['marca'] ?? '' ?>">>

                        </div>

                        <div class="col-md-2">
                            <label for="modelo" class="form-label text-dark">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control rounded-3"
                                required placeholder="Ex: IntelliVue MP5"
                                value="<?= $_POST['modelo'] ?? '' ?>">>
                        </div>

                        <div class="col-md-2">
                            <label for="fabricante" class="form-label text-dark">Fabricante</label>
                            <input type="text" id="fabricante" name="fabricante" class="form-control rounded-3"
                                required placeholder="Ex: Philips"
                                value="<?= $_POST['fabricante'] ?? '' ?>">>
                        </div>

                        <div class="col-md-2">
                            <label for="ano_fabrico" class="form-label text-dark">Ano Fabrico</label>
                            <input type="number" id="ano_fabrico" name="ano_fabrico" class="form-control rounded-3"
                                required
                                value="<?= $_POST['ano_fabricante'] ?? '' ?>">>
                        </div>

                        <div class="col-md-2">
                            <label for="data_aquisicao" class="form-label text-dark">Data de Aquisição</label>
                            <input type="date" id="data_aquisicao" name="data_aquisicao" class="form-control rounded-3"
                                required
                                value="<?= $_POST['data_aquisicao'] ?? '' ?>">>
                        </div>

                        <div class="col-md-2">
                            <label for="custo_aquisicao" class="form-label text-dark">Custo de Aquisição</label>
                            <input type="text" id="custo_aquisicao" name="custo_aquisicao" class="form-control rounded-3"
                                placeholder="0.00 €" required
                                value="<?= $_POST['custo_aquisicao'] ?? '' ?>">>
                        </div>

                        <div class="col-md-4">
                            <label for="categoria_id" class="form-label text-dark">Categoria do Equipamento</label>
                            <div class="input-group">
                                <select id="categoria_id" name="categoria_id" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione uma categoria...</option>

                                    <?php foreach ($categorias_existentes as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->nome) ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <button class="btn btn-outline-success rounded-end-3" type="button" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="tipo_entrada" class="form-label text-dark">Tipo de Entrada</label>
                            <div class="input-group">

                                <select id="tipo_entrada" name="tipo_entrada" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione um tipo de entrada...</option>
                                    <option value="Compra">Compra</option>
                                    <option value="Doação">Doação</option>
                                    <option value="Aluguer">Aluger</option>
                                    <option value="Empréstimo">Empréstimo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="localizacao_id" class="form-label text-dark">Localização</label>
                            <div class="input-group">

                                <select id="localizacao_id" name="localizacao_id" class="form-select rounded-start-3">
                                    <option value="" selected disabled>Selecione uma localização...</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label text-dark">Estado</label>
                            <div class="input-group">

                                <select id="estado" name="estado" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione um estado...</option>
                                    <option value="Ativo">Ativo</option>
                                    <option value="Em manutenção">Em manutenção</option>
                                    <option value="Inativo">Inativo</option>
                                    <option value="Em calibração">Em calibração</option>
                                    <option value="Em quarentena">Em quarentena</option>
                                    <option value="Abatido">Abatido</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="criticidade" class="form-label text-dark">Criticidade</label>
                            <div class="input-group">

                                <select id="criticidade" name="criticidade" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione um nível de criticidade...</option>
                                    <option value="Baixo">Baixa</option>
                                    <option value="Médio">Médio</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Supote de vida">Supote de vida</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="observacoes" class="form-label text-dark">Observações</label>
                            <input type="text" id="observacoes" name="observacoes" class="form-control rounded-3"
                                value="<?= $_POST['observacoes'] ?? '' ?>">>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="equipamentos.php" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Equipamento
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="modalNovaCategoria" tabindex="-1" aria-labelledby="modalNovaCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="modalNovaCategoriaLabel">Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNovaCategoria">
                        <div class="mb-3">
                            <label for="nome_categoria" class="form-label text-secondary small fw-medium">Nome da Categoria</label>
                            <input type="text" class="form-control rounded-3" id="nome_categoria" placeholder="Ex: Monitores, Seringas..." required>
                        </div>
                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4" id="btnGuardarCategoria">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>