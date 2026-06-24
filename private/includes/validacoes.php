<?php

// ------------------------------ DOCUMENTAÇÃO ------------------------------

function validar_nome_documento(string $nome_documento): array
{
    $erros = [];
    $nome_documento = trim($nome_documento);

    if ($nome_documento === '') {
        $erros[] = "O Nome do Documento é obrigatório.";
    } elseif (mb_strlen($nome_documento) > 255) {
        $erros[] = "O Nome do Documento não pode exceder os 255 caracteres.";
    }

    return $erros;
}

function validar_tipo_documento(string $tipo): array
{
    $erros = [];
    $tipos_validos = ['Manual', 'Calibracao', 'Conformidade', 'Relatorio'];

    if (trim($tipo) === '') {
        $erros[] = "O Tipo de Documento é obrigatório.";
    } elseif (!in_array($tipo, $tipos_validos, true)) {
        $erros[] = "O Tipo de Documento selecionado é inválido.";
    }

    return $erros;
}

function validar_documento_equipamento_id(string $equipamento_id): array
{
    $erros = [];
    $equipamento_id = trim($equipamento_id);

    if ($equipamento_id !== '' && !ctype_digit($equipamento_id)) {
        $erros[] = "O Equipamento associado ao documento é inválido.";
    }

    return $erros;
}

function validar_documento_fornecedor_id(string $fornecedor_id): array
{
    $erros = [];
    $fornecedor_id = trim($fornecedor_id);

    if ($fornecedor_id !== '' && !ctype_digit($fornecedor_id)) {
        $erros[] = "O Fornecedor associado ao documento é inválido.";
    }

    return $erros;
}

function validar_associacao_documento(string $equipamento_id, string $fornecedor_id): array
{
    $erros = [];

    if (trim($equipamento_id) === '' && trim($fornecedor_id) === '') {
        $erros[] = "O documento tem de estar associado a pelo menos um Equipamento ou um Fornecedor.";
    }

    return $erros;
}

function validar_datas_documento(string $data_documento, string $data_validade): array
{
    $erros = [];
    $data_documento = trim($data_documento);
    $data_validade = trim($data_validade);
    $doc = null;

    if ($data_documento === '') {
        $erros[] = "A Data de Emissão do Documento é obrigatória.";
    } else {
        $doc = DateTime::createFromFormat('Y-m-d', $data_documento);
        if (!$doc || $doc->format('Y-m-d') !== $data_documento) {
            $erros[] = "A Data de Emissão introduzida é inválida.";
            $doc = null;
        }
    }

    if ($data_validade !== '') {
        $val = DateTime::createFromFormat('Y-m-d', $data_validade);
        if (!$val || $val->format('Y-m-d') !== $data_validade) {
            $erros[] = "A Data de Validade introduzida é inválida.";
        } elseif ($doc && $val < $doc) {
            $erros[] = "A Data de Validade não pode ser anterior à Data de Emissão.";
        }
    }

    return $erros;
}

function validar_caminhos_documento(array $ficheiro, string $url_externo): array
{
    $erros = [];
    $url_externo = trim($url_externo);

    $tem_ficheiro = !empty($ficheiro) && isset($ficheiro['error']) && $ficheiro['error'] === UPLOAD_ERR_OK;
    $erro_upload_real = !empty($ficheiro) && isset($ficheiro['error'])
        && $ficheiro['error'] !== UPLOAD_ERR_OK
        && $ficheiro['error'] !== UPLOAD_ERR_NO_FILE;

    if ($erro_upload_real) {
        $erros[] = "Ocorreu um erro ao carregar o ficheiro selecionado. Tenta novamente.";
        return $erros;
    }

    if (!$tem_ficheiro && $url_externo === '') {
        $erros[] = "Tens de anexar um ficheiro PDF ou indicar um link externo para o documento.";
        return $erros;
    }

    if ($tem_ficheiro) {
        $ext = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $erros[] = "Apenas ficheiros PDF são permitidos.";
        } elseif ($ficheiro['size'] > 5 * 1024 * 1024) {
            $erros[] = "O PDF não pode exceder 5MB.";
        }
    }

    if ($url_externo !== '' && !filter_var($url_externo, FILTER_VALIDATE_URL)) {
        $erros[] = "O Link para a Cloud inserido não é válido.";
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
    if (!in_array($criticidade, ['Baixa', 'Média', 'Alta', 'Suporte de vida'], true)) {
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

function validar_caminhos_garantia(array $ficheiro, string $url_externo): array
{
    $erros = [];

    // Validar ficheiro PDF (caso exista upload)
    if (!empty($ficheiro) && isset($ficheiro['error']) && $ficheiro['error'] === UPLOAD_ERR_OK) {

        $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));

        if ($extensao !== 'pdf') {
            $erros[] = "Apenas ficheiros PDF são permitidos.";
        }

        if ($ficheiro['size'] > 5 * 1024 * 1024) {
            $erros[] = "O ficheiro não pode exceder 5 MB.";
        }

        if (!empty($ficheiro) && isset($ficheiro['error'])) {

            if (
                $ficheiro['error'] !== UPLOAD_ERR_OK &&
                $ficheiro['error'] !== UPLOAD_ERR_NO_FILE
            ) {
                $erros[] = "Ocorreu um erro no upload do ficheiro.";
            }
        }
    }

    // Validar URL externo
    if (trim($url_externo) !== '') {

        if (!filter_var(trim($url_externo), FILTER_VALIDATE_URL)) {
            $erros[] = "O link externo não é um URL válido.";
        }

        if (mb_strlen(trim($url_externo)) > 2000) {
            $erros[] = "O URL externo não pode exceder 2000 caracteres.";
        }
    }

    return $erros;
}


// ------------------------------ LOCALIZACOES ------------------------------

function validar_codigo(string $codigo)
{
    $erros = [];
    $codigo = trim($codigo);

    if (empty($codigo)) {
        $erros[] = "O campo ID da Localização é obrigatório.";
    } elseif (strlen($codigo) < 3 || strlen($codigo) > 15) {
        $erros[] = "O ID da Localização deve conter entre 3 e 15 caracteres.";
    } elseif (!preg_match('/^[A-Z0-9\-]+$/i', $codigo)) {
        // Permite apenas letras, números e hífen (padrão para siglas hospitalares)
        $erros[] = "O ID da Localização apenas pode conter letras, números e hífens.";
    }

    return $erros; // Devolve SEMPRE um array (vazio se não houver erros)
}

function validar_nome(string $nome)
{
    $erros = [];
    $nome = trim($nome);

    if (empty($nome)) {
        $erros[] = "O campo Nome do Serviço / Ala é obrigatório.";
    } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
        $erros[] = "O Nome do Serviço deve conter entre 3 e 100 caracteres.";
    }

    return $erros;
}

function validar_edificio(string $edificio)
{
    $erros = [];
    $edificio = trim($edificio);

    if (empty($edificio)) {
        $erros[] = "O campo Edifício / Bloco é obrigatório.";
    }

    return $erros;
}

function validar_piso(string $piso)
{
    $erros = [];

    // Como o piso pode ser "0", verificamos explicitamente se a string está vazia
    if (trim($piso) === "") {
        $erros[] = "O campo Piso é de preenchimento obrigatório.";
    } elseif (!is_numeric($piso)) {
        $erros[] = "O valor do Piso deve ser um número válido.";
    } else {
        $piso_int = (int)$piso;
        // Validação com base nos limites configurados no teu HTML (min="-2" max="7")
        if ($piso_int < -2 || $piso_int > 7) {
            $erros[] = "Piso inválido. O hospital apenas dispõe de pisos entre o -2 e o 7.";
        }
    }

    return $erros;
}

function validar_responsavel(string $responsavel): array
{
    $erros = [];
    $responsavel = trim($responsavel);

    if (empty($responsavel)) {
        $erros[] = "O campo Responsável é obrigatório.";
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
