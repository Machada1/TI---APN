<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Falha na conexão com o banco de dados']);
    exit();
}

$sql = "SELECT e.id_emprestimo, l.titulo_livro as book, u.nome_usuario as borrower, e.data_emprestimo as loanDate, e.data_devolucao as returnDate 
        FROM tb_emprestimo e 
        JOIN tb_exemplar ex ON e.exemplar = ex.id_exemplar 
        JOIN tb_usuario u  ON e.usuario = u.id_usuario
        JOIN tb_livro l ON ex.isbn = l.isbn";

$result = $conn->query($sql);

$loans = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loans[] = $row;
    }
}

echo json_encode($loans);

$conn->close();
?>