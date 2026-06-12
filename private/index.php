<?php

// Inicia a sessão para poder usar a variável $_SESSION
require_once '../includes/funcoes.php';
start_session();


// --------------------------------------------------------------------
// SEGURANÇA: Impede que o utilizador aceda diretamente a este script.
// Este ficheiro deve ser acedido apenas através de submissão de formulário (POST).
// Se for acedido diretamente (por URL), será redirecionado para o login.
// --------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
 // Redireciona para o formulário de login (interface pública)
 header('Location: ../public/login.php');
 // Encerra a execução do script imediatamente após o redirecionamento
 return;
} 

// --------------------------------------------------------------------
// RECOLHA DE DADOS DO FORMULÁRIO
// --------------------------------------------------------------------
// Verifica se o campo 'text_username' foi enviado via POST.
// Se sim, guarda-o na variável $username. Caso contrário, usa string vazia.
$username = isset($_POST['utilizador']) ? $_POST['utilizador'] : '';
// O mesmo para o campo da password.
$password = isset($_POST['password']) ? $_POST['password'] : '';

// --------------------------------------------------------------------
// VALIDAÇÃO DOS DADOS
// --------------------------------------------------------------------
// Inicializa um array vazio para guardar mensagens de erro de validação
$validation_errors = [];

// Verifica se o nome de utilizador tem um comprimento entre 5 e 50 caracteres
// Isto evita usernames demasiado curtos ou excessivamente longos
if (strlen($username) < 5 || strlen($username) > 50) {
 $validation_errors[] = 'O nome de utilizador deve ter entre 5 e 50 caracteres.';
}
// Verifica se a password tem um comprimento entre 6 e 12 caracteres
// Garante uma password minimamente segura, mas fácil de recordar
if (strlen($password) < 6 || strlen($password) > 12) {
 $validation_errors[] = 'A palavra-passe deve ter entre 6 e 12 caracteres.';
}






// Se existirem erros de validação, guarda-os na sessão
// Depois, redireciona o utilizador de volta para o formulário de login
if (!empty($validation_errors)) {
 $_SESSION['validation_errors'] = $validation_errors;
 // Redireciona para a página de login (ou outro formulário)
 header('Location: ../public/login.php'); // ou 'login_form.php'

 // Encerra o script para impedir execução posterior
 return;
}


// --------------------------------------------------------------------
// SIMULAÇÃO DE RESULTADO DE LOGIN (antes da ligação real à base de dados)
// --------------------------------------------------------------------
// Simula o resultado que viria de uma verificação à base de dados
// Neste caso, assume-se que o login é válido (status = 1)
// Mais tarde, esta variável será substituída por um resultado real vindo da BD
$result['status'] = 1; // 1 = login válido, 0 = inválido

// Verifica se o status retornado indica login inválido
if (!$result['status']) {
 // Se o login for inválido, guarda uma mensagem de erro na sessão
 $_SESSION['server_error'] = 'Login inválido';

 // Redireciona o utilizador novamente para o formulário de login
 header('Location: ../public/login.php');

 // Encerra o script para não continuar o processamento
 return;
}
// Se o status for 1 (válido), o código continuará — aqui será futuramente criada a sessão do utilizador e o redirecionamento para a área privada.


// --------------------------------------------------------------------
// APRESENTAÇÃO DE DADOS ENVIADOS
// --------------------------------------------------------------------
echo "Utilizador: " . $username . "<br>";
echo "Password: " . $password;
// Em produção, **nunca** se deve mostrar a password assim — isto é apenas para testes!

// -------------------------------------------------------------------
// LOGIN BEM-SUCEDIDO: Guardar o utilizador na sessão
// --------------------------------------------------------------------
// Guarda o nome de utilizador na sessão para identificar o utilizador autenticado
$_SESSION['utilizador'] = $username;
// Agora código da área privada 

?> 

<?php include '../assets/includes/head.php'; ?>

<body class="bg-page-light">
<!-- Classe personalizada para cor de fundo global -->

    <?php include '../assets/includes/header.php'; ?>

            <!-- Container principal da página -->
    <div class="container-fluid mt-4">

        <!-- Estrutura principal em grelha -->
        <div class="row g-4">

            <?php include '../assets/includes/sidebar/dashboard.php' ?>

            <!-- Conteúdo principal da dashboard -->
            <main class="col-md-9 col-lg-10">
                
                <!-- Secção de indicadores estatísticos -->
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Total Equipamentos</h6>
                            <p class="fs-2 fw-bold text-dark m-0 font-monospace">142</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos Ativos</h6>
                            <p class="fs-2 fw-bold text-secondary m-0 font-monospace">100</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos em Manutenção</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace">40</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos Inativos</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace">40</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos em (Serviço)</h6>
                            <p class="fs-2 fw-bold text-dark m-0 font-monospace">40</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos com Garantia Expirada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace">40</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Equipamentos sem Documentação Associada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace">40</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Dispositivos de Criticidade Elevada</h6>
                            <p class="fs-2 fw-bold text-danger m-0 font-monospace">28</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-white p-4 shadow-sm border border-light-subtle text-center custom-card-rounded">
                            <h6 class="text-muted text-uppercase small fw-bold">Garantias Próximas do Fim (nos prox 30 dias)</h6>
                            <p class="fs-2 fw-bold text-warning m-0 font-monospace">5</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle mb-4 custom-card-rounded">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-chart-pie me-2 text-success"></i>Equipamentos por Serviço
                    </h5>
                    <div class="p-5 bg-light text-center rounded-3 border border-dashed">
                        <p class="text-muted m-0 small">
                            <i class="fa-solid fa-code fa-xl mb-2 d-block text-secondary"></i>
                            Área reservada para renderização do gráfico via JavaScript / Chart.js no ficheiro <span class="font-monospace">1241841.js</span>.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle mb-4 custom-card-rounded">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-chart-pie me-2 text-success"></i>Distribuição por Localização
                    </h5>
                    <div class="p-5 bg-light text-center rounded-3 border border-dashed">
                        <p class="text-muted m-0 small">
                            <i class="fa-solid fa-code fa-xl mb-2 d-block text-secondary"></i>
                            Área reservada para renderização do gráfico via JavaScript / Chart.js no ficheiro <span class="font-monospace">1241841.js</span>.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-4 shadow-sm border border-light-subtle mb-4 custom-card-rounded">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-chart-pie me-2 text-success"></i>Equipamentos de Suporte de Vida por Serviço
                    </h5>
                    <div class="p-5 bg-light text-center rounded-3 border border-dashed">
                        <p class="text-muted m-0 small">
                            <i class="fa-solid fa-code fa-xl mb-2 d-block text-secondary"></i>
                            Área reservada para renderização do gráfico via JavaScript / Chart.js no ficheiro <span class="font-monospace">1241841.js</span>.
                        </p>
                    </div>
                </div>

            </main>

        </div>

    </div>

<?php include '../assets/includes/footer.php'; ?> 