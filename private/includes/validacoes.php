<?php

// ------------------------------ DOCUMENTAÇÃO ------------------------------

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

// ------------------------------ FORNECEDORES ------------------------------

function validar_nome_fornecedor(string $nome): array
{
    $erros = [];
    $nome_limpo = trim($nome);
    if ($nome_limpo === '') {
        $erros[] = "O campo Nome / Razão Social é obrigatório.";
    } elseif (mb_strlen($nome_limpo) > 150) {
        $erros[] = "O campo Nome / Razão Social não pode exceder os 150 caracteres.";
    }
    return $erros;
}

function validar_nif(string $nif): array
{
    $erros = [];
    $nif_limpo = trim($nif);
    if ($nif_limpo === '') {
        $erros[] = "O campo NIF é obrigatório.";
    } elseif (!preg_match('/^[0-9]{9}$/', $nif_limpo)) {
        $erros[] = "O NIF deve conter exatamente 9 dígitos numéricos.";
    }
    return $erros;
}

function validar_tipo_fornecedor(string $tipo): array
{
    $erros = [];
    if (!in_array($tipo, ['Fabricante', 'Distribuidor', 'Assistência Técnica', 'Consumíveis'], true)) {
        $erros[] = "Selecione um Tipo de Fornecedor válido.";
    }
    return $erros;
}

function validar_telefone_fornecedor(string $telefone): array
{
    $erros = [];
    $tel_limpo = trim($telefone);
    if ($tel_limpo !== '' && mb_strlen($tel_limpo) > 30) {
        $erros[] = "O Telefone Geral da Empresa não pode exceder os 30 caracteres.";
    }
    return $erros;
}

function validar_email_fornecedor(string $email): array
{
    $erros = [];
    $email_limpo = trim($email);
    if ($email_limpo !== '') {
        if (!filter_var($email_limpo, FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O E-mail de Assistência Oficial inserido não é válido.";
        } elseif (mb_strlen($email_limpo) > 100) {
            $erros[] = "O E-mail de Assistência Oficial não pode exceder os 100 caracteres.";
        }
    }
    return $erros;
}

function validar_website_fornecedor(string $website): array
{
    $erros = [];
    $web_limpo = trim($website);
    if ($web_limpo !== '') {
        if (!filter_var($web_limpo, FILTER_VALIDATE_URL)) {
            $erros[] = "O endereço do Website Oficial não é válido (ex: https://www.empresa.com).";
        } elseif (mb_strlen($web_limpo) > 150) {
            $erros[] = "O Website Oficial não pode exceder os 150 caracteres.";
        }
    }
    return $erros;
}

function validar_morada_fornecedor(string $morada): array
{
    $erros = [];
    $morada_limpa = trim($morada);
    if ($morada_limpa !== '' && mb_strlen($morada_limpa) > 200) {
        $erros[] = "O Endereço da Sede Comercial não pode exceder os 200 caracteres.";
    }
    return $erros;
}

function validar_tecnico_nome(string $tecnico_nome): array
{
    $erros = [];
    $nome_limpo = trim($tecnico_nome);
    if ($nome_limpo !== '' && mb_strlen($nome_limpo) > 100) {
        $erros[] = "O nome do Gestor de Conta / Técnico Responsável não pode exceder os 100 caracteres.";
    }
    return $erros;
}

function validar_tecnico_telefone(string $tecnico_telefone): array
{
    $erros = [];
    $tel_limpo = trim($tecnico_telefone);
    if ($tel_limpo !== '' && mb_strlen($tel_limpo) > 30) {
        $erros[] = "A Linha Direta do Técnico não pode exceder os 30 caracteres.";
    }
    return $erros;
}

// ------------------------------ GARANTIAS ------------------------------

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
