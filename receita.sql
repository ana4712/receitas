CREATE DATABASE receita;
use receita;

-- tabela receitas
CREATE TABLE receitas (
  id_receita INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  categoria ENUM('doce','salgado','bebida') NOT NULL,
  tempo_preparo INT NOT NULL,
  rendimento VARCHAR(50) NOT NULL,
  instrucoes TEXT NOT NULL
);
-- tabela ingredientes
CREATE TABLE ingredientes (
  id_ingrediente INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  unidade_medida VARCHAR(20) NOT NULL
);
-- tabela intermediaria
CREATE TABLE receita_ingredientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_receita INT NOT NULL,
  id_ingrediente INT NOT NULL,
  quantidade DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_receita) REFERENCES receitas(id_receita) ON DELETE CASCADE,
  FOREIGN KEY (id_ingrediente) REFERENCES ingredientes(id_ingrediente) ON DELETE CASCADE
);

-- Esse comando muda a senha do usuário root para 1234 e aplica imediatamente essa alteração no MySQL.
ALTER USER 'root'@'localhost' IDENTIFIED BY '1234';
FLUSH PRIVILEGES;

INSERT INTO ingredientes (nome, unidade_medida) VALUES
('Açúcar', 'g'),
('Farinha', 'g'),
('Leite', 'ml'),
('Ovos', 'unidade'),
('Manteiga', 'g');

