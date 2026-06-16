<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// Inicializar arrays para evitar erros na renderização do HTML
$erros = [];
$erros_sistema = [];


// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados
    $codigo_inventario = $_POST["codigo_inventario"] ?? "";
    $designacao        = $_POST["designacao"] ?? "";
    $categoria_id      = $_POST["categoria_id"] ?? "";
    $marca             = $_POST["marca"] ?? "";
    $modelo            = $_POST["modelo"] ?? "";
    $num_serie         = $_POST["num_serie"] ?? "";
    $fabricante        = $_POST["fabricante"] ?? "";
    $ano_fabrico       = $_POST["ano_fabrico"] ?? "";
    $data_aquisicao    = $_POST["data_aquisicao"] ?? "";
    $custo_aquisicao   = $_POST["custo_aquisicao"] ?? "";
    $tipo_entrada      = $_POST["tipo_entrada"] ?? "";
    $estado            = $_POST["estado"] ?? "";
    $criticidade       = $_POST["criticidade"] ?? "";
    $localizacao_id    = $_POST["localizacao_id"] ?? "";
    $observacoes       = $_POST["observacoes"] ?? "";

    // A. Normalizar entrada 

    $codigo_inventario = strtoupper($codigo_inventario);
    $designacao = ucwords(strtolower($designacao));
    $marca = ucwords(strtolower($marca));
    $modelo = ucwords(strtolower($modelo));
    $fabricante = ucwords(strtolower($fabricante));


    // 2. Validar os dados acumulando os erros corretamente

    $erros = [];
    $erros = array_merge($erros, validar_designacao($designacao) ?? []);
    $erros = array_merge($erros, validar_codigo_inventario($codigo_inventario) ?? []);
    $erros = array_merge($erros, validar_marca($marca) ?? []);
    $erros = array_merge($erros, validar_modelo($modelo) ?? []);
    $erros = array_merge($erros, validar_num_serie($num_serie) ?? []);
    $erros = array_merge($erros, validar_fabricante($fabricante) ?? []);
    $erros = array_merge($erros, validar_ano_fabrico($ano_fabrico) ?? []);
    $erros = array_merge($erros, validar_data_aquisicao($data_aquisicao) ?? []);
    $erros = array_merge($erros, validar_custo_aquisicao($custo_aquisicao) ?? []);
    $erros = array_merge($erros, validar_categoria($categoria_id) ?? []);
    $erros = array_merge($erros, validar_localizacao_id($localizacao_id) ?? []);
    $erros = array_merge($erros, validar_tipo_entrada($tipo_entrada) ?? []);
    $erros = array_merge($erros, validar_estado($estado) ?? []);
    $erros = array_merge($erros, validar_criticidade($criticidade) ?? []);

    // B. Normalizar custo de aquisição (depois da validação, formato PT: 1.250,00 € -> 1250.00)
    $custo_aquisicao = normalizar_custo_aquisicao($custo_aquisicao);

    // 3. Se não houver erros, guardar na base de dados

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verifica se já existe um equipamento com o mesmo código de inventário
            $sqlCheck = "SELECT COUNT(*) FROM equipamentos WHERE codigo_inventario = :codigo_inventario";
            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([':codigo_inventario' => $codigo_inventario]);

            if ($stmtCheck->fetchColumn() > 0) {
                // Se já existir, injetamos o erro no teu array global de erros
                $erros[] = "O Código de Inventário '$codigo_inventario' já está registado. Escolha um código único.";
            }

            // 4. Se continuar sem erros (o código é único), faz-se o INSERT
            if (empty($erros)) {
                $sql = "INSERT INTO equipamentos (
                            codigo_inventario, designacao, categoria_id, marca, modelo, 
                            num_serie, fabricante, ano_fabrico, data_aquisicao, 
                            custo_aquisicao, tipo_entrada, estado, criticidade, 
                            localizacao_id, observacoes) 
                        VALUES (
                            :codigo_inventario, :designacao, :categoria_id, :marca, :modelo, 
                            :num_serie, :fabricante, :ano_fabrico, :data_aquisicao, 
                            :custo_aquisicao, :tipo_entrada, :estado, :criticidade, 
                            :localizacao_id, :observacoes)";

                $stmt = $ligacao->prepare($sql);
                $stmt->execute([
                    ':codigo_inventario' => $codigo_inventario,
                    ':designacao'        => $designacao,
                    ':categoria_id'      => $categoria_id,
                    ':marca'             => $marca,
                    ':modelo'            => $modelo,
                    ':num_serie'         => $num_serie,
                    ':fabricante'        => $fabricante,
                    ':ano_fabrico'       => $ano_fabrico,
                    ':data_aquisicao'    => $data_aquisicao,
                    ':custo_aquisicao'   => $custo_aquisicao,
                    ':tipo_entrada'      => $tipo_entrada,
                    ':estado'            => $estado,
                    ':criticidade'       => $criticidade,
                    ':localizacao_id'    => $localizacao_id,
                    ':observacoes'       => $observacoes
                ]);

                header('Location: equipamentos.php');
                exit;
            }
        } catch (PDOException $err) {
            $erros[] = "Erro ao gravar os dados: " . $err->getMessage();
        } finally {
            $ligacao = null;
        }
    }
}

// --------------------------------------------------------------------
// BLOCO EXTRA: Procurar dados para as ComboBoxes / Selects do Formulário
// (Executado sempre que a página carrega, seja via GET ou se houver erros no POST)
// --------------------------------------------------------------------
$erros = $erros ?? [];

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Tipos de entrada existentes
    $stmt_tipos = $ligacao->query("SELECT DISTINCT tipo_entrada FROM equipamentos WHERE tipo_entrada IS NOT NULL AND tipo_entrada != '' ORDER BY tipo_entrada ASC");
    $tipos_existentes = $stmt_tipos->fetchAll(PDO::FETCH_COLUMN);

    // 2. Categorias
    $stmt_categorias = $ligacao->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
    $categorias_existentes = $stmt_categorias->fetchAll(PDO::FETCH_OBJ);

    // 3. Localizações
    $stmt_localizacoes = $ligacao->query("SELECT id, nome, edificio, piso FROM localizacoes ORDER BY edificio ASC");
    $localizacoes_existentes = $stmt_localizacoes->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $tipos_existentes = [];
    $categorias_existentes = [];
    $localizacoes_existentes = [];
    $erros[] = "Erro ao carregar listas do formulário: " . $e->getMessage();
} finally {
    $ligacao = null;
}

include '../../../assets/includes/head.php'; ?>

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
                                placeholder="Ex: Monitor de Sinais Vitais"
                                value="<?= htmlspecialchars($_POST['designacao'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="codigo_inventario" class="form-label text-dark">Código Interno</label>
                            <input type="text" id="codigo_inventario" name="codigo_inventario" class="form-control rounded-3"
                                placeholder="Ex: EQ-12345-XWZ"
                                value="<?= htmlspecialchars($_POST['codigo_inventario'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="marca" class="form-label text-dark">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control rounded-3"
                                placeholder="Ex: Philips"
                                value="<?= htmlspecialchars($_POST['marca'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="modelo" class="form-label text-dark">Modelo</label>
                            <input
                                type="text"
                                id="modelo"
                                name="modelo"
                                class="form-control rounded-3"
                                placeholder="Ex: IntelliVue MP5"
                                value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label for="num_serie" class="form-label text-dark">Número de Série (S/N)</label>
                            <input type="text" id="num_serie" name="num_serie" class="form-control rounded-3"
                                placeholder="Ex: MP5-2022-45873"
                                value="<?= htmlspecialchars($_POST['num_serie'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="fabricante" class="form-label text-dark">Fabricante</label>
                            <input
                                type="text"
                                id="fabricante"
                                name="fabricante"
                                class="form-control rounded-3"
                                placeholder="Ex: Philips"
                                value="<?= htmlspecialchars($_POST['fabricante'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label for="ano_fabrico" class="form-label text-dark">Ano Fabrico</label>
                            <input
                                type="number"
                                id="ano_fabrico"
                                name="ano_fabrico"
                                class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label for="data_aquisicao" class="form-label text-dark">Data de Aquisição</label>
                            <input
                                type="text"
                                id="data_aquisicao"
                                name="data_aquisicao"
                                class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label for="custo_aquisicao" class="form-label text-dark">Custo de Aquisição</label>
                            <input
                                type="text"
                                id="custo_aquisicao"
                                name="custo_aquisicao"
                                class="form-control rounded-3"
                                placeholder="0.00 €"
                                value="<?= htmlspecialchars($_POST['custo_aquisicao'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="categoria_id" class="form-label text-dark">Categoria do Equipamento</label>
                            <div class="input-group">
                                <select id="categoria_id" name="categoria_id" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione uma categoria...</option>

                                    <?php foreach ($categorias_existentes as $cat): ?>
                                        <option 
                                            value="<?= $cat->id ?>"
                                            <?= (($_POST['categoria_id'] ?? '') == $cat->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->nome) ?>
                                        </option>
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
                                    <option value="Compra" <?= (($_POST['tipo_entrada'] ?? '') == 'Compra') ? 'selected' : '' ?>>Compra</option>
                                    <option value="Doação" <?= (($_POST['tipo_entrada'] ?? '') == 'Doação') ? 'selected' : '' ?>>Doação</option>
                                    <option value="Aluguer" <?= (($_POST['tipo_entrada'] ?? '') == 'Aluguer') ? 'selected' : '' ?>>Aluguer</option>
                                    <option value="Empréstimo" <?= (($_POST['tipo_entrada'] ?? '') == 'Empréstimo') ? 'selected' : '' ?>>Empréstimo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="localizacao_id" class="form-label text-dark">Localização</label>
                            <div class="input-group">

                                <select id="localizacao_id" name="localizacao_id" class="form-select rounded-start-3">
                                    <option value="" selected disabled>Selecione uma localização...</option>

                                    <?php foreach ($localizacoes_existentes as $loc): ?>
                                        <option
                                            value="<?= $loc->id ?>"
                                            <?= (($_POST['localizacao_id'] ?? '') == $loc->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($loc->edificio) ?> |
                                            <?= htmlspecialchars($loc->nome) ?>
                                            (Piso <?= htmlspecialchars($loc->piso) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label text-dark">Estado</label>
                            <div class="input-group">

                                <select id="estado" name="estado" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione um estado...</option>
                                    <option value="Ativo" <?= (($_POST['estado'] ?? '') == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
                                    <option value="Em manutenção" <?= (($_POST['estado'] ?? '') == 'Em manutenção') ? 'selected' : '' ?>>Em manutenção</option>
                                    <option value="Inativo"> <?= (($_POST['estado'] ?? '') == 'Inativo') ? 'selected' : '' ?>Inativo</option>
                                    <option value="Em calibração" <?= (($_POST['estado'] ?? '') == 'Em calibração') ? 'selected' : '' ?>>Em calibração</option>
                                    <option value="Em quarentena" <?= (($_POST['estado'] ?? '') == 'Em quarentena') ? 'selected' : '' ?>>Em quarentena</option>
                                    <option value="Abatido" <?= (($_POST['estado'] ?? '') == 'Abatido') ? 'selected' : '' ?>>Abatido</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="criticidade" class="form-label text-dark">Criticidade</label>
                            <div class="input-group">

                                <select id="criticidade" name="criticidade" class="form-select rounded-start-3" required>
                                    <option value="" selected disabled>Selecione um nível de criticidade...</option>
                                    <option value="Baixa" <?= (($_POST['criticidade'] ?? '') == 'Baixa') ? 'selected' : '' ?>>Baixa</option>
                                    <option value="Média" <?= (($_POST['criticidade'] ?? '') == 'Média') ? 'selected' : '' ?>>Média</option>
                                    <option value="Alta" <?= (($_POST['criticidade'] ?? '') == 'Alta') ? 'selected' : '' ?>>Alta</option>
                                    <option value="Suporte de vida" <?= (($_POST['criticidade'] ?? '') == 'Suporte de vida') ? 'selected' : '' ?>>Suporte de vida</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="observacoes" class="form-label text-dark">Observações</label>
                            <input type="text" id="observacoes" name="observacoes" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['observacoes'] ?? '') ?>">
                        </div>

                        <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end">
                            <a href="equipamentos.php" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Equipamento
                            </button>
                        </div>
                    </form>

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

    <script>
        flatpickr("#data_aquisicao", {
            dateFormat: "Y-m-d"
        });
    </script>

    <?php include '../../../assets/includes/footer.php'; ?>