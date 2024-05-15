<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

// Inclui o arquivo de conexão com o banco de dados
include 'banco.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém e sanitiza os dados do formulário
    $nome_empresa = $conn->real_escape_string($_POST['nome_empresa']);
    $cidade      = $conn->real_escape_string($_POST['cidade']);
    $endereco_completo = $conn->real_escape_string($_POST['endereco_completo']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $whatsapp = $conn->real_escape_string($_POST['whatsapp']);
    $sn_ativo = $conn->real_escape_string($_POST['sn_ativo']);

    // Tratar o upload da foto da empresa
    $folder = 'uploads/';
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true); // Criar a pasta se ela não existir
    }

    $foto_empresa = $folder . basename($_FILES["foto_empresa"]["name"]);
    if (move_uploaded_file($_FILES["foto_empresa"]["tmp_name"], $foto_empresa)) {
        echo "Arquivo válido e enviado com sucesso.\n";
    } else {
        echo "Erro no upload do arquivo.\n";
        $foto_empresa = ""; // Configura como vazio se falhar o upload
    }

    // Preparar e executar a consulta SQL
    $sql = "INSERT INTO empresas (Nome_empresa, CIDADE, ENDERECO_COMPLETO, ESTADO, WHATZAP, SN_ATIVO, FOTO_EMPRESA) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $nome_empresa, $cidade, $endereco_completo, $estado, $whatsapp, $sn_ativo, $foto_empresa);
        if ($stmt->execute()) {
            echo "Nova empresa cadastrada com sucesso!";
        } else {
            echo "Erro: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], input[type="file"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], button[type="button"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: calc(50% - 11px);
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        @media (max-width: 600px) {
            form {
                padding: 15px;
                box-shadow: none;
            }
            body {
                padding: 10px;
            }
            input[type="submit"], button[type="button"] {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="nome_empresa">Nome da Empresa:</label>
        <input type="text" id="nome_empresa" name="nome_empresa" required><br>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required><br>

        <label for="endereco_completo">Endereço Completo:</label>
        <input type="text" id="endereco_completo" name="endereco_completo" required><br>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br>

        <label for="whatsapp">WhatsApp:</label>
        <input type="text" id="whatsapp" name="whatsapp"><br>

        <label for="sn_ativo">Ativo (S/N):</label>
        <input type="text" id="sn_ativo" name="sn_ativo" required><br>

        <label for="foto_empresa">Foto da Empresa:</label>
        <input type="file" id="foto_empresa" name="foto_empresa" required><br>

        <input type="submit" value="Cadastrar Empresa">
        <button type="button" onclick="alert('Cancelado!'); window.history.back();">Cancelar</button>
    </form>
</body>
</html>
