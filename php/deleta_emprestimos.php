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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);

    $sql = "DELETE FROM tb_emprestimo WHERE id_emprestimo = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Empréstimo deletado com sucesso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar o empréstimo: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos ou inválidos']);
}

$conn->close();
?>