<?php
// Inicia a sessão (necessário para usar $_SESSION)
session_start();
// Inicia a sessão PHP, permitindo aceder às variáveis armazenadas em $_SESSION
$validation_errors = [];
// Inicializa um array que irá conter erros de validação (por exemplo, campos em branco, credenciais erradas

// --------------------------------------------------------------------
// RECOLHA DE MENSAGENS TEMPORÁRIAS DA SESSÃO
// --------------------------------------------------------------------
// Verifica se existe um array de erros de validação na sessão
if (!empty($_SESSION['validation_errors'])) {
    // Se existirem, copia-os para a variável local
    $validation_errors = $_SESSION['validation_errors'];
    // Remove os erros da sessão para que não apareçam novamente numa recarga de página
    unset($_SESSION['validation_errors']);
}
// Inicializa a variável que irá conter erros de servidor
$server_error = [];
// Verifica se existe algum erro de servidor guardado na sessão
if (!empty($_SESSION['server_error'])) {
    // Se existir, copia-o para a variável local
    $server_error = $_SESSION['server_error'];
    // Remove o erro da sessão após ser lido
    unset($_SESSION['server_error']);
}
?>

<?php include '../assets/includes/head.php'; ?>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container-fluid mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5 col-lg-4">

                <div class="card p-4">

                    <div class="text-center mb-4">
                        <div class="icon-circle-main bg-success-light text-success-custom mx-auto mb-2">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Área Reservada</h4>
                        <p class="small text-muted">Introduza as suas credenciais de acesso</p>
                    </div>

                    <form action="../private/processa_login.php" method="POST">

                        <div class="mb-3">

                            <label for="utilizador" class="form-label fw-semibold text-muted small">Utilizador</label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                    <i class="fa-solid fa-user small"></i>
                                </span>

                                <input type="text" id="utilizador" name="utilizador"
                                    class="form-control bg-light border-start-0 rounded-end-3 focus-custom" placeholder="Ex: ines.moreira" required>

                            </div>

                        </div>

                        <div class="mb-4">

                            <label for="password" class="form-label fw-semibold text-muted small">Palavra-Passe</label>

                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                    <i class="fa-solid fa-key small"></i>
                                </span>

                                <input type="password" id="password" name="password"
                                    class="form-control bg-light border-start-0 rounded-end-3 focus-custom" placeholder="*******" required>
                            </div>
                        </div>

                        <div class="alert alert-danger p-2 text-center small rounded-3 mb-4 d-none" id="login-erro">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Erro: Utilizador ou password incorretos.
                        </div>

                        <button type="submit" class="btn btn-success-custom w-100 fw-bold py-2.5 rounded-pill shadow-sm">
                            Entrar <i class="fa-solid fa-right-to-bracket ms-2"></i>
                        </button>

                        <!-- -------------------------------------------------------------------- -->
                        <!-- APRESENTAÇÃO DE MENSAGENS DE ERRO (VALIDAÇÃO E SERVIDOR) -->
                        <!-- -------------------------------------------------------------------- -->

                        <!-- Verifica se existem erros de validação -->
                        <!-- Verifica se existem erros de validação -->
                        <?php if (!empty($validation_errors)) : ?>
                            <!-- Se existirem, apresenta um alerta de erro (vermelho) usando as classes do Bootstrap -->
                            <div class="mt-4 alert alert-danger p-2 text-center">
                            <!--
                                mt-4: dá um pequeno espaçamento só no topo
                                alert: cria a "caixa" estrutural
                                alert-danger: cor vermelha
                                p-2: cria espaço dentro do elemento, afastando o conteúdo da borda da própria caixa
                                text-center: alinha o texto ao centro
                            -->

                                <!-- Percorre todos os erros de validação -->
                                <?php foreach ($validation_errors as $error) : ?>

                                    <!-- Mostra cada erro dentro de uma <div>, escapando caracteres especiais para segurança -->
                                    <div><?= htmlspecialchars($error) ?></div>
                                <?php endforeach; ?>
                            </div>

                        <?php endif; ?>
                        <!-- Verifica se existe um erro de servidor -->
                        <?php if (!empty($server_error)) : ?>
                            <!-- Apresenta também num alerta de erro (vermelho) -->
                            <div class="alert alert-danger p-2 text-center">
                                <!-- Mostra o erro do servidor, também escapado com htmlspecialchars -->
                                <div><?= htmlspecialchars($server_error) ?></div>
                            </div>
                        <?php endif; ?>

                    </form>



                    <div class="text-center mt-4">

                        <a href="index.php" class="text-decoration-none small text-success-custom fw-semibold">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar à página inicial
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

    <?php include '../assets/includes/footer.php'; ?>