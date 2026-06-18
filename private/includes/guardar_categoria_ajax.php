<?php
// Forçar o navegador a interpretar a resposta estritamente como JSON
header('Content-Type: application/json; charset=utf-8');

// Impedir que o PHP mostre avisos de texto normais no ecrã (o que corromperia o JSON)
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/funcoes.php';
start_session();

// CORREÇÃO: Verifica se a sessão 'utilizador' existe (conforme definido no seu processa_login.php)
if (!isset($_SESSION['utilizador']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado ou sessão expirada.']);
    exit;
}

// Recolher e limpar o nome enviado pelo formulário
$nome = trim($_POST['nome'] ?? '');

if (empty($nome)) {
    echo json_encode(['success' => false, 'message' => 'O nome da categoria não pode estar vazio.']);
    exit;
}

// Normalizar o nome (ex: "MONITORES" ou "monitores" -> "Monitores")
$nome = ucwords(strtolower($nome));

try {
    // Ligar à Base de Dados usando as constantes globais do seu projeto
    $ligacao = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Verificar se a categoria já existe na base de dados
    $stmtCheck = $ligacao->prepare("SELECT id FROM categorias WHERE nome = :nome");
    $stmtCheck->execute([':nome' => $nome]);
    
    if ($stmtCheck->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Esta categoria já se encontra registada.']);
        exit;
    }

    // 2. Inserir a nova categoria se não for duplicada
    $stmt = $ligacao->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
    $stmt->execute([':nome' => $nome]);
    $idGerado = $ligacao->lastInsertId();

    // 3. Responder com sucesso e enviar os dados para o JavaScript atualizar o <select>
    echo json_encode([
        'success' => true,
        'id' => $idGerado,
        'nome' => $nome
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro na base de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro interno do sistema: ' . $e->getMessage()]);
} finally {
    $ligacao = null;
}