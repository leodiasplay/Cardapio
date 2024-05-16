<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Online</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #333;
            color: white;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #222;
        }

        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .item {
            width: 90%;
            margin: 10px;
            background: #444;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .item:hover {
            transform: scale(1.05);
        }

        .item img {
            width: 100%;
            display: block;
        }

        .item h2, .item p {
            margin: 5px 10px;
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            padding: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #666;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #7a7;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="item">
            <img src="beef-burger.jpg" alt="Beef Burger">
            <h2>Beef Burger</h2>
            <p>$24.00</p>
            <div class="buttons">
                <button onclick="addItem(this)">Adicionar</button>
                <button onclick="removeItem(this)">Remover</button>
            </div>
        </div>
        <div class="item">
            <img src="chicken-breast.jpg" alt="Chicken Breast">
            <h2>Chicken Breast</h2>
            <p>$18.00</p>
            <div class="buttons">
                <button onclick="addItem(this)">Adicionar</button>
                <button onclick="removeItem(this)">Remover</button>
            </div>
        </div>
        <div class="item">
            <img src="img/x-tudo.jpeg" alt="Crab Ramen">
            <h2>Crab Ramen</h2>
            <p>$24.00</p>
            <div class="buttons">
                <button onclick="addItem(this)">Adicionar</button>
                <button onclick="removeItem(this)">Remover</button>
            </div>
        </div>
        <div class="item">
            <img src="spicy-chicken.jpg" alt="Spicy Chicken">
            <h2>Spicy Chicken</h2>
            <p>$12.00</p>
            <div class="buttons">
                <button onclick="addItem(this)">Adicionar</button>
                <button onclick="removeItem(this)">Remover</button>
            </div>
        </div>
    </div>
    <script>
        function addItem(button) {
            const itemName = button.parentNode.parentNode.querySelector('h2').textContent;
            alert("Adicionado ao carrinho: " + itemName);
        }

        function removeItem(button) {
            const itemName = button.parentNode.parentNode.querySelector('h2').textContent;
            alert("Removido do carrinho: " + itemName);
        }
    </script>
</body>
</html>
