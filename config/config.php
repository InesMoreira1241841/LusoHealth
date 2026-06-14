<?php 

// Configurações globais da aplicação 

define('BASE_URL', '/lusohealth');

define('APP_NAME', 'LusoHealth'); 
define('APP_VERSION', '1.0.0'); 
define('APP_COPYRIGHT', 'Copyright 2026 ©'); 

// Base de dados

define('DB_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('DB_PORT', '10464');
define('DB_NAME', 'db1241841');     
define('DB_USER', '1241841');    
define('DB_PASS', 'moreira_841'); 


// Segurança – Encriptação com OpenSSL

define('OPENSSL_METHOD', 'AES-256-CBC'); // Algoritmo simétrico robusto
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa'); // Chave de 32 caracteres
define('OPENSSL_IV', 'BzKAbjuREsHgnw56'); // Vetor de inicialização (16 caracteres)
