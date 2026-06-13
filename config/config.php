<?php 
// Configurações globais da aplicação 
 
define('APP_NAME', 'LusoHealth'); 
define('APP_VERSION', '1.0.0'); 
define('APP_COPYRIGHT', 'Copyright 2026 ©'); 
define('BASE_URL', '/lusohealth');

// ------------------------------ Base de Dados ------------------------------
 
define('DB_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('DB_PORT', '10464');
define('DB_NAME', 'db1241841');       // <- substituir pelo nome da tua BD
define('DB_USER', '1241841');    // <- substituir pelo teu utilizador
define('DB_PASS', 'moreira_841');      // <- substituir pela tua password
 
// Função que cria e devolve a ligação à base de dados
function getDB() {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
 
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // lança exceções em caso de erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // devolve resultados como array associativo
            PDO::ATTR_EMULATE_PREPARES   => false                    // usa prepared statements reais
        ]);
        return $pdo;
 
    } catch (PDOException $e) {
        // em produção nunca mostrar o erro real ao utilizador
        die("Erro na ligação à base de dados. Tente novamente mais tarde.");
    }
}