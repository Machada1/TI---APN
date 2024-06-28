<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accesskey = $_POST['accesskey'];

    $sql = "SELECT nome_bibliotecaria FROM tb_bibliotecaria WHERE chave_acesso =  '$accesskey' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $accesskey;
        }
        header("Location: ../html/home.php");
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
