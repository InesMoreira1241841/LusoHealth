<?php include '../assets/includes/head.php'; ?>

<body>

    <!-- Navegação -->

    <header class="custom-header sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg container">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
                    <img src="../assets/img/logo.png" alt="Logo LusoHealth" width="40" height="40">
                    <span class="fw-bold brand-title"><?php echo APP_NAME; ?></span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav gap-3 text-center">
                        <li class="nav-item"><a class="nav-link custom-nav-link text-uppercase fw-semibold"
                                href="#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link custom-nav-link text-uppercase fw-semibold"
                                href="#quem-somos">Quem Somos</a></li>
                        <li class="nav-item"><a class="nav-link custom-nav-link text-uppercase fw-semibold"
                                href="#servicos">Serviços</a></li>
                        <li class="nav-item"><a class="nav-link custom-nav-link text-uppercase fw-semibold"
                                href="#formulario">Fale Connosco</a></li>
                    </ul>
                </div>

                <div class="d-none d-lg-block">
                    <a href="login.php" class="btn btn-login-custom fw-bold px-4 rounded-pill shadow-sm">
                        ENTRAR <i class="fa-solid fa-right-to-bracket ms-2"></i>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Seção "Conteudo da pagina" -->
    <main class="container my-5">

        <section id="home" class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold text-dark mb-3">Inovação e Eficiência na Gestão de Equipamentos Médicos
                    </h1>
                    <p class="lead text-muted lh-base">
                        A <strong class="text-success-custom"><?php echo APP_NAME; ?></strong> impulsiona a transformação digital no
                        setor da saúde através de soluções inteligentes para a gestão hospitalar.
                        O nosso sistema de inventário clínico foi desenvolvido para responder às
                        exigências da Engenharia Biomédica, garantindo segurança, rastreabilidade e
                        maior eficiência na gestão de equipamentos médicos.
                    </p>
                </div>
                <div class="col-lg-5 text-center">
                    <img class="img-fluid rounded-4 shadow-sm" src="../assets/img/index_img01.png"
                        alt="Equipamento Hospitalar">
                </div>
            </div>
        </section>

        <section id="quem-somos" class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <div class="row g-5">
                <div class="col-md-6">
                    <h2 class="h3 fw-bold text-dark border-bottom-custom pb-2 mb-3">Sobre o Projeto</h2>
                    <p class="text-muted text-justify lh-base">
                        O <strong><?php echo APP_NAME; ?></strong> é um sistema web desenvolvido no âmbito da
                        unidade curricular de <strong>Sistemas de Informação e Bases de Dados Aplicados à Saúde
                            (SIBDAS)</strong>,
                        integrada no curso de Licenciatura em <strong>Engenharia Biomédica</strong> do
                        <a href="https://www.isep.ipp.pt" target="_blank"
                            class="text-success-custom fw-semibold text-decoration-none">Instituto Superior de
                            Engenharia do Porto (ISEP)</a>.
                    </p>
                    <p class="text-muted text-justify lh-base">
                        Este projeto tem como objetivo principal o desenvolvimento de uma plataforma
                        inteligente para o inventário clínico hospitalar, otimizando a rastreabilidade,
                        gestão de fornecedores e controlo de criticidade de equipamentos médicos
                        essenciais ao ecossistema de saúde.
                    </p>
                </div>

                <div class="col-md-6">
                    <h2 class="h3 fw-bold text-dark border-bottom-custom pb-2 mb-3">Autor do Projeto</h2>
                    <div class="card bg-light border-0 rounded-4 shadow-sm p-4">
                        <div class="row align-items-center g-4">
                            <div class="col-sm-4 text-center">
                                <img src="../assets/img/sobre_nos_img01.png" alt="Inês Moreira"
                                    class="img-fluid rounded-circle border border-3 border-success-custom shadow-sm"
                                    style="max-width: 110px;">
                            </div>
                            <div class="col-sm-8">
                                <h4 class="h5 fw-bold text-dark mb-1">Inês Moreira</h4>
                                <p class="small text-muted mb-3 fw-semibold">Estudante de Engenharia Biomédica</p>

                                <div class="small text-muted">
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-id-card text-success-custom"></i>
                                        <span><strong>Número:</strong> 1241841</span>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-envelope text-success-custom"></i>
                                        <span><strong>E-mail:</strong> <a href="mailto:1241841@isep.ipp.pt"
                                                class="text-decoration-none text-dark">1241841@isep.ipp.pt</a></span>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <i class="fa-brands fa-linkedin text-success-custom"></i>
                                        <span><a href="https://pt.linkedin.com/in/inês-moreira-b1256238b"
                                                target="_blank"
                                                class="btn btn-sm btn-success-custom rounded-pill px-3 py-0 fs-7">Conectar
                                                no LinkedIn</a></span>
                                    </div>
                                    <div class="mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-graduation-cap text-success-custom"></i>
                                        <span>Instituto Superior de Engenharia do Porto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="servicos" class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <div class="text-center max-width-600 mx-auto mb-5">
                <h2 class="fw-bold text-dark mb-3">Soluções para Gestão Hospitalar Inteligente</h2>
                <p class="text-muted lh-base">A <strong><?php echo APP_NAME; ?></strong> disponibiliza uma plataforma integrada que
                    otimiza a gestão, rastreabilidade e controlo de equipamentos médicos, promovendo maior eficiência e
                    segurança no ambiente hospitalar.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 bg-light p-4 rounded-4 shadow-sm card-hover-effect">
                        <div class="icon-circle-main bg-success-light text-success-custom mx-auto mb-3">
                            <i class="fa-solid fa-stethoscope"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Gestão de Equipamentos</h3>
                        <p class="card-text text-muted small flex-grow-1 lh-base">Consulta e gestão do inventário
                            clínico, com monitorização de criticidade, localização e histórico de manutenção preventiva.
                        </p>
                        <a href="equipamentos.php"
                            class="btn btn-outline-success-custom rounded-pill btn-sm fw-bold mt-3 px-4">Aceder ao
                            Módulo</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 bg-light p-4 rounded-4 shadow-sm card-hover-effect">
                        <div class="icon-circle-main bg-success-light text-success-custom mx-auto mb-3">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Rastreabilidade de Localizações</h3>
                        <p class="card-text text-muted small flex-grow-1 lh-base">Visualização da distribuição dos
                            ativos por serviços hospitalares, garantindo resposta rápida e maior eficiência operacional.
                        </p>
                        <a href="localizacoes.php"
                            class="btn btn-outline-success-custom rounded-pill btn-sm fw-bold mt-3 px-4">Aceder ao
                            Módulo</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 bg-light p-4 rounded-4 shadow-sm card-hover-effect">
                        <div class="icon-circle-main bg-success-light text-success-custom mx-auto mb-3">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Controlo de Fornecedores</h3>
                        <p class="card-text text-muted small flex-grow-1 lh-base">Centralização de contactos, contratos
                            de manutenção e garantias equipamentos médicos essenciais.</p>
                        <a href="fornecedores.php"
                            class="btn btn-outline-success-custom rounded-pill btn-sm fw-bold mt-3 px-4">Aceder ao
                            Módulo</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="contactos" class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <h2 class="h3 fw-bold text-dark text-center mb-4">Contactos</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <address class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border border-smooth m-0">
                        <div class="icon-circle-main bg-success-light text-success-custom">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="d-block small text-muted fw-semibold text-uppercase">Telefone</span>
                            <a href="tel:+35196XXXXXXX" class="text-decoration-none text-dark fw-bold">96X XXX XXX</a>
                        </div>
                    </address>
                </div>
                <div class="col-md-5">
                    <address class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border border-smooth m-0">
                        <div class="icon-circle-main bg-success-light text-success-custom">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span class="d-block small text-muted fw-semibold text-uppercase">E-mail</span>
                            <a href="mailto:contacto@lusohealth.pt"
                                class="text-decoration-none text-dark fw-bold">contacto@lusohealth.pt</a>
                        </div>
                    </address>
                </div>
            </div>
        </section>

        <section id="formulario" class="bg-white p-5 rounded-4 shadow-sm mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold text-dark mb-3">Fale Connosco</h2>
                    <p class="text-muted mb-4 max-width-600 mx-auto lh-base">
                        Simplifique a gestão de inventário e a rastreabilidade de dispositivos médicos.
                        Desenvolvido para responder às reais exigências da Engenharia Biomédica, o
                        <?php echo APP_NAME; ?> é o parceiro ideal para monitorizar a criticidade e conformidade das
                        suas tecnologias de saúde.
                    </p>

                    <form class="text-start mx-auto style-form-width" action="#" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-semibold text-muted small">Nome</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control rounded-3 border-smooth focus-custom" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-muted small">E-mail</label>
                            <input type="email" id="email" name="email"
                                class="form-control rounded-3 border-smooth focus-custom" required>
                        </div>

                        <div class="mb-4">
                            <label for="mensagem" class="form-label fw-semibold text-muted small">Mensagem</label>
                            <textarea id="mensagem" name="mensagem"
                                class="form-control rounded-3 border-smooth focus-custom" rows="4" required></textarea>
                        </div>

                        <button type="submit"
                            class="btn btn-success-custom w-100 fw-bold py-2.5 rounded-pill shadow-sm">
                            Enviar Mensagem <i class="fa-solid fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>

    </main>

    <!-- Rodapé -->
    <footer class="py-4 mt-5 border-top border-light-subtle">
        <div class="container text-center">
            <p class="small text-muted mb-0"> <?php echo APP_COPYRIGHT; ?>
                 <span class="fw-semibold text-success-custom"><?php echo APP_NAME; ?> Portugal</span>.
            </p>
        </div>
    </footer>

<?php include '../assets/includes/footer.php'; ?>