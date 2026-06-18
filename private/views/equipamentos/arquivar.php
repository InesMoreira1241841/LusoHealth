<?php
// 1. Inicialização do ecossistema de segurança 
require_once __DIR__ . '/../../includes/funcoes.php';
start_session();
redirect_if_not_logged();

// 2. Verificação do parâmetro GET e Desencriptação 
$id_encriptado = $_GET['id_equipamentos'] ?? '';
$id_desencriptado = aes_decrypt($id_encriptado);

// Se o ID for inválido ou a desencriptação falhar, bloqueia o ataque
if (!$id_desencriptado || !is_numeric($id_desencriptado)) {
    $_SESSION['error_message'] = "Requisição inválida ou identificador corrompido.";
    header("Location: equipamentos.php");
    exit;
}

// 3. Processamento do Arquivo Lógico via PDO 
try {
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // IMPORTANTE: Faz-se UPDATE e não DELETE para preservar o histórico relacional
    $sql = "UPDATE equipamentos SET arquivado = 1 WHERE id = :id";
    $stmt = $ligacao->prepare($sql);
    $stmt->execute([':id' => $id_desencriptado]);

    // 4. Feedback de Sucesso e Redirecionamento
    $_SESSION['success_message'] = "O equipamento foi arquivado com sucesso.";
    
} catch (PDOException $e) {
    // Isola o erro técnico para não expor a estrutura do servidor ao utilizador
    $_SESSION['error_message'] = "Erro interno ao tentar arquivar o equipamento selecionado.";
}

// Volta automaticamente para a tabela
header("Location: equipamentos.php");
exit;