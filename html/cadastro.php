<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_autor, nome_autor FROM tb_autor";
$autor = $conn->query($sql);

$sql = "SELECT id_categoria, categoria FROM tb_categoria";
$categoria = $conn->query($sql);

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div id="header"></div>

    <div class="container">
        <div class="form-container">
            <h2>Cadastro de Livro</h2>
            <form id="bookForm" action="../php/cadastrarLivros.php" method="post">
                <label for="nomeLivro">Nome do Livro:</label>
                <input type="text" id="nomeLivro" name="nomeLivro" required>

                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" required>

                <label for="autor">Autor:</label>
                <select id="autor" name="autor">
                    <?php
                    if ($autor->num_rows > 0) {
                        while ($row = $autor->fetch_assoc()) {
                            echo "<option value='" . $row['id_autor'] . "'>" . $row['nome_autor'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum item encontrado</option>";
                    }
                    ?>
                </select>
                <br>
                <br>

                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria">
                    <?php
                    if ($categoria->num_rows > 0) {
                        while ($row = $categoria->fetch_assoc()) {
                            echo "<option value='" . $row['id_categoria'] . "'>" . $row['categoria'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum item encontrado</option>";
                    }
                    ?>
                </select>
                <br>
                <br>

                <label for="dataEdicao">Ano de Edição:</label>
                <input type="text" id="dataEdicao" name="dataEdicao" required>

                <label for="edicao">Edição:</label>
                <input type="text" id="edicao" name="edicao" required></textarea>

                <label for="exemplares">Exemplares:</label>
                <input type="number" id="exemplares" name="exemplares" value="1" min="1" required>

                <br><br>

                <label for="paginas">Páginas:</label>
                <input type="number" id="paginas" name="paginas" value="1" min="1" required>

                <br><br>

                <label for="capa">Insira um Link de imagem para a capa:</label>
                <input type="text" id="capa" name="capa" required>

                <input type="submit" value="Cadastrar Livro">
            </form>
        </div>
    </div>

    <div id="footer"></div>

    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<script>
            Swal.fire({
                title: 'Sucesso!',
                text: 'Livro cadastrado com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        </script>";
    } else if (isset($_GET['status']) && $_GET['status'] == 'error') {
        echo "<script>
            Swal.fire({
                title: 'Erro!',
                text: 'Erro ao cadastrar o Livro selecionado.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        </script>";
    }
    ?>
</body>

</html>