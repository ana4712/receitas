<?php
require_once 'conexao.php'; // Conexão com o banco

// Pega todos os ingredientes para mostrar no formulário
$ingredientes = $conn->query("SELECT * FROM ingredientes");

// Quando o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $tempo = $_POST['tempo_preparo'];
    $rendimento = $_POST['rendimento'];
    $instrucoes = $_POST['instrucoes'];
    $ingredientesSelecionados = $_POST['ingredientes']; // array com id => quantidade

    // Insere a nova receita na tabela
    $stmt = $conn->prepare("INSERT INTO receitas (nome, categoria, tempo_preparo, rendimento, instrucoes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nome, $categoria, $tempo, $rendimento, $instrucoes);
    $stmt->execute();

    // Pega o ID da receita que acabou de ser inserida
    $id_receita = $stmt->insert_id;

    // Para cada ingrediente selecionado, insere a relação na tabela intermediária
    foreach ($ingredientesSelecionados as $id_ingrediente => $quantidade) {
        if (!empty($quantidade)) {
            $stmt_ing = $conn->prepare("INSERT INTO receita_ingredientes (id_receita, id_ingrediente, quantidade) VALUES (?, ?, ?)");
            $stmt_ing->bind_param("iid", $id_receita, $id_ingrediente, $quantidade);
            $stmt_ing->execute();
        }
    }

    // Mensagem de sucesso
    echo "<p class='mensagem-sucesso'>Receita cadastrada com sucesso!</p>";

}
?>
<!--html-->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Receita</title>
  <link rel="stylesheet" href="styles/cadastrar.css">
</head>
<body>
  <!--incluir o cabecalho-->
  <?php include 'header.php'; ?>

<h1>Nova Receita</h1>

<!-- Formulário de cadastro -->
<form method="POST">
  <label for="nome">Nome da Receita:</label>
  <input type="text" name="nome" required>

  <label for="categoria">Categoria:</label>
  <select name="categoria" required>
    <option value="">Selecione...</option>
    <option value="doce">Doce</option>
    <option value="salgado">Salgado</option>
    <option value="bebida">Bebida</option>
  </select>

  <label for="tempo_preparo">Tempo de Preparo (min):</label>
  <input type="number" name="tempo_preparo" required>

  <label for="rendimento">Rendimento:</label>
  <input type="text" name="rendimento" required>

  <label for="instrucoes">Instruções de Preparo:</label>
  <textarea name="instrucoes" rows="4" required></textarea>

  <!-- Lista de ingredientes com campos para quantidade -->
  <label>Ingredientes:</label>
  <?php while($ing = $ingredientes->fetch_assoc()): ?>
    <div class="ingrediente">
      <?= htmlspecialchars($ing['nome']) ?> (<?= $ing['unidade_medida'] ?>):
      <input type="number" step="0.1" name="ingredientes[<?= $ing['id_ingrediente'] ?>]" placeholder="Quantidade">
    </div>
  <?php endwhile; ?>

  <button type="submit">Cadastrar Receita</button>
</form>

<!-- Link de voltar -->
<div style="text-align: center;">
  <a href="index.php" class="btn">
    ⬅ Voltar para lista de receitas
  </a>
</div>
</body>
</html>
