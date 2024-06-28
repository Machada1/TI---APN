<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeLivro      = mb_convert_encoding($_POST['nomeLivro'], 'UTF-8', 'ISO-8859-1');
    $isbn           = $_POST['isbn'];
    $autor          = $_POST['autor'];
    $categoria      = $_POST['categoria'];
    $dataEdicao     = $_POST['dataEdicao'];
    $edicao         = $_POST['edicao'];
    $exemplares     = $_POST['exemplares'];
    $paginas        = $_POST['paginas'];
    $capa           = $_POST['capa'];

    $stmt = $conn->prepare("INSERT INTO tb_livro (isbn, titulo_livro, autor, categoria, edicao, ano_edicao, paginas, capa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddddds", $isbn, $nomeLivro, $autor, $categoria, $edicao, $dataEdicao, $paginas, $capa);

    try {

        $stmt->execute();

        while($row < $exemplares){

            $stmt = $conn->prepare("INSERT INTO tb_exemplar (isbn, status) VALUES (?, 'Disponível')");
            $stmt->bind_param("s", $isbn);

            $stmt->execute();

            $row = $row + 1;
        }

        $stmt->close();
        $conn->close();
        header("Location: ../html/cadastro.php?status=success");
        exit;
    } catch (Exception $e){
        header("Location: ../html/cadastro.php?status=error");
        exit;
    }
}

$stmt->close();
$conn->close();
?>

?>