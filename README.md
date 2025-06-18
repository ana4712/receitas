# 🍴 Sistema de Gerenciamento de Receitas Culinárias

Sistema web para **cadastro e gerenciamento de receitas culinárias**, desenvolvido com **PHP e MySQL**. Este projeto foi criado como parte do curso Técnico em Desenvolvimento de Sistemas, com o objetivo de praticar a manipulação de dados por meio de operações CRUD (Create, Read, Update, Delete).

## 🚀 Funcionalidades

- ➕ Cadastrar nova receita com:
  - Nome
  - Categoria (doce, salgado, bebida)
  - Tempo de preparo
  - Rendimento
  - Instruções
  - Ingredientes com quantidades
- 📋 Listar receitas em cards organizados
- ✏️ Editar e 🗑️ excluir receitas
- 📦 Visualizar ingredientes utilizados em cada receita
- 📷 Interface com imagem padrão para cada receita

## 🛠️ Tecnologias Utilizadas

- **HTML5**: Estrutura das páginas
- **CSS3**: Estilização do layout com uso de `@import` em `global.css`
- **PHP**: Lógica do sistema e comunicação com banco de dados
- **MySQL**: Banco de dados relacional
- **XAMPP**: Ambiente local com Apache e MySQL
- **VS Code**: Editor de código com suporte para as tecnologias utilizadas

## ▶️ Como executar o projeto

1. Clone ou copie este repositório para a sua máquina.
2. Certifique-se de que possui o XAMPP (ou outro servidor local com PHP e MySQL) instalado.
3. Copie a pasta do projeto para o diretório `htdocs` do XAMPP.
4. Crie o banco de dados `receitas` e execute os comandos SQL do arquivo `receita.sql` para gerar as tabelas.
5. Inicie o servidor Apache e o MySQL pelo painel do XAMPP.

### 🔗 Acesse no navegador:
[http://localhost/receitas](http://localhost/receitas)

## 📌 Melhorias previstas

- Upload de imagem personalizada por receita
- Filtro por categoria (doces, salgados, bebidas)
- Busca por nome de receita
