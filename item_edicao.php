<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

include 'banco.php'; // Inclui o arquivo de conexão ao banco de dados

// Consulta para obter a foto da empresa
$fotoPerfil = 'img/default.jpg'; // Imagem padrão se nenhuma foto for encontrada
if ($conn) {
    $sql = "SELECT FOTO_EMPRESA FROM empresas WHERE Nome_empresa = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();
        $stmt->bind_result($fotoEmpresa);
        if ($stmt->fetch()) {
            $fotoPerfil = $fotoEmpresa ?: $fotoPerfil; // Atribui a foto da empresa ou a padrão
        }
        $stmt->close();
    } else {
        echo "Erro: " . $conn->error;
    }

    // Preparação da consulta SQL com condição WHERE para filtrar por Nome_empresa
    $sql = "SELECT CODIGO, TITULO FROM itens WHERE Nome_empresa = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Erro ao preparar consulta: " . $conn->error;
    }
}

if (isset($conn) && !$conn->connect_error) {
    $conn->close(); // Fecha a conexão somente após todas as operações
}

 include 'Navbar.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Itens</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #808080;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            text-align: right;
        }
        .actions a {
            text-decoration: none;
            color: white;
            background-color: #32CD32;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }
        .actions a:hover {
            background-color: #29b529;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Título</th>
            <th class='actions'></th>
        </tr>
        <?php
        if (isset($result) && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['TITULO']}</td>
                        <td class='actions'><a href='detalhes.php?codigo={$row['CODIGO']}'>Editar</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Nenhum item encontrado</td></tr>";
        }
        ?>
    </table>
</body>
</html>
