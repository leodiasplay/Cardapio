<?php
    // Inicia a sessão uma vez no início do script
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include 'banco.php';
    $nomeEmpresa = $_SESSION['empresa'];

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
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar com Perfil e Botão de Abrir/Fechar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: right;
        }
        .navbar {
            background-color: #3b5998;
            width: 300px;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            color: white;
            position: fixed;
            top: 0;
            left: -300px;
            transition: left 0.3s;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 24px;
            color: white;
        }
        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile .name, .profile .email {
            font-size: 16px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            font-size: 16px;
            padding: 10px 0;
            cursor: pointer;
        }
        .menu-item i {
            margin-right: 10px;
        }
        .submenu {
            display: none;
            padding-left: 20px;
        }
        .toggle-button {
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <header>
            <span class="toggle-button" onclick="toggleNavbar()">☰</span>
        </header>
        <div class="navbar">
        <span class="close-button" onclick="toggleNavbar()">&times;</span>
        <div class="profile">
            <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Perfil">
            <div class="name"><?php echo htmlspecialchars($nomeEmpresa); ?></div>
            <div class="email">Joaolanches@gmail.com</div>
        </div>

        <div class="menu-item" onclick="toggleSubmenu('user-submenu')"><i class="fas fa-user"></i>Usuários</div>
        <div id="user-submenu" class="submenu">
            <div class="menu-item" onclick="window.location.href='usuario_cadastro.php';">
                <i class="fas fa-user-plus"></i>Cadastro de Usuário
            </div>
            <div class="menu-item" onclick="window.location.href='usuario_cadastro.php';">
                <i class="fas fa-user-edit"></i>Editar Usuário
            </div>
        </div>

        <div class="menu-item" onclick="toggleSubmenu('stock-submenu')"><i class="fas fa-boxes"></i>Estoque</div>
        <div id="stock-submenu" class="submenu">
            <div class="menu-item" onclick="window.location.href='Cadastro_item.php';">
                <i class="fas fa-plus"></i>Item Cadastro
            </div>
            <div class="menu-item" onclick="window.location.href='item_edicao.php';">
                <i class="fas fa-edit"></i>Editar Item
            </div>          
        </div>

        <div class="menu-item" onclick="toggleSubmenu('cardapio-submenu')"><i class="fas fa-utensils"></i>Cardápio</div>
        <div id="cardapio-submenu" class="submenu">
            <div class="menu-item" onclick="window.location.href='Cardapio.php';">
                <i class="fas fa-plus"></i>Meu Cardápio
            </div>
        </div>

        <div class="menu-item"><i class="fas fa-cog"></i>Configurações</div>
    </div>

    <script>
        function toggleNavbar() {
            const navbar = document.querySelector('.navbar');
            if (navbar.style.left === '0px') {
                navbar.style.left = '-300px';
            } else {
                navbar.style.left = '0px';
            }
        }

        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
