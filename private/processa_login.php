<?php
require_once 'includes/funcoes.php';
start_session();

// Impede acesso direto (só aceita POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    exit;
}

// Recolha de dados do formulário
$username = isset($_POST['utilizador']) ? trim($_POST['utilizador']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validação
$validation_errors = [];

if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O nome de utilizador deve ter entre 5 e 50 caracteres.';
}
if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A palavra-passe deve ter entre 6 e 12 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ../public/login.php');
    exit;
}

// Simulação de login (será substituído por verificação real à BD)
$result['status'] = 1;

if (!$result['status']) {
    $_SESSION['server_error'] = 'Login inválido. Verifique as credenciais.';
    header('Location: ../public/login.php');
    exit;
}

// Login bem-sucedido
$_SESSION['utilizador'] = $username;
$_SESSION['success_message'] = 'Bem-vindo, ' . htmlspecialchars($username) . '!';
header('Location: home.php');
exit;
