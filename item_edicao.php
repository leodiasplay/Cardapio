<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

include 'banco.php';

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Preparação da consulta SQL com condição WHERE para filtrar por Nome_empresa
$sql = "SELECT CODIGO, TITULO FROM itens WHERE Nome_empresa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nomeEmpresa); // 's' indica que o parâmetro é uma string
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Itens</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .menu-btn {
            background-color: #007bff;
            border: none;
            padding: 8px 15px;
            color: white;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .navbar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }
        .navbar a, .dropdown-btn {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 25px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .navbar a:hover, .dropdown-btn:hover {
            background-color: #ddd;
            color: black;
        }
        .dropdown-container {
            display: none;
            background-color: #262626;
            padding-left: 8px;
        }
        .closebtn {
            position:absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            color: white;
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
            border-color: #808080;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            text-align: right; /* Alinha o conteúdo à direita */
        }
        .actions a {
            text-decoration: none;
            color: white; /* Light Green changed to white for better visibility on button */
            background-color: #32CD32; /* Light Green background for button */
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }
        .actions a:hover {
            text-decoration: none;
            background-color: #29b529; /* Slightly darker green for hover effect */
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Lista de Itens</h1>
    <button class="menu-btn" onclick="toggleNav()">☰</button>
</div>

<div id="myNavbar" class="navbar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <a href="javascript:void(0)" class="dropdown-btn">Item</a>
    <div class="dropdown-container">
        <a href="Cadastro_item.php">Cadastro Item</a>
        <a href="item_edicao.php">Editar Item</a>
    </div>

    <a href="javascript:void(0)" class="dropdown-btn">Usuarios</a>
    <div class="dropdown-container">
        <a href="usuario_cadastro.php">Cadastro Usuarios</a>
        <a href="">Editar Usuarios</a>
    </div>

    <a href="javascript:void(0)" class="dropdown-btn">Cardapio</a>
    <div class="dropdown-container">
        <a href="Cardapio.php">Meu Cardapio</a>
        
    </div>
    
</div>


<table>
    <tr>
        <th>Título</th>
        <th class='actions'></th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['TITULO']}</td>
                    <td class='actions'><a href='detalhes.php?codigo={$row['CODIGO']}'>Editar</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Nenhum item encontrado</td></tr>";
    }
    $stmt->close();
    $conn->close();
    ?>
</table>

<script>
    function toggleNav() {
        var navbar = document.getElementById("myNavbar");
        if (navbar.style.width === "250px") {
            navbar.style.width = "0";
        } else {
            navbar.style.width = "250px";
        }
    }

    function closeNav() {
        document.getElementById("myNavbar").style.width = "0";
    }

    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

</body>
</html>


