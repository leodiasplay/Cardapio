<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }
        label {
            display: block;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        input, textarea {
            width: calc(100% - 16px); /* Full width minus padding */
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Includes padding in width calculation */
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #5c67f2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #5058e4;
        }
        .btn-secondary {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Media query for mobile devices */
        @media (max-width: 600px) {
            form {
                padding: 10px;
                width: 90%;
                max-width: none;
            }
            input, textarea {
                width: calc(100% - 20px); /* Adjust padding for smaller screens */
            }
            label {
                margin-top: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    
    <h2>Atualizar item</h1>

    <?php
    include 'banco.php';
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $codigo = $_GET['codigo'];
    $sql = "SELECT * FROM itens WHERE CODIGO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        echo "<form action='atualiza_item.php' method='post'>
                <input type='hidden' name='codigo' value='{$row['CODIGO']}' />
                <label for='titulo'>Título:</label>
                <input type='text' id='titulo' name='titulo' value='{$row['TITULO']}' />

                <label for='descricao'>Descrição:</label>
                <textarea id='descricao' name='descricao'>{$row['DESCRICAO']}</textarea>

                <label for='valor_compra'>Valor de Compra:</label>
                <input type='text' id='valor_compra' name='valor_compra' value='{$row['VALOR_COMPRA']}' />

                <label for='valor_venda'>Valor de Venda:</label>
                <input type='text' id='valor_venda' name='valor_venda' value='{$row['VALOR_VENDA']}' />

                <label for='lucro'>Lucro:</label>
                <input type='text' id='lucro' name='lucro' value='{$row['LUCRO']}' />

                <button type='submit'>Atualizar</button>
                <button type='button' class='btn-secondary' onclick='window.location=\"item_edicao.php\"'>Voltar</button>
              </form>";
    } else {
        echo "<p>Item não encontrado!</p>";
    }
    $conn->close();
    ?>
</body>
</html>
