<?php
session_start(); // Inicia a sessão

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Armazena o nome da empresa em uma variável para fácil acesso
$nomeEmpresa = $_SESSION['empresa'];

include 'NavBar.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inserir Item no Painel ADM</title>
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
    form {
        max-width: 300px;
        margin: 20px auto 50px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
    label {
        margin-bottom: 5px;
        font-weight: bold;
    }
    input[type="text"], input[type="number"], input[type="file"], textarea {
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
<form action="" method="POST" enctype="multipart/form-data">
    <label for="Nome_empresa">Empresa:</label>
    <input type="text" id="Nome_empresa" name="Nome_empresa" required value="<?php echo htmlspecialchars($nomeEmpresa); ?>" readonly>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required>

    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao" rows="4"></textarea>

    <label for="foto">Foto:</label>
    <input type="file" id="foto" name="foto" accept="image/png, image/jpeg">

    <label for="valor_compra">Valor de Compra:</label>
    <input type="number" id="valor_compra" name="valor_compra" step="0.01" min="0">

    <label for="valor_venda">Valor de Venda:</label>
    <input type="number" id="valor_venda" name="valor_venda" step="0.01" min="0">

    <label for="lucro">Lucro:</label>
    <input type="number" id="lucro" name="lucro" step="0.01" min="0">

    <input type="submit" name="cadastrar" value="Inserir Item">
    <button type="button" onclick="alert('Cancelado!');">Cancelar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
    include 'banco.php'; // Assegure-se de incluir o arquivo de conexão corretamente

    $titulo       = $_POST['titulo'];
    $descricao    = $_POST['descricao'];
    $fotoNome     = $_FILES['foto']['name'];
    $valorCompra  = $_POST['valor_compra'];
    $valorVenda   = $_POST['valor_venda'];
    $lucro        = $_POST['lucro'];

    $diretorioUpload = 'uploads/';
    if (!is_dir($diretorioUpload)) {
        mkdir($diretorioUpload, 0777, true); // Cria o diretório se ele não existir
    }
    $caminhoCompleto = $diretorioUpload . basename($fotoNome);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
        echo "O arquivo " . htmlspecialchars(basename($fotoNome)) . " foi carregado.";
    } else {
        echo "Ocorreu um erro ao fazer upload do arquivo.";
    }

    $sql = "INSERT INTO itens (TITULO, DESCRICAO, FOTO, VALOR_COMPRA, VALOR_VENDA, LUCRO, Nome_empresa) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $titulo, $descricao, $fotoNome, $valorCompra, $valorVenda, $lucro, $nomeEmpresa);

    if ($stmt->execute()) {
        echo "Novo registro criado com sucesso.";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
