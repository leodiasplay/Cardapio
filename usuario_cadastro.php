<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
include 'banco.php';

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

// Consulta para obter a foto da empresa
if ($conn) {
    $sql = "SELECT FOTO_EMPRESA FROM empresas WHERE Nome_empresa = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();
        $stmt->bind_result($fotoEmpresa);
        if ($stmt->fetch()) {
            $fotoPerfil = $fotoEmpresa ?: 'img/default.jpg';
        }
        $stmt->close();
    } else {
        echo "Erro: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Erro: Conexão ao banco de dados falhou.";
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém e sanitiza os dados do formulário
    $nome_empresa = $conn->real_escape_string($_POST['nome_empresa']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $senha = $conn->real_escape_string($_POST['senha']);
    $nome_usuario = $conn->real_escape_string($_POST['nome_usuario']);
    $sn_user_adm = $conn->real_escape_string($_POST['sn_user_adm']);

    // Cria a query SQL para inserir os dados
    $sql = "INSERT INTO usuarios (Nome_empresa, USUARIO, SENHA, NOME_USUARIO, SN_USER_ADM) VALUES (?, ?, ?, ?, ?)";

    // Prepara a query para execução
    if ($stmt = $conn->prepare($sql)) {
        // Vincula os parâmetros (s = string)
        $stmt->bind_param("sssss", $nome_empresa, $usuario, $senha, $nome_usuario, $sn_user_adm);

        // Executa a query
        if ($stmt->execute()) {
            // Redireciona para index.php se o cadastro for bem-sucedido
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='index.php';</script>";
        } else {
            // Exibe erro se houver falha no cadastro
            echo "<script>alert('Erro ao cadastrar usuário: " . addslashes($stmt->error) . "'); window.location.href='usuario_cadastro.php';</script>";
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "Erro ao preparar a query: " . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}

    include 'NavBar.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        form {
            max-width: 300px;
            margin: 70px auto 50px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], input[type="number"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
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
        button[type="button"] {
            background-color: #f44336;
            color: white;
            float: right;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <label for="nome_empresa">Nome da Empresa:</label>
        <input type="text" id="nome_empresa" name="nome_empresa" required autocomplete="off" value="<?php echo htmlspecialchars($nomeEmpresa); ?>" readonly>
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required autocomplete="off">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required autocomplete="off">
        <label for="nome_usuario">Nome Completo do Usuário:</label>
        <input type="text" id="nome_usuario" name="nome_usuario" autocomplete="off">
        <label for="sn_user_adm">Usuário ADM:</label>
        <select id="sn_user_adm" name="sn_user_adm" required>
            <option value="SIM">SIM</option>
            <option value="NÃO">NÃO</option>
        </select>
        <input type="submit" value="Cadastrar">
        <button type="button" onclick="alert('Cancelado!'); window.history.back();">Cancelar</button>
    </form>
</body>
</html>
