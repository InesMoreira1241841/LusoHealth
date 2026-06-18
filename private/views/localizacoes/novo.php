<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// -------------------------------------------------------------------- 


require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados
    $codigo = $_POST["codigo"] ?? "";
    $nome = $_POST["nome"] ?? "";
    $edificio = $_POST["edificio"] ?? "";
    $piso = $_POST["piso"] ?? "";
    $responsavel = $_POST["responsavel"] ?? "";
    $observacoes = $_POST["observacoes"] ?? "";

    // A. Normalizar entrada 

    $codigo = strtoupper($codigo);
    $nome = ucwords(strtolower($nome));
    $edificio = ucwords(strtolower($edificio));
    $responsavel = ucwords(strtolower($responsavel));

    // 2. Validar os dados acumulando os erros corretamente

    $erros = [];
    $erros = array_merge($erros, validar_codigo($codigo) ?? []);
    $erros = array_merge($erros, validar_nome($nome) ?? []);
    $erros = array_merge($erros, validar_edificio($edificio) ?? []);
    $erros = array_merge($erros, validar_piso($piso) ?? []);
    $erros = array_merge($erros, validar_responsavel($responsavel) ?? []);

    // 3. Se não houver erros, guardar na base de dados

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // --- VERIFICAÇÃO DE DUPLICADO AQUI ---
            $sqlCheck = "SELECT COUNT(*) FROM localizacoes WHERE codigo = :codigo";
            $stmtCheck = $ligacao->prepare($sqlCheck);
            $stmtCheck->execute([':codigo' => $codigo]);

            if ($stmtCheck->fetchColumn() > 0) {
                // Se já existir, injetamos o erro no teu array global de erros
                $erros[] = "O ID da Localização '$codigo' já está registado. Escolha um código único.";
            }

            // 4. Se continuar sem erros (ou seja, o código é único), faz-se o INSERT
            if (empty($erros)) {
                $sql = "INSERT INTO localizacoes (codigo, nome, edificio, piso, responsavel, observacoes) 
                        VALUES (:codigo, :nome, :edificio, :piso, :responsavel, :observacoes)";

                $stmt = $ligacao->prepare($sql);
                $stmt->execute([
                    ':codigo' => $codigo,
                    ':nome' => $nome,
                    ':edificio' => $edificio,
                    ':piso' => $piso,
                    ':responsavel' => $responsavel,
                    ':observacoes' => $observacoes
                ]);

                $_SESSION['success_message'] = "Localização hospitalar registada com sucesso.";
                header('Location: localizacoes.php');
                exit;
            }
        } catch (PDOException $err) {
            $erros[] = "Erro ao gravar os dados: " . $err->getMessage();
        } finally {
            $ligacao = null;
        }
    }
}

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
                        <h2 class="fw-bold text-dark m-0">Registar Nova Localização</h2>
                        <p class="text-muted small m-0">Crie um novo espaço ou serviço para alocação de dispositivos
                            biomédicos.</p>
                    </div>

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

                    <form action=# method="POST" class="small text-secondary">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="codigoLocalizacao" class="form-label fw-bold text-dark">ID da Localização</label>
                                <input type="text" class="form-control font-monospace text-uppercase"
                                    name="codigo" placeholder="Ex: SRV-UCI" required
                                    value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>">
                            </div>

                            <div class="col-md-8">
                                <label for="nomeLocalizacao" class="form-label fw-bold text-dark">Nome do Serviço / Ala</label>
                                <input type="text" class="form-control" name="nome"
                                    placeholder="Ex: Unidade de Cuidados Intensivos" required
                                    value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="edificioLocalizacao" class="form-label fw-bold text-dark">Edifício / Bloco</label>
                                <input type="text" class="form-control font-monospace text-uppercase"
                                    name="edificio" placeholder="Ex: Bloco B - Cirúrgico" required
                                    value="<?= htmlspecialchars($_POST['edificio'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="pisoLocalizacao" class="form-label fw-bold text-dark">Piso</label>
                                <input type="number" class="form-control" name="piso"
                                    placeholder="Ex: 2" min="-2" max="7" required
                                    value="<?= htmlspecialchars($_POST['piso'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="responsavelLocalizacao" class="form-label fw-bold text-dark">Responsável</label>
                                <input type="text" class="form-control font-monospace text-uppercase"
                                    name="responsavel" placeholder="Ex: Eng. Inês Moreira" required
                                    value="<?= htmlspecialchars($_POST['responsavel'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="observacoesLocalizacao" class="form-label fw-bold text-dark">Observações</label>
                                <input type="text" class="form-control font-monospace text-uppercase" name="observacoes"
                                    value="<?= htmlspecialchars($_POST['observacoes'] ?? '') ?>">
                            </div>

                            <div class="col-12 pt-3 mt-2 mb-4 d-flex gap-2 justify-content-end">
                                <a href="localizacoes.php"
                                    class="btn btn-light border rounded-pill px-4 fw-medium">Cancelar</a>
                                <button type="submit" id="btnSalvarLocalizacao"
                                    class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Gravar Espaço
                                </button>
                            </div>

                        </div>
                    </form>
                
                </div>
            </main>
        </div>
    </div>

    <?php include '../../../assets/includes/footer.php'; ?>