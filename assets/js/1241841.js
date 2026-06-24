// Garante que todo o código DOM só corre quando o HTML estiver totalmente carregado
document.addEventListener("DOMContentLoaded", function () {

    /* ============================================================ 
       1. MÓDULO: EQUIPAMENTOS (CÓDIGO DE INVENTÁRIO)
       ============================================================ */
    const inputCodigo = document.getElementById("codigo_inventario");

    if (inputCodigo) {
        const PREFIXO_COD = "EQ-"; // Prefixo obrigatório

        /**
         * Formata o código do inventário no padrão: EQ-XXXXX-YYY (5 números, 3 letras)
         * @param {string} valor - O texto bruto digitado pelo utilizador
         * @returns {string} Texto formatado
         */
        function formatarCodigo(valor) {
            let miolo;

            // Remove o prefixo para tratar apenas os dados úteis
            if (valor.startsWith(PREFIXO_COD)) {
                miolo = valor.slice(PREFIXO_COD.length);
            } else {
                miolo = valor;
            }

            // Normaliza o texto para maiúsculas e remove tudo o que não for alfanumérico
            miolo = miolo.toUpperCase().replace(/[^A-Z0-9]/g, "");

            let numeros = "";
            let letras = "";

            // Separa os primeiros 5 caracteres se forem números, e os 3 seguintes se forem letras
            for (let i = 0; i < miolo.length; i++) {
                const char = miolo[i];

                if (numeros.length < 5) {
                    if (/[0-9]/.test(char)) {
                        numeros += char;
                    }
                } else if (letras.length < 3) {
                    if (/[A-Z]/.test(char)) {
                        letras += char;
                    }
                }
            }

            // Constrói o resultado final
            let resultado = PREFIXO_COD + numeros;
            if (numeros.length === 5) {
                resultado += "-"; // Adiciona o hífen separador
            }
            resultado += letras;

            return resultado;
        }

        // Listener: Formata em tempo real enquanto o utilizador digita
        inputCodigo.addEventListener("input", function () {
            this.value = formatarCodigo(this.value);
            // Evita que o cursor salte de forma intermitente
            this.setSelectionRange(this.value.length, this.value.length);
        });

        // Listener: Força a aparição do prefixo ao clicar no campo
        inputCodigo.addEventListener("focus", function () {
            if (!this.value) this.value = PREFIXO_COD;
            setTimeout(() => {
                this.setSelectionRange(this.value.length, this.value.length);
            }, 0);
        });

        // Listener: Impede o utilizador de apagar o prefixo com Backspace ou Delete
        inputCodigo.addEventListener("keydown", function (e) {
            if (this.selectionStart <= PREFIXO_COD.length && (e.key === "Backspace" || e.key === "Delete")) {
                e.preventDefault();
            }
        });
    }

    /* ============================================================ 
       2. MÓDULO: EQUIPAMENTOS (ANO DE FABRICO)
       ============================================================ */
    const inputAno = document.getElementById("ano_fabrico");

    if (inputAno) {
        const anoAtual = new Date().getFullYear();
        const anoMinimo = anoAtual - 40; // Limite de 40 anos de vida útil ativa

        // Configura atributos nativos do HTML5
        inputAno.min = anoMinimo;
        inputAno.max = anoAtual;
        inputAno.placeholder = "Ex: " + anoAtual;

        // Validação customizada em tempo real
        inputAno.addEventListener("input", function () {
            const anoInserido = parseInt(this.value, 10);

            if (isNaN(anoInserido)) {
                this.setCustomValidity("");
            } else if (anoInserido < anoMinimo) {
                this.setCustomValidity("O ano de fabrico deve ser igual ou superior a " + anoMinimo + " para registo de equipamentos ativos.");
            } else if (anoInserido > anoAtual) {
                this.setCustomValidity("O ano de fabrico não pode ser superior ao ano atual (" + anoAtual + ").");
            } else {
                this.setCustomValidity(""); // Campo válido, limpa erros
            }
        });
    }

    /* ============================================================ 
       3. MÓDULO: AJAX — GUARDAR NOVA CATEGORIA (MODAL)
       ============================================================ */
    const formCategoria = document.getElementById("formNovaCategoria");

    if (formCategoria) {
        formCategoria.addEventListener("submit", function (e) {
            e.preventDefault();

            const nomeCategoria = document.getElementById("nome_categoria").value.trim();
            const selectCategoria = document.getElementById("categoria_id");
            const btnGuardar = document.getElementById("btnGuardarCategoria");

            if (nomeCategoria === "") return;

            // Bloqueia o botão para evitar cliques duplos
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = "A guardar...";

            const dados = new FormData();
            dados.append("nome_categoria", nomeCategoria);

            // Submissão assíncrona via FETCH API
            fetch('/lusohealth/assets/includes/guardar_categoria_ajax.php', {
                method: "POST",
                body: dados
            })
                .then(response => response.text()) // Captura como texto puro para mitigar notices/errors do PHP
                .then(texto => {
                    console.log("--- RESPOSTA BRUTA DO SERVIDOR ---", texto);

                    try {
                        const data = JSON.parse(texto);

                        if (data.sucesso) {
                            // Adiciona dinamicamente a nova opção ao Select e seleciona-a
                            const novaOpcao = new Option(data.nome, data.id, true, true);
                            selectCategoria.add(novaOpcao);
                            document.getElementById("nome_categoria").value = "";

                            // Fecha o Modal do Bootstrap de forma limpa
                            const modalElement = document.getElementById("modalNovaCategoria");
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        } else {
                            alert("Erro no PHP: " + data.erro);
                        }
                    } catch (erroJson) {
                        alert("--- RESPOSTA DO PHP (Erro JSON) ---\n" + texto);
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição FETCH:", error);
                })
                .finally(() => {
                    // Restaura o estado do botão
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = "Guardar";
                });
        });
    }

    /* ============================================================ 
       4. MÓDULO INTERNO: JQUERY — DATATABLES & ALERTAS
       ============================================================ */
    if (typeof $ !== 'undefined') {
        $(document).ready(function () {
            // Desaparecer alertas de sucesso automaticamente após 2 segundos
            const alertaSucesso = $('.alert-success');
            if (alertaSucesso.length) {
                setTimeout(function () {
                    alertaSucesso.fadeOut(500, function () {
                        $(this).remove();
                    });
                }, 2000);
            }

            // Inicialização segura do DataTables em Português
            if ($.fn.DataTable && $('#tabela').length) {
                $('#tabela').DataTable({
                    "pageLength": 10,
                    "pagingType": "full_numbers",
                    "language": {
                        "emptyTable": "Não foi encontrado nenhum registo.",
                        "info": "A apresentar _START_ até _END_ de um total de _TOTAL_ registos",
                        "infoEmpty": "A apresentar 0 até 0 de 0 registos",
                        "infoFiltered": "(a filtrar um total de _MAX_ garantias)",
                        "lengthMenu": "Mostrar _MENU_ registos por página",
                        "loadingRecords": "A carregar...",
                        "processing": "A processar...",
                        "search": "Filtrar:",
                        "zeroRecords": "Não foram encontrados resultados correspondentes",
                        "paginate": {
                            "first": "Primeira",
                            "last": "Última",
                            "next": "Seguinte",
                            "previous": "Anterior"
                        }
                    }
                });
                console.log("DataTables iniciado com sucesso!");
            }
        });
    }

    /* ============================================================ 
   5. MÓDULO: BOTÕES DE PREENCHIMENTO AUTOMÁTICO (TESTES GERIAS)
   ============================================================ */
    // 1. Primeiro procura-se o botão de submissão da página
    const btnSubmitGlobal = document.querySelector('button[type="submit"]');

    // 2. Só cria o botão "Preencher Dados de Teste" se o botão existir E NÃO forem as páginas entre ''
    if (btnSubmitGlobal 
            && !window.location.pathname.includes('login.php') 
            && !window.location.pathname.includes('conteudos.php')
            && !window.location.pathname.includes('editar.php')) {
        const btnAuto = document.createElement('button');
        btnAuto.type = 'button';
        btnAuto.className = 'btn btn-outline-secondary btn-sm rounded-pill mt-2 d-block';
        btnAuto.innerHTML = '<i class="fa-solid fa-magic-wand-sparkles me-1"></i> Preencher Dados de Teste';

        // Insere o botão logo antes do botão de submissão original
        btnSubmitGlobal.parentNode.insertBefore(btnAuto, btnSubmitGlobal);

        btnAuto.addEventListener('click', function () {
            // Helpers rápidos de preenchimento
            const setId = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
            const setIdx = (id, idx) => { const el = document.getElementById(id); if (el && el.options.length > idx) el.selectedIndex = idx; };
            const f = document.querySelector('form');
            const setName = (name, val) => { if (f) { const el = f.querySelector('[name="' + name + '"]'); if (el) el.value = val; } };
            const setNameIdx = (name, idx) => { if (f) { const el = f.querySelector('[name="' + name + '"]'); if (el && el.options.length > idx) el.selectedIndex = idx; } };

            // 5.1 - Teste para Documentos
            setId('nomeDocumento', 'Manual do Utilizador — Teste Automático');
            setId('dataEmissao', '2025-03-15');
            setId('dataValidade', '2027-03-15');
            setId('notas', 'Documento de teste inserido automaticamente.');
            setId('url_externo', 'https://drive.google.com/manual-teste');
            setIdx('tipoDocumento', 1);
            setIdx('equipamentoAlvo', 1);
            setIdx('fornecedorAlvo', 1);

            // 5.2 - Teste para Equipamentos
            setId('designacao', 'Monitor de Sinais Vitais');
            setId('codigo_inventario', 'EQ-99999-XYZ');
            setId('marca', 'Philips');
            setId('modelo', 'IntelliVue MP5');
            setId('num_serie', 'MP5-TESTE-' + Math.floor(Math.random() * 90000 + 10000));
            setId('ano_fabrico', '2022');
            setId('custo_aquisicao', '9800.00');
            setId('observacoes', 'Equipamento de teste — Unidade de Cuidados Intensivos.');

            const dataInput = document.getElementById('data_aquisicao');
            if (dataInput) {
                if (dataInput._flatpickr) dataInput._flatpickr.setDate('2022-06-15');
                else dataInput.value = '2022-06-15';
            }
            ['categoria_id', 'tipo_entrada', 'localizacao_id', 'estado', 'criticidade', 'forn_fabricante'].forEach(id => setIdx(id, 1));

            // 5.3 - Teste para Fornecedores
            setId('nome_fornecedor', 'MedTest Portugal S.A.');
            setId('nif_fornecedor', '500' + Math.floor(Math.random() * 900000 + 100000));
            setId('telefone_fornecedor', '+351 21 000 1234');
            setId('email_fornecedor', 'teste@medtest.pt');
            setId('website_fornecedor', 'https://www.medtest.pt');
            setId('morada_fornecedor', 'Rua da Inovação, 42, 4000-123 Porto');
            setId('tecnico_responsavel', 'Eng. João Moreira');
            setId('telefone_tecnico', '+351 910 123 456');
            setId('observacoes_fornecedor', 'Fornecedor de teste criado automaticamente.');
            setIdx('tipo_fornecedor', 1);

            // 5.4 - Teste para Contratos (via atributos Name)
            setName('num_contrato', 'CTR-TESTE-' + Math.floor(Math.random() * 90000 + 10000));
            setName('data_inicio', '2025-01-01');
            setName('data_fim', '2027-12-31');
            setName('periodicidade', 'Anual');
            setName('observacoes', 'Contrato de teste criado automaticamente.');
            setName('url_externo', 'https://drive.google.com/exemplo-contrato-teste');
            ['equipamento_id', 'fornecedor_id', 'tipo'].forEach(name => setNameIdx(name, 1));

            // 5.5 - Teste para Localizações
            setName('codigo', 'TST-' + Math.floor(Math.random() * 900 + 100));
            setName('nome', 'Serviço de Medicina de Teste');
            setName('edificio', 'Bloco de Testes');
            setName('piso', '2');
            setName('responsavel', 'Enf. Inês Moreira');
            setName('observacoes', 'Localização criada automaticamente para testes.');
        });
    }

    /* ============================================================ 
       6. MÓDULO: LOGIN (PREENCHIMENTO RÁPIDO)
       ============================================================ */
    const btnAdm = document.querySelector("#preencher_adm");
    const btnUtilizador = document.querySelector("#preencher_utilizador");

    if (btnAdm) {
        btnAdm.addEventListener('click', () => {
            const formulario = document.forms['formulario_login'];
            if (formulario) {
                formulario['username'].value = "admin@isep.pt";
                formulario['password'].value = "123456";
            }
        });
    }

    if (btnUtilizador) {
        btnUtilizador.addEventListener('click', () => {
            const formulario = document.forms['formulario_login'];
            if (formulario) {
                formulario['username'].value = "utilizador@isep.pt";
                formulario['password'].value = "123456";
            }
        });
    }
});

    /* ============================================================ 
       7. COMPONENTES EXTERNOS E FUNÇÕES GLOBAIS (FORA DO DOM READY)
       ============================================================ */

    // Inicialização Segura do Seletor de Datas Flatpickr
    if (document.getElementById("data_aquisicao")) {
        flatpickr("#data_aquisicao", {
            dateFormat: "Y-m-d"
        });
    }

    /**
     * Remove visualmente o ficheiro atual anexado a um registo (Contratos)
     */
    function removerFicheiroAtual() {
        const inputRemover = document.getElementById('remover_ficheiro');
        const wrapper = document.getElementById('ficheiro_atual_wrapper');
        const inputFicheiro = document.getElementById('ficheiro_contrato');

        if (inputRemover) inputRemover.value = '1';
        if (wrapper) wrapper.classList.add('d-none');
        if (inputFicheiro) inputFicheiro.value = ''; // Limpa seleções pendentes
    }

    /**
     * Cria instâncias de gráficos através do Chart.js de forma parametrizada
     */
    function criarGrafico(canvasId, dados, tipo, cor) {
        const ctx = document.getElementById(canvasId);
        if (!ctx || !dados || dados.length === 0) return;

        new Chart(ctx, {
            type: tipo,
            data: {
                labels: dados.map(d => d.label),
                datasets: [{
                    label: 'Nº de equipamentos',
                    data: dados.map(d => d.total),
                    backgroundColor: cor
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: tipo === 'pie' // Só exibe legendas laterais se for gráfico circular
                    }
                }
            }
        });
    }

    // Inicialização condicional dos gráficos gerados pelo PHP backend
    try {
        if (typeof dadosServicos !== 'undefined') criarGrafico('graficoServicos', dadosServicos, 'bar', '#198754');
        if (typeof dadosEdificios !== 'undefined') criarGrafico('graficoEdificios', dadosEdificios, 'pie', ['#198754', '#0d6efd', '#ffc107', '#dc3545', '#6c757d', '#20c997']);
        if (typeof dadosSuporteVida !== 'undefined') criarGrafico('graficoSuporteVida', dadosSuporteVida, 'bar', '#dc3545');
    } catch (e) {
        console.log("Gráficos ignorados nesta página ou variáveis em falta.");
    }