<?php // conexao 
$conn = new mysqli("localhost", "root", "1234", "receita");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>