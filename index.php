<?php
require_once 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Consulta todas as receitas cadastradas no banco, ordenadas por nome 
$sql = "SELECT * FROM receitas ORDER BY nome ASC";
$result = $conn->query($sql); // Executa a consulta e armazena o resultado
?>

<!--html-->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciador de Receitas</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <!--incluir o cabecalho-->
  <?php include 'header.php'; ?>

<h1>Receitas Culinárias</h1>
<div style="text-align: center; margin: 20px 0;">
  <a href="cadastrar.php" style="
    text-decoration: none;
    background-color: rgb(23, 139, 19);
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 16px;
  ">Cadastrar Nova Receita</a>
</div>

<!-- Container em grade para exibir as receitas -->
<div class="grid">

<!-- Loop para mostrar cada receita vinda do banco de dados -->
<?php while($receita = $result->fetch_assoc()): ?>

    <!-- Cada receita será exibida dentro de um card -->
  <div class="card">
    <div class="card-content">
    
      <!-- Nome da receita -->
      <h2><?= htmlspecialchars($receita['nome']) ?></h2>
      
      <!-- Informações básicas -->
      <p><strong>Categoria:</strong> <?= htmlspecialchars($receita['categoria']) ?></p>
      <p><strong>Tempo de preparo:</strong> <?= $receita['tempo_preparo'] ?> min</p>
      <p><strong>Rendimento:</strong> <?= htmlspecialchars($receita['rendimento']) ?></p>
      
      <!-- Instruções da receita, com quebra de linha preservada -->
      <p><strong>Instruções:</strong><br><?= nl2br(htmlspecialchars($receita['instrucoes'])) ?></p>

      <!-- Lista de ingredientes da receita -->
      <div class="ingredientes">
        <strong>Ingredientes:</strong>
        <ul>

          <?php
            // Pega o ID da receita atual
            $id_receita = $receita['id_receita'];

            // Consulta os ingredientes dessa receita
            $sql_ingredientes = "
              SELECT i.nome, ri.quantidade, i.unidade_medida
              FROM receita_ingredientes ri
              JOIN ingredientes i ON ri.id_ingrediente = i.id_ingrediente
              WHERE ri.id_receita = $id_receita
            ";

            // Executa a consulta dos ingredientes
            $result_ingredientes = $conn->query($sql_ingredientes);

            // Loop para mostrar cada ingrediente
            while($ing = $result_ingredientes->fetch_assoc()):
          ?>

          <!-- Exibe a quantidade, unidade de medida e nome do ingrediente -->
            <li><?= $ing['quantidade'] . " " . $ing['unidade_medida'] . " de " . htmlspecialchars($ing['nome']) ?></li>
          <?php endwhile; ?>
        </ul>
      </div>
            <!-- Botões de ação (editar e excluir) -->
      <div class="botoes">
        <a href="editar.php?id=<?= $receita['id_receita'] ?>">Editar</a>
        <a href="excluir.php?id=<?= $receita['id_receita'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
      </div>

    </div> <!-- Fim do conteúdo do card -->
  </div> <!-- Fim do card -->
<?php endwhile; ?> <!-- Fim do loop de receitas -->
</div> <!-- Fim  -->
</body>
</html>

