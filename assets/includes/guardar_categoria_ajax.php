<?php
// =========================================================================
// FICHEIRO: guardar_categoria_ajax.php
// =========================================================================

// 1. Inclui as definições e funções globais (onde estão o DB_HOST, DB_NAME, etc.)
// Ajustámos o caminho para subir a partir de assets/includes/
require_once __DIR__ . '/../../private/includes/funcoes.php'; 

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_categoria'])) {
    
    $nome = trim($_POST['nome_categoria']);

    if (!empty($nome)) {
        try {
            // 2. PRIMEIRO: Criamos a ligação à Base de Dados
            $ligacao = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 3. SEGUNDO: Fazemos o prepare (agora a variável $ligacao já existe com toda a certeza!)
            $stmt = $ligacao->prepare("INSERT INTO categorias (nome) VALUES (?)");
            
            if ($stmt->execute([$nome])) {
                $id_gerado = $ligacao->lastInsertId();
                $ligacao = null; // Fecha a ligação

                echo json_encode([
                    "sucesso" => true,
                    "id" => $id_gerado,
                    "nome" => $nome
                ]);
                exit;
            } else {
                echo json_encode(["sucesso" => false, "erro" => "Não foi possível gravar no banco de dados."]);
                exit;
            }

        } catch (PDOException $e) {
            echo json_encode(["sucesso" => false, "erro" => "Erro de BD: " . $e->getMessage()]);
            exit;
        }
    }
}

echo json_encode(["sucesso" => false, "erro" => "Dados inválidos ou campo vazio."]);