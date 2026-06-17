<?php

// ------------------------------ DOCUMENTAÇÃO ------------------------------

function validar_documento_equipamento_id(string $equipamento_id): array
{
    $erros = [];
    if (trim($equipamento_id) === '' || !ctype_digit($equipamento_id)) {
        $erros[] = "O documento deve estar associado a um Equipamento válido.";
    }
    return $erros;
}

function validar_documento_fornecedor_id(string $fornecedor_id): array
{
    $erros = [];
    if (trim($fornecedor_id) !== '' && !ctype_digit($fornecedor_id)) {
        $erros[] = "O Fornecedor associado ao documento é inválido.";
    }
    return $erros;
}

function validar_tipo_documento(string $tipo): array
{
    $erros = [];
    if (trim($tipo) === '') {
        $erros[] = "O Tipo de Documento é obrigatório.";
    } elseif (mb_strlen(trim($tipo)) > 50) {
        $erros[] = "O Tipo de Documento não pode exceder os 50 caracteres.";
    }
    return $erros;
}

function validar_nome_documento(string $nome_documento): array
{
    $erros = [];
    if (trim($nome_documento) === '') {
        $erros[] = "O Nome do Documento é obrigatório.";
    } elseif (mb_strlen(trim($nome_documento)) > 255) {
        $erros[] = "O Nome do Documento não pode exceder os 255 caracteres.";
    }
    return $erros;
}

function validar_ficheiro_documento(string $caminho): array
{
    $erros = [];
    if (trim($caminho) === '') {
        $erros[] = "O ficheiro do documento ou o seu caminho é obrigatório.";
    } elseif (mb_strlen(trim($caminho)) > 255) {
        $erros[] = "O caminho do ficheiro não pode exceder os 255 caracteres.";
    }
    return $erros;
}

function validar_datas_documento(string $data_doc, string $data_val): array
{
    $erros = [];
    if (trim($data_doc) !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $data_doc);
        if (!$d || $d->format('Y-m-d') !== $data_doc) {
            $erros[] = "A Data do Documento introduzida é inválida.";
        }
    }
    if (trim($data_val) !== '') {
        $v = DateTime::createFromFormat('Y-m-d', $data_val);
        if (!$v || $v->format('Y-m-d') !== $data_val) {
            $erros[] = "A Data de Validade introduzida é inválida.";
        }
    }
    return $erros;
}


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

function validar_arquivado_equipamento(string $arquivado): array
{
    $erros = [];
    if (!in_array($arquivado, ['0', '1'], true)) {
        $erros[] = "O estado de arquivo do equipamento é inválido.";
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
function validar_num_contrato(string $num_contrato): array
{
    $erros = [];
    if (trim($num_contrato) === '') {
        $erros[] = "O Número de Contrato é obrigatório.";
    } elseif (mb_strlen(trim($num_contrato)) > 50) {
        $erros[] = "O Número de Contrato não pode exceder os 50 caracteres.";
    }
    return $erros;
}

function validar_garantia_equipamento_id(string $equipamento_id): array
{
    $erros = [];
    if (trim($equipamento_id) === '' || !ctype_digit($equipamento_id)) {
        $erros[] = "Selecione um Equipamento válido para associar à garantia.";
    }
    return $erros;
}

function validar_garantia_fornecedor_id(string $fornecedor_id): array
{
    $erros = [];
    // Opcional na base de dados
    if (trim($fornecedor_id) !== '' && !ctype_digit($fornecedor_id)) {
        $erros[] = "O Fornecedor selecionado é inválido.";
    }
    return $erros;
}

function validar_tipo_garantia(string $tipo): array
{
    $erros = [];
    if (!in_array($tipo, ['Garantia de Fábrica', 'Contrato de Manutenção', 'Outro'], true)) {
        $erros[] = "Selecione um Tipo de Garantia válido.";
    }
    return $erros;
}

function validar_tem_contrato_manutencao(string $tem_contrato): array
{
    $erros = [];
    if (!in_array($tem_contrato, ['0', '1'], true)) {
        $erros[] = "A indicação de Contrato de Manutenção é inválida.";
    }
    return $erros;
}

function validar_datas_garantia(string $data_inicio, string $data_fim): array
{
    $erros = [];
    $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio);
    $fim = DateTime::createFromFormat('Y-m-d', $data_fim);

    if (trim($data_inicio) === '' || !$inicio || $inicio->format('Y-m-d') !== $data_inicio) {
        $erros[] = "A Data de Início deve ser uma data válida (AAAA-MM-DD).";
    }
    if (trim($data_fim) === '' || !$fim || $fim->format('Y-m-d') !== $data_fim) {
        $erros[] = "A Data de Fim deve ser uma data válida (AAAA-MM-DD).";
    }

    if ($inicio && $fim && $fim <= $inicio) {
        $erros[] = "A Data de Fim do contrato deve ser estritamente posterior à Data de Início.";
    }
    return $erros;
}

function validar_periodicidade(string $periodicidade): array
{
    $erros = [];
    if (trim($periodicidade) !== '' && mb_strlen(trim($periodicidade)) > 50) {
        $erros[] = "O campo Periodicidade não pode exceder os 50 caracteres.";
    }
    return $erros;
}

function validar_caminhos_garantia(string $ficheiro_path, string $url_externo): array
{
    $erros = [];
    if (trim($ficheiro_path) !== '' && mb_strlen(trim($ficheiro_path)) > 255) {
        $erros[] = "O caminho do ficheiro local excede o limite de 255 caracteres.";
    }
    if (trim($url_externo) !== '') {
        if (!filter_var(trim($url_externo), FILTER_VALIDATE_URL)) {
            $url_externo = "O Link para a Cloud inserido não é um URL válido.";
        } elseif (mb_strlen(trim($url_externo)) > 255) {
            $erros[] = "O Link para a Cloud não pode exceder os 255 caracteres.";
        }
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

function validar_arquivado_localizacao(string $arquivado): array
{
    $erros = [];
    if (!in_array($arquivado, ['0', '1'], true)) {
        $erros[] = "O estado de arquivo selecionado é inválido.";
    }
    return $erros;
}
