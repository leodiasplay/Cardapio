<?php
    
    include 'banco.php';

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $codigo = $_POST['codigo'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $valor_compra = $_POST['valor_compra'];
    $valor_venda = $_POST['valor_venda'];
    $lucro = $_POST['lucro'];

    $sql = "UPDATE itens SET TITULO=?, DESCRICAO=?, VALOR_COMPRA=?, VALOR_VENDA=?, LUCRO=? WHERE CODIGO=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddi", $titulo, $descricao, $valor_compra, $valor_venda, $lucro, $codigo);

    if ($stmt->execute()) {
        echo "Item atualizado com sucesso!";
        // Redirecionamento automático para a página anterior
        echo "<script>setTimeout(function(){ window.history.back(); }, 3000);</script>"; // Espera 3 segundos antes de voltar
    } else {
        echo "Erro ao atualizar item: " . $stmt->error;
    }

    $conn->close();
?>
