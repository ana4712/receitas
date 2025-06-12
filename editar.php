<?php
// Inclui a conexão com o banco de dados
require_once 'conexao.php';

// Verifica se o ID da receita foi passado via URL (GET)
// Se não tiver ID ou estiver vazio, para o script e mostra mensagem de erro
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da receita não informado.");
}

// Guarda o ID da receita passado na URL, garantindo que seja um número inteiro
$id_receita = (int) $_GET['id'];

// Prepara a consulta para buscar os dados da receita no banco pelo ID
$sql_receita = "SELECT * FROM receitas WHERE id_receita = ?";
$stmt = $conn->prepare($sql_receita);
$stmt->bind_param("i", $id_receita); // Liga o parâmetro (inteiro) à consulta
$stmt->execute(); // Executa a consulta
$result = $stmt->get_result(); // Pega o resultado

// Se não encontrar nenhuma receita com esse ID, para o script e mostra mensagem de erro
if ($result->num_rows === 0) {
    die("Receita não encontrada.");
}

// Pega os dados da receita em formato de array associativo
$receita = $result->fetch_assoc();

// Busca todos os ingredientes cadastrados para listar no formulário (para o usuário selecionar/editar)
$ingredientes = $conn->query("SELECT * FROM ingredientes");

// Busca os ingredientes vinculados à receita que estamos editando, junto com as quantidades cadastradas
$sql_ingredientes_receita = "
    SELECT ri.id_ingrediente, ri.quantidade
    FROM receita_ingredientes ri
    WHERE ri.id_receita = ?
";
$stmt_ing = $conn->prepare($sql_ingredientes_receita);
$stmt_ing->bind_param("i", $id_receita);
$stmt_ing->execute();
$result_ing = $stmt_ing->get_result();

// Cria um array para armazenar os ingredientes e quantidades atuais da receita
$ingredientes_receita = [];
while ($row = $result_ing->fetch_assoc()) {
    // chave é o id do ingrediente e valor é a quantidade
    $ingredientes_receita[$row['id_ingrediente']] = $row['quantidade'];
}

// Se o formulário for enviado pelo método POST, ou seja, o usuário clicou em salvar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados enviados no formulário
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $tempo = $_POST['tempo_preparo'];
    $rendimento = $_POST['rendimento'];
    $instrucoes = $_POST['instrucoes'];
    // Array que vem com id_ingrediente => quantidade (ingredientes que usuário editou)
    $ingredientesSelecionados = $_POST['ingredientes'];

    // Prepara a query para atualizar os dados da receita no banco
    $sql_update = "
        UPDATE receitas SET
            nome = ?,
            categoria = ?,
            tempo_preparo = ?,
            rendimento = ?,
            instrucoes = ?
        WHERE id_receita = ?
    ";
    $stmt_update = $conn->prepare($sql_update);
    // Liga os parâmetros: s = string, i = inteiro
    $stmt_update->bind_param("ssissi", $nome, $categoria, $tempo, $rendimento, $instrucoes, $id_receita);
    $stmt_update->execute(); // Executa o update

    // Apaga os ingredientes antigos vinculados para inserir os novos depois
    $stmt_delete = $conn->prepare("DELETE FROM receita_ingredientes WHERE id_receita = ?");
    $stmt_delete->bind_param("i", $id_receita);
    $stmt_delete->execute();

    // Agora insere novamente os ingredientes atualizados conforme o formulário
    foreach ($ingredientesSelecionados as $id_ingrediente => $quantidade) {
        // Só insere se a quantidade não estiver vazia (evita inserir ingrediente sem quantidade)
        if (!empty($quantidade)) {
            $stmt_ing_ins = $conn->prepare("INSERT INTO receita_ingredientes (id_receita, id_ingrediente, quantidade) VALUES (?, ?, ?)");
            $stmt_ing_ins->bind_param("iid", $id_receita, $id_ingrediente, $quantidade);
            $stmt_ing_ins->execute();
        }
    }

    // Mostra mensagem de sucesso na tela
   echo "<p class='mensagem-sucesso'>Receita atualizada com sucesso!</p>";

    // Atualiza as variáveis para manter os valores no formulário após salvar
    $receita['nome'] = $nome;
    $receita['categoria'] = $categoria;
    $receita['tempo_preparo'] = $tempo;
    $receita['rendimento'] = $rendimento;
    $receita['instrucoes'] = $instrucoes;
    $ingredientes_receita = $ingredientesSelecionados;
}
?>
<!--html-->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Editar Receita</title>
  <link rel="stylesheet" href="styles/editar.css">
</head>
<body>
<!--incluir o cabecalho-->
  <?php include 'header.php'; ?>

<h1>Editar Receita</h1>
<!-- Formulário para editar a receita -->
<form method="POST">

  <label>Nome da Receita:</label>
  <!-- Campo texto já preenchido com o nome atual da receita -->
  <input type="text" name="nome" value="<?= htmlspecialchars($receita['nome']) ?>" required />

  <label>Categoria:</label>
  <select name="categoria" required>
    <option value="">Selecione...</option>
    <!-- Marca como selecionado o valor que a receita tem no banco -->
    <option value="doce" <?= $receita['categoria'] == 'doce' ? 'selected' : '' ?>>Doce</option>
    <option value="salgado" <?= $receita['categoria'] == 'salgado' ? 'selected' : '' ?>>Salgado</option>
    <option value="bebida" <?= $receita['categoria'] == 'bebida' ? 'selected' : '' ?>>Bebida</option>
  </select>

  <label>Tempo de Preparo (min):</label>
  <input type="number" name="tempo_preparo" value="<?= $receita['tempo_preparo'] ?>" required />

  <label>Rendimento:</label>
  <input type="text" name="rendimento" value="<?= htmlspecialchars($receita['rendimento']) ?>" required />

  <label>Instruções de Preparo:</label>
  <!-- Textarea preenchida com o texto atual das instruções -->
  <textarea name="instrucoes" rows="4" required><?= htmlspecialchars($receita['instrucoes']) ?></textarea>

  <label>Ingredientes:</label>
  <!-- Loop para listar todos os ingredientes com input para quantidade -->
  <?php while ($ing = $ingredientes->fetch_assoc()): ?>
    <div class="ingrediente">
      <?= htmlspecialchars($ing['nome']) ?> (<?= $ing['unidade_medida'] ?>):
      <!-- Input que mostra a quantidade já cadastrada para aquele ingrediente ou vazio -->
      <input
        type="number"
        step="0.1"
        name="ingredientes[<?= $ing['id_ingrediente'] ?>]"
        placeholder="Quantidade"
        value="<?= isset($ingredientes_receita[$ing['id_ingrediente']]) ? $ingredientes_receita[$ing['id_ingrediente']] : '' ?>"
      />
    </div>
  <?php endwhile; ?>

  <button type="submit">Salvar Alterações</button>
</form>

<!-- Link de voltar -->
<div style="text-align: center;">
  <a href="index.php" class="btn">
    ⬅ Voltar para lista de receitas
  </a>
</div>
</body>
</html>
