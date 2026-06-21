<?php
require_once 'includes/funcoes.php';
start_session();

// Impede acesso direto (só aceita POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    exit;
}

// Recolha de dados do formulário
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validação
$validation_errors = [];

if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O nome de utilizador deve ter entre 5 e 50 caracteres.';
}
if (strlen($password) < 6 || strlen($password) > 255) {
    $validation_errors[] = 'A palavra-passe deve ter entre 6 e 255 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ../public/login.php');
    exit;
}

// --- VERIFICAÇÃO REAL NA BASE DE DADOS ---
try {
    $ligacao = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM utilizadores WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $userBd = $stmt->fetch(PDO::FETCH_OBJ);

    // Verifica se o utilizador existe e se a password coincide com o hash guardado
    if ($userBd && password_verify($password, $userBd->password)) {

        $_SESSION['username'] = $userBd->username;

        $_SESSION['success_message'] = 'Bem-vindo, ' . htmlspecialchars($userBd->username) . '!';
        header('Location: home.php');
        exit;
    } else {
        $_SESSION['server_error'] = 'Utilizador ou palavra-passe incorretos.';
        header('Location: ../public/login.php');
        exit;
    }
} catch (PDOException $err) {
    error_log($err->getMessage()); // guarda no log do servidor, não mostra ao utilizador
    $_SESSION['server_error'] = 'Ocorreu um erro. Tente novamente mais tarde.';
    header('Location: ../public/login.php');
    exit;
}
