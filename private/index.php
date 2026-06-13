<?php
require_once 'includes/funcoes.php';
start_session();

// Redireciona para home se já autenticado, caso contrário para login
if (check_session()) {
    header('Location: home.php');
} else {
    header('Location: ../public/login.php');
}
exit;
