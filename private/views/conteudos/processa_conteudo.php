<?php
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: conteudos.php');
    exit;
}

$acao = $_POST['acao'] ?? '';

try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($acao === 'atualizar') {
        // ----------------------------------------------------------------
        // Atualizar uma entrada já existente (identificada por id)
        // ----------------------------------------------------------------
        $id = $_POST['id'] ?? '';
        $valor = trim($_POST['valor'] ?? '');

        if (!is_numeric($id) || $valor === '') {
            $_SESSION['error_message'] = 'Dados inválidos.';
            header('Location: conteudos.php');
            exit;
        }

        $stmt = $ligacao->prepare("UPDATE conteudos_publicos SET valor = :valor, atualizado_em = NOW() WHERE id = :id");
        $stmt->execute([':valor' => $valor, ':id' => $id]);

        $_SESSION['success_message'] = 'Conteúdo atualizado com sucesso.';

    } elseif ($acao === 'criar') {
        // ----------------------------------------------------------------
        // Criar uma nova entrada (chave deve ser única — restrição da tabela)
        // ----------------------------------------------------------------
        $chave = trim($_POST['chave'] ?? '');
        $valor = trim($_POST['valor'] ?? '');

        if ($chave === '' || $valor === '') {
            $_SESSION['error_message'] = 'A chave e o valor são obrigatórios.';
            header('Location: conteudos.php');
            exit;
        }

        // Normaliza a chave: minúsculas, sem espaços, com underscores
        $chave = strtolower(str_replace(' ', '_', $chave));

        $stmt = $ligacao->prepare("INSERT INTO conteudos_publicos (chave, valor, atualizado_em) VALUES (:chave, :valor, NOW())");
        $stmt->execute([':chave' => $chave, ':valor' => $valor]);

        $_SESSION['success_message'] = "Entrada '$chave' criada com sucesso.";

    } else {
        $_SESSION['error_message'] = 'Ação desconhecida.';
    }

} catch (PDOException $err) {
    error_log($err->getMessage());
    if ($err->getCode() == 23000) {
        // Código de erro padrão para violação de UNIQUE constraint
        $_SESSION['error_message'] = 'Já existe uma entrada com essa chave.';
    } else {
        $_SESSION['error_message'] = 'Erro ao gravar os dados.';
    }
} finally {
    $ligacao = null;
}

header('Location: conteudos.php');
exit;