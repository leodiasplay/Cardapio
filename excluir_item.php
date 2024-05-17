<?php
// Inclui o arquivo de conexão com o banco de dados
include 'banco.php';

// Verifica se o código do item foi fornecido
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Prepara a query SQL para deletar o item
    $sql = "DELETE FROM itens WHERE CODIGO = ?";

    // Prepara a declaração para execução
    $stmt = $conn->prepare($sql);

    // Verifica se a declaração foi preparada com sucesso
    if ($stmt === false) {
        die('Erro ao preparar a declaração: ' . htmlspecialchars($conn->error));
    }

    // Vincula os parâmetros (s = string, i = integer, d = double, b = blob)
    $stmt->bind_param("i", $codigo);

    // Executa a declaração
    if ($stmt->execute()) {
        // Se a execução for bem-sucedida, redireciona para uma página de sucesso
        header('Location: item_deletado_sucesso.php');
    } else {
        // Se ocorrer um erro, mostra uma mensagem de erro
        echo "Erro ao excluir o item: " . htmlspecialchars($stmt->error);
    }

    // Fecha a declaração
    $stmt->close();
} else {
    // Se o código não for fornecido, redireciona para a página de erro
    header('Location: erro.php?mensagem=Código do item não fornecido');
}

// Fecha a conexão com o banco
$conn->close();
?>
