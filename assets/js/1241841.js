// Garante que o código só corre quando o HTML estiver pronto
document.addEventListener("DOMContentLoaded", function () {

    // Equipamentos - Código Interno

    const inputCodigo = document.getElementById("codigo_inventario");

    // Verifica se o input existe na página antes de aplicar lógica
    if (inputCodigo) {

        const PREFIXO_COD = "EQ-"; // Prefixo fixo obrigatório para todas as séries

        function formatarCodigo(valor) {

            // Remove o prefixo e garante que só se trabalha com o conteúdo útil
            let miolo; // let - guarda valores que podem mudar depois ao longo do código
            if (valor.startsWith(PREFIXO_COD)) {
                miolo = valor.slice(PREFIXO_COD.length);
            }
            else {
                miolo = valor;
            }

            // Normaliza o texto: maiúsculas e remove caracteres inválidos
            miolo = miolo
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, ""); // substituiu qualquer coisa que não seja de A a Z ou de 0 a 9 por ""

            let numeros = "";
            let letras = "";

            // Percorre o valor e separa números e letras de forma sequencial
            for (let i = 0; i < miolo.length; i++) {
                const char = miolo[i];

                // Primeiro bloco: aceita apenas até 5 números
                if (numeros.length < 5) {
                    if (/[0-9]/.test(char)) { // .test() - função que testa a condição
                        numeros += char;
                    }
                }

                // Segundo bloco: após 5 números, aceita até 3 letras
                else if (letras.length < 3) {
                    if (/[A-Z]/.test(char)) {
                        letras += char;
                    }
                }
            }

            // Construção final da string formatada
            let resultado = PREFIXO_COD + numeros;

            // Adiciona hífen apenas quando os 5 números estão completos
            if (numeros.length === 5) {
                resultado += "-";
            }

            // Adiciona as letras finais (até 3)
            resultado += letras;

            return resultado;
        }

        // Atualiza o valor em tempo real enquanto o utilizador escreve
        inputCodigo.addEventListener("input", function () {
            // “Sempre que o utilizador alterar o conteúdo do campo inputCodigo, executa esta função.”
            this.value = formatarCodigo(this.value);

            // Mantém o cursor no fim para evitar comportamento estranho
            this.setSelectionRange(this.value.length, this.value.length);
        });

        // Garante que o prefixo existe ao entrar no campo
        inputCodigo.addEventListener("focus", function () {
            if (!this.value) this.value = PREFIXO_COD;

            setTimeout(() => {
                this.setSelectionRange(this.value.length, this.value.length); // coloca o curso no fim do texto ...
            }, 0); // ... de forma instantânea
        });

        // Impede apagar o prefixo obrigatório
        inputCodigo.addEventListener("keydown", function (e) {
            // keydown - este evento acontece quando uma tecla é pressionada (antes de o browser alterar o input)
            // function(e) - o e significa event (contém informação sobre a tecla pressionada)
            if (this.selectionStart <= PREFIXO_COD.length && // "O cursor está dentro ou antes do prefixo?"
                // this.selectionStart - indica a posição do cursor no input
                (e.key === "Backspace" || e.key === "Delete")) { // "E a tecla pressionada foi backspace OU delete?"
                e.preventDefault(); // "Cancela a ação normal do browser"
            }
        });
    }


    // Equipamentos - Ano de Fabrico

    // Procura o campo do ano de fabrico na página
    const inputAno = document.getElementById("ano_fabrico");

    // Verifica se o campo existe antes de aplicar a lógica
    if (inputAno) {

        // Obtém o ano atual do sistema
        const anoAtual = new Date().getFullYear();

        // Define o limite mínimo (40 anos anteriores)
        const anoMinimo = anoAtual - 40;

        // Configura os limites e o exemplo do campo
        inputAno.min = anoMinimo;
        inputAno.max = anoAtual;
        inputAno.placeholder = "Ex: " + anoAtual;

        // “Sempre que o utilizador escrever ou alterar o valor do campo inputAno, executa esta função.”
        inputAno.addEventListener("input", function () {

            // Converte o valor inserido para número inteiro
            const anoInserido = parseInt(this.value, 10);
            // parseInt - significa “parse integer” → converter para número inteiro

            // Remove erros se o campo estiver vazio ou inválido
            if (isNaN(anoInserido)) {
                // isNaN - significa “is Not a Number”
                this.setCustomValidity("");
            }

            // Valida anos inferiores ao mínimo permitido
            else if (anoInserido < anoMinimo) {
                this.setCustomValidity(
                    "O ano de fabrico deve ser igual ou superior a " +
                    anoMinimo + " para registo de equipamentos ativos."
                );
            }

            // Impede anos superiores ao ano atual
            else if (anoInserido > anoAtual) {
                this.setCustomValidity(
                    "O ano de fabrico não pode ser superior ao ano atual (" +
                    anoAtual + ")."
                );
            }

            // Remove mensagens de erro se o valor for válido
            else {
                this.setCustomValidity("");
            }
        });
    }

    // Equipamentos - Custo de Aquisição

    const formCategoria = document.getElementById("formNovaCategoria");

    if (formCategoria) {
        formCategoria.addEventListener("submit", function (e) {
            e.preventDefault();

            const nomeCategoria = document.getElementById("nome_categoria").value.trim();
            const selectCategoria = document.getElementById("categoria_id");
            const btnGuardar = document.getElementById("btnGuardarCategoria");

            if (nomeCategoria === "") return;

            btnGuardar.disabled = true;
            btnGuardar.innerHTML = "A guardar...";

            const dados = new FormData();
            dados.append("nome_categoria", nomeCategoria);

            // Rota absoluta que descobrimos que funciona
            fetch('/lusohealth/assets/includes/guardar_categoria_ajax.php', {
                method: "POST",
                body: dados
            })
                .then(response => {
                    // Lemos a resposta como texto bruto primeiro para apanhar erros do PHP
                    return response.text();
                })
                .then(texto => {
                    console.log("--- RESPOSTA BRUTA DO SERVIDOR ---");
                    console.log(texto);
                    console.log("----------------------------------");

                    try {
                        // Tenta converter o texto para JSON
                        const data = JSON.parse(texto);

                        if (data.sucesso) {
                            const novaOpcao = new Option(data.nome, data.id, true, true);
                            selectCategoria.add(novaOpcao);
                            document.getElementById("nome_categoria").value = "";

                            const modalElement = document.getElementById("modalNovaCategoria");
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            modal.hide();
                        } else {
                            alert("Erro no PHP: " + data.erro);
                        }
                    } catch (erroJson) {
                        // MUDANÇA AQUI: Vamos ver o texto bruto num alert para não haver dúvidas!
                        alert("--- RESPOSTA DO PHP ---\n" + texto + "\n------------------------");
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição FETCH:", error);
                })
                .finally(() => {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = "Guardar";
                });
        });
    }

    // Filtros

    // tradução para português
    $(document).ready(function () {

        // Procura na página se existe algum alerta de sucesso ativo
        const alertaSucesso = $('.alert-success');

        console.log("DataTables a iniciar..."); // Isto aparece na consola do navegador

        if (alertaSucesso.length) {
            // Executa uma contagem decrescente de 3000ms (3 segundos)
            setTimeout(function () {
                // Efeito visual suave de desaparecimento (fade) ao longo de 500ms
                alertaSucesso.fadeOut(500, function () {
                    // Remove completamente o elemento do HTML após desaparecer
                    $(this).remove();
                });
            }, 2000); // 3000 milissegundos = 3 segundos
        }

        // datatable
        if ($.fn.DataTable) {
            $('#tabela').DataTable({
                "pageLength": 10,
                "pagingType": "full_numbers",

                "language": {
                    "decimal": "",
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
        } else {
            console.error("Erro: O plugin DataTables não foi carregado no footer.php!");
        }
    })

})


// Inicializar o seletor de data
flatpickr("#data_aquisicao", {
    dateFormat: "Y-m-d"
});

/* REMOVER FICHEIRO */
function removerFicheiroAtual() {
    document.getElementById('remover_ficheiro').value = '1';
    document.getElementById('ficheiro_atual_wrapper').classList.add('d-none');
    document.getElementById('ficheiro_contrato').value = ''; // limpa qualquer seleção pendente
}
