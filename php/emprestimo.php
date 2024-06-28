<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Falha na conexão com o banco de dados']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idBibliotecaria']) && isset($_POST['selectedUser']) && isset($_POST['isbn'])) {
    $idBibliotecaria = $conn->real_escape_string($_POST['idBibliotecaria']);
    $selectedUser = $conn->real_escape_string($_POST['selectedUser']);
    $isbn = $conn->real_escape_string($_POST['isbn']);

    $sql = "SELECT id_exemplar FROM tb_exemplar WHERE isbn = '$isbn' AND status = 'Disponível' LIMIT 1";
    $exemplaresLivres = $conn->query($sql);

    if ($exemplaresLivres && $exemplaresLivres->num_rows > 0) {
        $row = $exemplaresLivres->fetch_assoc();
        $exemplar = $row["id_exemplar"];

        $sql = "INSERT INTO tb_emprestimo (usuario, exemplar, data_emprestimo, data_devolucao, bibliotecaria) VALUES (?, ?, CURRENT_DATE(), DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $selectedUser, $exemplar, $idBibliotecaria);

        if ($stmt->execute()) {
            $updateSql = "UPDATE tb_exemplar SET status = 'Emprestado' WHERE id_exemplar = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("s", $exemplar);

            if ($updateStmt->execute()) {
                echo json_encode(['status' => 'success', 'selectedUser' => $selectedUser]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o status do exemplar: ' . $updateStmt->error]);
            }

            $updateStmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar o empréstimo: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nenhum exemplar disponível encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos ou inválidos']);
}

$conn->close();
?>