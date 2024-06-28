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

    <?php

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "biblioteca";

    $conn = new mysqli($servername, $username, $password, $dbname);

    session_start();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["isbn"])) {
        $isbn = $conn->real_escape_string($_GET["isbn"]);

        $sql = "SELECT * FROM tb_usuario";
        $usuario = $conn->query($sql);
        
        $usuariosJson = [];
        while ($row2 = $usuario->fetch_assoc()) {
            $usuariosJson[] = $row2;
        }

        $usuarios = json_encode($usuariosJson);

        $sql = "SELECT isbn, titulo_livro, capa, ano_edicao, autor FROM tb_livro WHERE isbn = '$isbn'";
        $livro = $conn->query($sql);

        if ($livro && $livro->num_rows > 0) {
            echo '<div class="container book-details">';

            while ($row = $livro->fetch_assoc()) {
                echo '<img src="' . $row["capa"] . '" alt="' . $row["titulo_livro"] . '">' .
                    '<h2>' . $row["titulo_livro"]  . '</h2>' .
                    '<h3>Data de Publicação: ' . $row["ano_edicao"] . '</h3>';

                $sql = "SELECT nome_autor FROM tb_autor WHERE id_autor = " . $row["autor"];
                $nomeAutor = $conn->query($sql);

                if ($nomeAutor && $nomeAutor->num_rows > 0) {
                    while ($row2 = $nomeAutor->fetch_assoc()) {
                        echo '<h3>Autor: ' . $row2["nome_autor"] . '</h3>';
                    }
                }
            }

            $sql = "SELECT count(id_exemplar) AS total FROM tb_exemplar WHERE isbn = '$isbn'";
            $exemplares = $conn->query($sql);

            if ($exemplares && $exemplares->num_rows > 0) {
                while ($row = $exemplares->fetch_assoc()) {
                    echo '<h4>Exemplares: ' . $row["total"] . '</h4>';
                }
            }

            $sql = "SELECT count(id_exemplar) AS disponiveis FROM tb_exemplar WHERE isbn = '$isbn' AND status = 'Disponível'";
            $exemplaresLivres = $conn->query($sql);

            if ($exemplaresLivres && $exemplaresLivres->num_rows > 0) {
                while ($row = $exemplaresLivres->fetch_assoc()) {
                    echo '<h4>Disponíveis: ' . $row["disponiveis"]  . '</h4>' .
                        '<button onclick=\'emprestimo("' . $_SESSION["username"] . '", ' . $usuarios . ', "' . $isbn . '")\'>Realizar Empréstimo</button>';
                }
            }

            echo '</div>';
        } else {
            echo '<p>Nenhum livro encontrado com o ISBN fornecido.</p>';
        }
    }

    $conn->close();
    ?>

    <script>
        function emprestimo(idBibliotecaria, arrayUsuarios, isbn) {

            Swal.fire({
                title: 'Escolha o nome do beneficiário',
                icon: 'info',
                html: '<select id="usuarioSelect" class="swal2-input"></select>',
                preConfirm: () => {
                    const selectedUser = document.getElementById('usuarioSelect').value;
                    return selectedUser;
                },
                didOpen: () => {
                    const select = document.getElementById('usuarioSelect');
                    arrayUsuarios.forEach(usuario => {
                        const option = document.createElement('option');
                        option.value = usuario.id_usuario;
                        option.textContent = usuario.nome_usuario;
                        select.appendChild(option);
                    });
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    const selectedUser = result.value;
                    $.ajax({
                        url: '../php/emprestimo.php',
                        type: 'POST',
                        data: {
                            idBibliotecaria: idBibliotecaria,
                            selectedUser: selectedUser,
                            isbn: isbn
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(`Empréstimo realizado!`);
                            } else {
                                Swal.fire('Erro', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Erro', 'Não foi possível processar a solicitação', 'error');
                        }
                    });
                }
            });
        }
    </script>

</body>
</html>