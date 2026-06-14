<?php

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

function validar_piso(int $piso): array
{
    $erros = [];
    if (empty($piso)) {
        $erros[] = "O campo Piso é obrigatório.";
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
