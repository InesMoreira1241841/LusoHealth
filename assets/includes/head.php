<?php
require_once __DIR__ . '/../../config/config.php';
?>

<!DOCTYPE html> <!-- Informa ao navegador que este é um documento HTML5 -->

<html lang="pt">
<!-- <html> define o início do documento HTML -->
<!-- lang="pt" indica que o idioma principal da página é português -->

<head>
    <meta charset="UTF-8">
    <!-- Define a codificação de caracteres para UTF-8, que 
         suporta acentos e caracteres especiais usados em português -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Torna a página responsiva, ajustando-a para diferentes 
         tamanhos de ecrã, especialmente em dispositivos móveis -->
    <title><?php echo APP_NAME; ?></title>

    <!-- favicon -->
    <link rel="shortcut icon" href="/lusohealth/assets/img/logo.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (local) -->
    <link rel="stylesheet" href="/lusohealth/assets/fontawesome/all.min.css">

    <!-- Bootstrap CSS & custom CSS -->
    <link rel="stylesheet" href="/lusohealth/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/lusohealth/assets/css/1241841.css">
</head>