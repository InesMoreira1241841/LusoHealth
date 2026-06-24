<?php

require_once __DIR__ . '/../../config/config.php';

// Inicia a sessão se ainda não estiver iniciada
function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verifica se a sessão do utilizador está ativa
function check_session()
{
    return isset($_SESSION['username']);
}

// Redireciona automaticamente se não houver sessão iniciada
function redirect_if_not_logged($redirect_to = '/public/login.php')
{
    start_session();
    if (!check_session()) {
        header("Location: " . BASE_URL . $redirect_to);
        exit;
    }
}

function logout_and_redirect($redirect_to = '/public/login.php')
{
    start_session(); // Garante que a sessão foi iniciada
    session_unset(); // Remove todas as variáveis da sessão
    session_destroy(); // Destrói completamente a sessão

    // Redireciona para a página de login com caminho absoluto
    header("Location: " . BASE_URL . $redirect_to);
    exit;
}

// Encriptação e desencriptação de valores com OpenSSL
function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt(
        $value,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    ));
}

function aes_decrypt($value)
{
    if (!is_string($value) || strlen($value) % 2 !== 0) return false; 
    
    return openssl_decrypt( 
        hex2bin($value),
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
}

// --------------------------------------------------------------------
// CONTEÚDOS PÚBLICOS (chave => valor) — para edição dinâmica do site público
// --------------------------------------------------------------------

// Vai à BD e devolve todos os conteúdos como array associativo ['chave' => 'valor']
function get_conteudos_publicos(): array
{
    try {
        $ligacao = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS
        );
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $ligacao->query("SELECT chave, valor FROM conteudos_publicos");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['chave' => 'valor', ...]
    } catch (PDOException $err) {
        error_log($err->getMessage());
        return []; // Se a BD falhar, devolve vazio — o index.php usa os valores por defeito
    } finally {
        $ligacao = null;
    }
}

// Para texto simples (sempre escapado — telefone, email, nomes, etc.)
function c(array $conteudos, string $chave, string $default = ''): string
{
    return htmlspecialchars($conteudos[$chave] ?? $default);
}

// Para conteúdo que pode conter HTML simples (parágrafos com <strong>, etc.)
// Usar apenas para texto inserido por ti no admin, nunca para input de utilizadores externos
function c_html(array $conteudos, string $chave, string $default = ''): string
{
    return $conteudos[$chave] ?? $default;
}