// Equipamentos - Número de Série

const inputSerie = document.getElementById("num_serie");

// Verifica se o input existe na página antes de aplicar lógica
if (inputSerie) {

    const PREFIXO = "EQ-"; // Prefixo fixo obrigatório para todas as séries

    function formatar(valor) {

        // Remove o prefixo e garante que só se trabalha com o conteúdo útil
        let miolo; // let - guarda valores que podem mudar depois ao longo do código
        if (valor.startsWith(PREFIXO)) {
            miolo = valor.slice(PREFIXO.length);
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
        let resultado = PREFIXO + numeros;

        // Adiciona hífen apenas quando os 5 números estão completos
        if (numeros.length === 5) {
            resultado += "-";
        }

        // Adiciona as letras finais (até 3)
        resultado += letras;

        return resultado;
    }

    // Atualiza o valor em tempo real enquanto o utilizador escreve
    inputSerie.addEventListener("input", function () {
    // “Sempre que o utilizador alterar o conteúdo do campo inputSerie, executa esta função.”
        this.value = formatar(this.value);

        // Mantém o cursor no fim para evitar comportamento estranho
        this.setSelectionRange(this.value.length, this.value.length);
    });

    // Garante que o prefixo existe ao entrar no campo
    inputSerie.addEventListener("focus", function () {
        if (!this.value) this.value = PREFIXO;

        setTimeout(() => {
            this.setSelectionRange(this.value.length, this.value.length); // coloca o curso no fim do texto ...
        }, 0); // ... de forma instantânea
    });

    // Impede apagar o prefixo obrigatório
    inputSerie.addEventListener("keydown", function (e) {
    // keydown - este evento acontece quando uma tecla é pressionada (antes de o browser alterar o input)
    // function(e) - o e significa event (contém informação sobre a tecla pressionada)
        if (this.selectionStart <= PREFIXO.length && // "O cursor está dentro ou antes do prefixo?"
        // this.selectionStart - indica a posição do cursor no input
            (e.key === "Backspace" || e.key === "Delete")) { // "E a tecla pressionada foi backspace OU delete?"
            e.preventDefault(); // "Cancela a ação normal do browser"
        }
    });
}

// Equipamentos - Ano de Fabrico

// “Executa este código só depois de a página HTML estar totalmente carregada.”
document.addEventListener("DOMContentLoaded", function () {

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
});