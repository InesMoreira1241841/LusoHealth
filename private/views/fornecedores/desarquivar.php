<?php
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

$id_encriptado = $_GET['id'] ?? '';
$id_desencriptado = aes_decrypt($id_encriptado);

if (!$id_desencriptado || !is_numeric($id_desencriptado)) {
    $_SESSION['error_message'] = "Identificador inválido.";
    header("Location: fornecedores.php?modo=arquivados");
    exit;
}

try {
    $ligacao = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Restaura o registo definindo arquivado a 0
    $sql = "UPDATE fornecedores SET arquivado = 0 WHERE id = :id";
    $stmt = $ligacao->prepare($sql);
    $stmt->execute([':id' => $id_desencriptado]);

    $_SESSION['success_message'] = "O fornecedor foi restaurado com sucesso.";
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro interno ao tentar restaurar o registo.";
}

// Redireciona de volta para a lista de arquivados para ver o item a sumir dali
header("Location: fornecedores.php?modo=arquivados");
exit;