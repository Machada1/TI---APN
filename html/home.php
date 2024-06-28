<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT isbn, titulo_livro, capa FROM tb_livro";
$livros = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libr - Biblioteca</title>
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/script.js" defer></script>
</head>

<body>
    <div id="header"></div>

    <div class="container options">
        <a href="../html/cadastro.php">Cadastro</a>
    </div>

    <div class="container option-container">
        <h2>Acervo</h2>
        <div id="searchContainer">
            <input type="text" id="searchInput" placeholder="Digite o nome do livro">
            <button id="searchButton" onclick="buscarLivro()">Buscar</button>
        </div>
        <!-- Bloco de livros do acervo -->
        <div id="bookContainer">
            <?php

            if ($livros->num_rows > 0) {
                while ($row = $livros->fetch_assoc()) {
                    echo '<div class="book" >' .
                        '<a href = "../html/livros.php?isbn='. $row["isbn"] . '">'.
                        "<img src='" . $row['capa'] . "' alt='" . $row["titulo_livro"] . "'>" .
                        '</a>'.
                        '<h3>' . $row["titulo_livro"] . '</h3>' .
                        '</div>';
                }
            }

            ?>
        </div>
    </div>

    <div id="footer"></div>

    <script>
        function buscarLivro() {
            var input, filter, books, bookContainer, book, title, i;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            books = document.getElementsByClassName('book');
            bookContainer = document.getElementById('bookContainer');

            for (i = 0; i < books.length; i++) {
                book = books[i];
                title = book.getElementsByTagName('h3')[0];
                if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                    book.style.display = '';
                } else {
                    book.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>