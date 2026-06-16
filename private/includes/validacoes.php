<?php

// ------------------------------ EQUIPAMENTOS ------------------------------

function validar_designacao(string $designacao): array
{
    $erros = [];
    if (trim($designacao) === '') {
        $erros[] = "O Nome do Equipamento é obrigatório.";
    }
    return $erros;
}
 
function validar_codigo_inventario(string $codigo_inventario): array
{
    $erros = [];
    if (trim($codigo_inventario) === '') {
        $erros[] = "O Código Interno é obrigatório.";
    } elseif (!preg_match('/^[A-Z0-9\-_]+$/', $codigo_inventario)) {
        $erros[] = "O Código Interno só pode conter letras, números, hífens e underscores.";
    }
    return $erros;
}
 
function validar_marca(string $marca): array
{
    $erros = [];
    if (trim($marca) === '') {
        $erros[] = "A Marca é obrigatória.";
    }
    return $erros;
}
 
function validar_modelo(string $modelo): array
{
    $erros = [];
    if (trim($modelo) === '') {
        $erros[] = "O Modelo é obrigatório.";
    }
    return $erros;
}
 
function validar_num_serie(string $num_serie): array
{
    $erros = [];
    if (trim($num_serie) === '') {
        $erros[] = "O Número de Série é obrigatório.";
    }
    return $erros;
}
 
function validar_fabricante(string $fabricante): array
{
    $erros = [];
    if (trim($fabricante) === '') {
        $erros[] = "O Fabricante é obrigatório.";
    }
    return $erros;
}
 
function validar_ano_fabrico(string $ano_fabrico): array
{
    $erros = [];
    $ano_atual = (int) date('Y');
 
    if (trim($ano_fabrico) === '' || !ctype_digit($ano_fabrico)) {
        $erros[] = "O Ano de Fabrico deve ser um número válido.";
    } elseif ((int) $ano_fabrico < 1980 || (int) $ano_fabrico > $ano_atual + 1) {
        $erros[] = "O Ano de Fabrico deve estar entre 1980 e " . ($ano_atual + 1) . ".";
    }
    return $erros;
}
 
function validar_data_aquisicao(string $data_aquisicao): array
{
    $erros = [];
    $data = DateTime::createFromFormat('Y-m-d', $data_aquisicao);
 
    if (trim($data_aquisicao) === '' || !$data || $data->format('Y-m-d') !== $data_aquisicao) {
        $erros[] = "A Data de Aquisição deve ser uma data válida no formato AAAA-MM-DD.";
    }
    return $erros;
}
 
/**
 * Converte o custo de aquisição de formato PT (ex: "1.250,00 €") para float.
 * Devolve null se o valor estiver vazio ou não puder ser convertido.
 */
function normalizar_custo_aquisicao(string $custo_aquisicao): ?float
{
    $custo = trim($custo_aquisicao);
    if ($custo === '') {
        return null;
    }
 
    $custo = str_replace('€', '', $custo);
    $custo = trim($custo);
    $custo = str_replace('.', '', $custo);   // remove separador de milhares
    $custo = str_replace(',', '.', $custo);  // vírgula decimal -> ponto
 
    return is_numeric($custo) ? (float) $custo : null;
}
 
function validar_custo_aquisicao(string $custo_aquisicao): array
{
    $erros = [];
    if (trim($custo_aquisicao) !== '' && normalizar_custo_aquisicao($custo_aquisicao) === null) {
        $erros[] = "O Custo de Aquisição deve ser um valor numérico válido (ex: 1.250,00 €).";
    }
    return $erros;
}
 
function validar_categoria(string $categoria_id): array
{
    $erros = [];
    if (trim($categoria_id) === '' || !ctype_digit($categoria_id)) {
        $erros[] = "Selecione uma Categoria válida.";
    }
    return $erros;
}
 
function validar_localizacao_id(string $localizacao_id): array
{
    $erros = [];
    if (trim($localizacao_id) === '' || !ctype_digit($localizacao_id)) {
        $erros[] = "Selecione uma Localização válida.";
    }
    return $erros;
}
 
function validar_tipo_entrada(string $tipo_entrada): array
{
    $erros = [];
    if (!in_array($tipo_entrada, ['Compra', 'Doação', 'Aluguer', 'Empréstimo'], true)) {
        $erros[] = "Selecione um Tipo de Entrada válido.";
    }
    return $erros;
}
 
function validar_estado(string $estado): array
{
    $erros = [];
    if (!in_array($estado, ['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'], true)) {
        $erros[] = "Selecione um Estado válido.";
    }
    return $erros;
}
 
function validar_criticidade(string $criticidade): array
{
    $erros = [];
    if (!in_array($criticidade, ['Baixa', 'Médio', 'Alta', 'Suporte de vida'], true)) {
        $erros[] = "Selecione um nível de Criticidade válido.";
    }
    return $erros;
}

// ------------------------------ LOCALIZACOES ------------------------------

function validar_codigo(string $codigo): array
{
    $erros = [];
    if (empty($codigo)) {
        $erros[] = "O campo ID da Localização é obrigatório.";
    }
    return $erros;
}

function validar_nome(string $nome): array
{
    $erros = [];
    if (empty(trim($nome))) {
        $erros[] = "O campo Nome é obrigatório.";
    } elseif (preg_match('/\d/', $nome)) {
        $erros[] = "O campo Nome não pode conter números.";
    }
    return $erros;
}

function validar_edificio(string $edificio): array
{
    $erros = [];
    if (empty($edificio)) {
        $erros[] = "O campo Edifício / Bloco é obrigatório.";
    }
    return $erros;
}

function validar_piso(string $piso): array
{
    $erros = [];
    // Verifica se está mesmo vazio (string vazia)
    if (trim($piso) === '') {
        $erros[] = "O campo Piso é obrigatório.";
    } 
    // Garante que o que foi digitado é um número inteiro válido
    elseif (!filter_var($piso, FILTER_VALIDATE_INT) && $piso !== '0') {
        $erros[] = "O campo Piso deve ser um número inteiro válido.";
    }
    return $erros;
}

function validar_responsavel(string $responsavel): array
{
    $erros = [];
    if (empty($responsavel)) {
        $erros[] = "O campo Responsável é obrigatório.";
    } elseif (preg_match('/\d/', $responsavel)) {
        $erros[] = "O campo Responsável não pode conter números.";
    }
    return $erros;
}
