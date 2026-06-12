<?php
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
// APRESENTAÇÃO DE DADOS ENVIADOS
// --------------------------------------------------------------------
echo "Utilizador: " . $username . "<br>";
echo "Password: " . $password;
// Em produção, **nunca** se deve mostrar a password assim — isto é apenas para testes!
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