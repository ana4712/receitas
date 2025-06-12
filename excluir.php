<?php
// Inclui o arquivo de conexão com o banco
require_once 'conexao.php';

// Verifica se foi passado o ID da receita via URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da receita não informado.");
}

// Converte o ID para inteiro (segurança)
$id_receita = (int) $_GET['id'];

// Prepara a query para deletar a receita pelo ID
//Lembre que na sua tabela receita_ingredientes está ON DELETE CASCADE, então os ingredientes vinculados serão apagados automaticamente
$stmt = $conn->prepare("DELETE FROM receitas WHERE id_receita = ?");
$stmt->bind_param("i", $id_receita);
$stmt->execute();

// Verifica se realmente uma linha foi deletada
if ($stmt->affected_rows > 0) {
    // Se deletou com sucesso, redireciona para o index com uma mensagem de sucesso na URL
    header("Location: index.php?msg=Receita+excluída+com+sucesso");
    exit; // Para o script após o redirecionamento
} else {
    // Caso contrário, mostra uma mensagem de erro
    echo "Erro ao excluir a receita.";
}
?>
