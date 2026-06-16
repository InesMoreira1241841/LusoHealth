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

// Inicializar variáveis de erro
$erros = [];

// 1. Capturar e desencriptar o ID do equipamento vindo do GET
$idEquipamentoEncrypted = $_GET['id_equipamentos'] ?? null; // Altere o nome do parâmetro GET se necessário
$idEquipamento = aes_decrypt($idEquipamentoEncrypted);

if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: equipamentos.php');
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

        // Recolher dados do formulário
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

        // Normalizar entrada
        $codigo_inventario = strtoupper($codigo_inventario);
        $designacao = ucwords(strtolower($designacao));
        $marca = ucwords(strtolower($marca));
        $modelo = ucwords(strtolower($modelo));
        $fabricante = ucwords(strtolower($fabricante));

        // Validar os dados acumulando os erros
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

        // Normalizar custo de aquisição pós-validação
        $custo_aquisicao = normalizar_custo_aquisicao($custo_aquisicao);

        // Verificar se o Código de Inventário já existe noutro equipamento (excluindo o atual)
        $sqlCheck = "SELECT COUNT(*) FROM equipamentos WHERE codigo_inventario = :codigo_inventario AND id != :id";
        $stmtCheck = $ligacao->prepare($sqlCheck);
        $stmtCheck->execute([':codigo_inventario' => $codigo_inventario, ':id' => $idEquipamento]);
        if ($stmtCheck->fetchColumn() > 0) {
            $erros[] = "O Código de Inventário '$codigo_inventario' já está registado noutro dispositivo.";
        }

        // Se continuar sem erros, faz o UPDATE
        if (empty($erros)) {
            $sqlUp = "UPDATE equipamentos SET 
                        codigo_inventario = :codigo_inventario, 
                        designacao = :designacao, 
                        categoria_id = :categoria_id, 
                        marca = :marca, 
                        modelo = :modelo, 
                        num_serie = :num_serie, 
                        fabricante = :fabricante, 
                        ano_fabrico = :ano_fabrico, 
                        data_aquisicao = :data_aquisicao, 
                        custo_aquisicao = :custo_aquisicao, 
                        tipo_entrada = :tipo_entrada, 
                        estado = :estado, 
                        criticidade = :criticidade, 
                        localizacao_id = :localizacao_id, 
                        observacoes = :observacoes,
                        atualizado_em = NOW()
                      WHERE id = :id";

            $stmtUp = $ligacao->prepare($sqlUp);
            $stmtUp->execute([
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
                ':observacoes'       => $observacoes,
                ':id'                => $idEquipamento
            ]);

            header('Location: equipamentos.php');
            exit;
        }
    }

    // 3. Procurar os dados atuais do equipamento para preencher o formulário
    $stmt = $ligacao->prepare("SELECT * FROM equipamentos WHERE id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: equipamentos.php');
        exit;
    }

    // 4. Carregar dados auxiliares para as ComboBoxes / Selects
    $stmt_tipos = $ligacao->query("SELECT DISTINCT tipo_entrada FROM equipamentos WHERE tipo_entrada IS NOT NULL AND tipo_entrada != '' ORDER BY tipo_entrada ASC");
    $tipos_existentes = $stmt_tipos->fetchAll(PDO::FETCH_COLUMN);

    $stmt_categorias = $ligacao->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
    $categorias_existentes = $stmt_categorias->fetchAll(PDO::FETCH_OBJ);

    $stmt_localizacoes = $ligacao->query("SELECT id, nome, edificio, piso FROM localizacoes ORDER BY edificio ASC");
    $localizacoes_existentes = $stmt_localizacoes->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro no sistema ou na ligação à base de dados: " . $err->getMessage();
    $tipos_existentes = [];
    $categorias_existentes = [];
    $localizacoes_existentes = [];
} finally {
    $ligacao = null;
}

include '../../../assets/includes/head.php'; ?>

<body class="bg-page-light">
    <!-- Classe personalizada para cor de fundo global -->

    <?php include '../../../assets/includes/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            <?php include '../../../assets/includes/sidebar/equipamentos.php' ?>

            <main class="col-md-9 col-lg-10">
                <div
                    class="bg-white p-4 shadow-sm border border-light-subtle main-container-height custom-card-rounded">

                    <div class="mb-4 pb-2 border-bottom">
                        <h2 class="fw-bold text-dark m-0">Modificar Registo
                            <span class="text-success">#<?= htmlspecialchars($equipamento->codigo_inventario ?? '') ?></span>
                        </h2>
                        <p class="text-muted small m-0">Atualize as informações operacionais ou reposicionamento de serviço.</p>
                    </div>

                    <form action="editar.php?id_equipamentos=<?= $idEquipamentoEncrypted ?>" method="POST" novalidate class="row g-3 fw-medium text-secondary small">

                        <div class="col-md-4">
                            <label for="designacao" class="form-label text-dark">Nome do Equipamento</label>
                            <input type="text" id="designacao" name="designacao" class="form-control rounded-3"
                                placeholder="Ex: Monitor de Sinais Vitais"
                                value="<?= htmlspecialchars($_POST['designacao'] ?? $equipamento->designacao ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="codigo_inventario" class="form-label text-dark">Código Interno</label>
                            <input type="text" id="codigo_inventario" name="codigo_inventario" class="form-control rounded-3"
                                placeholder="Ex: EQ-12345-XWZ"
                                value="<?= htmlspecialchars($_POST['codigo_inventario'] ?? $equipamento->codigo_inventario ?? '') ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="marca" class="form-label text-dark">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control rounded-3"
                                placeholder="Ex: Philips"
                                value="<?= htmlspecialchars($_POST['marca'] ?? $equipamento->marca ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="modelo" class="form-label text-dark">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control rounded-3"
                                placeholder="Ex: IntelliVue MP5"
                                value="<?= htmlspecialchars($_POST['modelo'] ?? $equipamento->modelo ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="num_serie" class="form-label text-dark">Número de Série (S/N)</label>
                            <input type="text" id="num_serie" name="num_serie" class="form-control rounded-3"
                                placeholder="Ex: MP5-2022-45873"
                                value="<?= htmlspecialchars($_POST['num_serie'] ?? $equipamento->num_serie ?? '') ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="fabricante" class="form-label text-dark">Fabricante</label>
                            <input type="text" id="fabricante" name="fabricante" class="form-control rounded-3"
                                placeholder="Ex: Philips"
                                value="<?= htmlspecialchars($_POST['fabricante'] ?? $equipamento->fabricante ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="ano_fabrico" class="form-label text-dark">Ano Fabrico</label>
                            <input type="number" id="ano_fabrico" name="ano_fabrico" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? $equipamento->ano_fabrico ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label handle for="data_aquisicao" class="form-label text-dark">Data de Aquisição</label>
                            <input type="text" id="data_aquisicao" name="data_aquisicao" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? $equipamento->data_aquisicao ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label for="custo_aquisicao" class="form-label text-dark">Custo de Aquisição</label>
                            <input type="text" id="custo_aquisicao" name="custo_aquisicao" class="form-control rounded-3"
                                placeholder="0,00 €"
                                value="<?= htmlspecialchars($_POST['custo_aquisicao'] ?? (isset($equipamento->custo_aquisicao) ? number_format($equipamento->custo_aquisicao, 2, ',', '') : '')) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="categoria_id" class="form-label text-dark">Categoria do Equipamento</label>
                            <select id="categoria_id" name="categoria_id" class="form-select rounded-3" required>
                                <option value="" disabled>Selecione uma categoria...</option>
                                <?php
                                $cat_selecionada = $_POST['categoria_id'] ?? $equipamento->categoria_id ?? '';
                                foreach ($categorias_existentes as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= ($cat_selecionada == $cat->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->nome) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="tipo_entrada" class="form-label text-dark">Tipo de Entrada</label>
                            <select id="tipo_entrada" name="tipo_entrada" class="form-select rounded-3" required>
                                <option value="" disabled>Selecione um tipo de entrada...</option>
                                <?php
                                $tipo_selecionado = $_POST['tipo_entrada'] ?? $equipamento->tipo_entrada ?? '';
                                $opcoes_tipo = ['Compra', 'Doação', 'Aluguer', 'Empréstimo'];
                                foreach ($opcoes_tipo as $opcao): ?>
                                    <option value="<?= $opcao ?>" <?= ($tipo_selecionado == $opcao) ? 'selected' : '' ?>><?= $opcao ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="localizacao_id" class="form-label text-dark">Localização</label>
                            <select id="localizacao_id" name="localizacao_id" class="form-select rounded-3" required>
                                <option value="" disabled>Selecione uma localização...</option>
                                <?php
                                $loc_selecionada = $_POST['localizacao_id'] ?? $equipamento->localizacao_id ?? '';
                                foreach ($localizacoes_existentes as $loc): ?>
                                    <option value="<?= $loc->id ?>" <?= ($loc_selecionada == $loc->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($loc->edificio) ?> | <?= htmlspecialchars($loc->nome) ?> (Piso <?= htmlspecialchars($loc->piso) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label text-dark">Estado</label>
                            <select id="estado" name="estado" class="form-select rounded-3" required>
                                <option value="" disabled>Selecione um estado...</option>
                                <?php
                                $estado_selecionado = $_POST['estado'] ?? $equipamento->estado ?? '';
                                $opcoes_estado = ['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'];
                                foreach ($opcoes_estado as $opcao): ?>
                                    <option value="<?= $opcao ?>" <?= ($estado_selecionado == $opcao) ? 'selected' : '' ?>><?= $opcao ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="criticidade" class="form-label text-dark">Criticidade</label>
                            <select id="criticidade" name="criticidade" class="form-select rounded-3" required>
                                <option value="" disabled>Selecione um nível de criticidade...</option>
                                <?php
                                $crit_selecionada = $_POST['criticidade'] ?? $equipamento->criticidade ?? '';
                                $opcoes_crit = ['Baixa', 'Média', 'Alta', 'Suporte de vida'];
                                foreach ($opcoes_crit as $opcao): ?>
                                    <option value="<?= $opcao ?>" <?= ($crit_selecionada == $opcao) ? 'selected' : '' ?>><?= $opcao ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="observacoes" class="form-label text-dark">Observações</label>
                            <input type="text" id="observacoes" name="observacoes" class="form-control rounded-3"
                                value="<?= htmlspecialchars($_POST['observacoes'] ?? $equipamento->observacoes ?? '') ?>">
                        </div>

                        <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end border-top">
                            <a href="equipamentos.php" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Alterações
                            </button>
                        </div>

                    </form>

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

    <script>
        flatpickr("#data_aquisicao", {
            dateFormat: "Y-m-d"
        });
    </script>

    <?php include '../../../assets/includes/footer.php'; ?>