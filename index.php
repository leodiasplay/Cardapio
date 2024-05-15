<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

// Conectar ao banco de dados
include 'banco.php'; // Assegure-se de substituir 'banco.php' pelo seu arquivo de conexão real

// Consulta para obter a foto da empresa
$sql = "SELECT FOTO_EMPRESA FROM empresas WHERE Nome_empresa = ?";
$fotoPerfil = 'img/default.jpg'; // Imagem padrão se nenhuma foto for encontrada
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $nomeEmpresa);
    $stmt->execute();
    $stmt->bind_result($fotoEmpresa);
    if ($stmt->fetch()) {
        $fotoPerfil = $fotoEmpresa ?: $fotoPerfil; // Atribui a foto da empresa ou a padrão
    }
    $stmt->close();
}
$conn->close();

include 'NavBar.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil e Botão de Abrir/Fechar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    
</body>
</html>
