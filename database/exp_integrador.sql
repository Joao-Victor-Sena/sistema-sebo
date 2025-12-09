-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/12/2025 às 02:07
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `integrador`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `CD_CLIENTE` int(11) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `TELEFONE` varchar(20) DEFAULT NULL,
  `CPF` varchar(14) NOT NULL,
  `EMAIL` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`CD_CLIENTE`, `NOME`, `TELEFONE`, `CPF`, `EMAIL`) VALUES
(100, 'Consumidor Final', '00000000000', '00000000000', 'caixa@sebolinhas.com'),
(101, 'Maria Silva', '11999998888', '12345678900', 'maria@email.com'),
(102, 'João Santos', '11988887777', '98765432100', 'joao@email.com'),
(103, 'Fernanda Oliveira', '21977776666', '45678912300', 'fernanda@email.com'),
(104, 'Lucas Martins', '31966665555', '78912345600', 'lucas@email.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compra`
--

CREATE TABLE `compra` (
  `CD_COMPRA` int(11) NOT NULL,
  `DATA` date NOT NULL,
  `HORA` time NOT NULL,
  `CD_CLIENTE` int(11) DEFAULT NULL,
  `TOTAL` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `compra`
--

INSERT INTO `compra` (`CD_COMPRA`, `DATA`, `HORA`, `CD_CLIENTE`, `TOTAL`) VALUES
(1, '2023-10-01', '10:30:00', 101, 79.90),
(2, '2023-10-02', '14:15:00', 102, 49.90),
(3, '2023-10-03', '16:45:00', 103, 70.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `compra_livro`
--

CREATE TABLE `compra_livro` (
  `CD_COMPRA` int(11) NOT NULL,
  `CD_LIVRO` int(11) NOT NULL,
  `QUANTIDADE` int(11) NOT NULL DEFAULT 1,
  `VALOR_UNITARIO` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `compra_livro`
--

INSERT INTO `compra_livro` (`CD_COMPRA`, `CD_LIVRO`, `QUANTIDADE`, `VALOR_UNITARIO`) VALUES
(1, 1000, 1, 34.90),
(1, 1002, 1, 45.00),
(2, 1020, 1, 49.90),
(3, 1044, 1, 35.00),
(3, 1045, 1, 35.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `CD_FUNCIONARIO` int(11) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `CPF` varchar(14) NOT NULL,
  `FUNCAO` varchar(20) NOT NULL,
  `SENHA` varchar(255) NOT NULL DEFAULT '1234'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`CD_FUNCIONARIO`, `NOME`, `CPF`, `FUNCAO`, `SENHA`) VALUES
(1, 'Administrador', '00000000000', 'Gerente', 'admin'),
(2, 'Vendedor Padrão', '11111111111', 'Vendedor', '1234'),
(3, 'Carlos Souza', '22222222222', 'Estoquista', '1234'),
(4, 'Ana Pereira', '33333333333', 'Caixa', '1234'),
(5, 'Roberto Lima', '44444444444', 'Vendedor', '1234');

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `CD_LIVRO` int(11) NOT NULL,
  `TITULO` varchar(150) NOT NULL,
  `ANO` int(11) NOT NULL,
  `AUTOR` varchar(100) NOT NULL,
  `ESTADO` varchar(20) NOT NULL,
  `PRECO` decimal(10,2) NOT NULL DEFAULT 0.00,
  `CD_FUNCIONARIO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`CD_LIVRO`, `TITULO`, `ANO`, `AUTOR`, `ESTADO`, `PRECO`, `CD_FUNCIONARIO`) VALUES
(1000, 'Dom Casmurro', 1998, 'Machado de Assis', 'Usado', 34.90, 1),
(1001, 'Memórias Póstumas de Brás Cubas', 2015, 'Machado de Assis', 'Bom', 29.90, 1),
(1002, 'O Cortiço', 2020, 'Aluísio Azevedo', 'Novo', 45.00, 1),
(1003, 'Vidas Secas', 1985, 'Graciliano Ramos', 'Seminovo', 38.50, 1),
(1004, 'Grande Sertão: Veredas', 2019, 'João Guimarães Rosa', 'Novo', 89.90, 1),
(1005, 'A Hora da Estrela', 1998, 'Clarice Lispector', 'Bom', 32.00, 1),
(1006, 'Capitães da Areia', 2011, 'Jorge Amado', 'Usado', 25.00, 1),
(1007, 'Iracema', 1975, 'José de Alencar', 'Velho', 15.00, 1),
(1008, 'Macunaíma', 2002, 'Mário de Andrade', 'Bom', 28.90, 1),
(1009, 'O Guarani', 1982, 'José de Alencar', 'Usado', 22.00, 1),
(1010, 'O Alienista', 2021, 'Machado de Assis', 'Novo', 35.00, 1),
(1011, 'A Moreninha', 1995, 'Joaquim Manuel de Macedo', 'Seminovo', 30.00, 1),
(1012, 'Sagarana', 2018, 'João Guimarães Rosa', 'Novo', 55.00, 1),
(1013, 'Fogo Morto', 1992, 'José Lins do Rego', 'Bom', 42.00, 1),
(1014, 'Auto da Compadecida', 2014, 'Ariano Suassuna', 'Novo', 48.90, 1),
(1015, 'Quincas Borba', 1988, 'Machado de Assis', 'Usado', 20.00, 1),
(1016, 'Triste Fim de Policarpo Quaresma', 2005, 'Lima Barreto', 'Bom', 27.50, 1),
(1017, 'Gabriela, Cravo e Canela', 2010, 'Jorge Amado', 'Seminovo', 36.00, 1),
(1018, 'Dona Flor e Seus Dois Maridos', 1996, 'Jorge Amado', 'Usado', 33.00, 1),
(1019, 'O Tempo e o Vento', 2022, 'Erico Verissimo', 'Novo', 120.00, 1),
(1020, '1984', 2009, 'George Orwell', 'Novo', 49.90, 1),
(1021, 'A Revolução dos Bichos', 2005, 'George Orwell', 'Seminovo', 29.90, 1),
(1022, 'Dom Quixote', 1980, 'Miguel de Cervantes', 'Usado', 65.00, 1),
(1023, 'Orgulho e Preconceito', 2018, 'Jane Austen', 'Novo', 39.90, 1),
(1024, 'Moby Dick', 2012, 'Herman Melville', 'Bom', 55.00, 1),
(1025, 'O Grande Gatsby', 2010, 'F. Scott Fitzgerald', 'Seminovo', 32.50, 1),
(1026, 'Cem Anos de Solidão', 1995, 'Gabriel García Márquez', 'Novo', 58.00, 1),
(1027, 'Crime e Castigo', 2001, 'Fiódor Dostoiévski', 'Usado', 45.00, 1),
(1028, 'Os Miseráveis', 1985, 'Victor Hugo', 'Bom', 75.00, 1),
(1029, 'A Metamorfose', 1999, 'Franz Kafka', 'Novo', 25.00, 1),
(1030, 'O Pequeno Príncipe', 2015, 'Antoine de Saint-Exupéry', 'Novo', 22.00, 1),
(1031, 'O Conde de Monte Cristo', 2011, 'Alexandre Dumas', 'Bom', 85.00, 1),
(1032, 'Drácula', 2020, 'Bram Stoker', 'Seminovo', 42.00, 1),
(1033, 'Frankenstein', 2017, 'Mary Shelley', 'Usado', 28.00, 1),
(1034, 'O Retrato de Dorian Gray', 2013, 'Oscar Wilde', 'Novo', 34.00, 1),
(1035, 'Guerra e Paz', 2016, 'Liev Tolstói', 'Bom', 95.00, 1),
(1036, 'A Divina Comédia', 1978, 'Dante Alighieri', 'Usado', 50.00, 1),
(1037, 'Hamlet', 2004, 'William Shakespeare', 'Seminovo', 26.00, 1),
(1038, 'Romeu e Julieta', 2008, 'William Shakespeare', 'Novo', 28.00, 1),
(1039, 'A Odisséia', 2014, 'Homero', 'Bom', 48.00, 1),
(1040, 'O Senhor dos Anéis: A Sociedade do Anel', 2019, 'J.R.R. Tolkien', 'Novo', 69.90, 1),
(1041, 'O Senhor dos Anéis: As Duas Torres', 2019, 'J.R.R. Tolkien', 'Novo', 69.90, 1),
(1042, 'O Senhor dos Anéis: O Retorno do Rei', 2019, 'J.R.R. Tolkien', 'Novo', 69.90, 1),
(1043, 'O Hobbit', 2012, 'J.R.R. Tolkien', 'Seminovo', 45.00, 1),
(1044, 'Harry Potter e a Pedra Filosofal', 2001, 'J.K. Rowling', 'Usado', 35.00, 1),
(1045, 'Harry Potter e a Câmara Secreta', 2002, 'J.K. Rowling', 'Usado', 35.00, 1),
(1046, 'A Guerra dos Tronos', 2010, 'George R.R. Martin', 'Bom', 55.00, 1),
(1047, 'Duna', 2021, 'Frank Herbert', 'Novo', 78.00, 1),
(1048, 'Neuromancer', 2015, 'William Gibson', 'Seminovo', 42.00, 1),
(1049, 'Fundação', 2009, 'Isaac Asimov', 'Bom', 48.00, 1),
(1050, 'Eu, Robô', 2014, 'Isaac Asimov', 'Usado', 29.00, 1),
(1051, 'Fahrenheit 451', 2012, 'Ray Bradbury', 'Novo', 38.00, 1),
(1052, 'Admirável Mundo Novo', 2014, 'Aldous Huxley', 'Seminovo', 36.00, 1),
(1053, 'O Guia do Mochileiro das Galáxias', 2010, 'Douglas Adams', 'Novo', 32.00, 1),
(1054, 'Jogos Vorazes', 2011, 'Suzanne Collins', 'Bom', 35.00, 1),
(1055, 'Percy Jackson e o Ladrão de Raios', 2010, 'Rick Riordan', 'Usado', 28.00, 1),
(1056, 'As Crônicas de Nárnia', 2009, 'C.S. Lewis', 'Novo', 85.00, 1),
(1057, 'O Nome do Vento', 2009, 'Patrick Rothfuss', 'Bom', 58.00, 1),
(1058, 'Sandman: Prelúdio', 2015, 'Neil Gaiman', 'Novo', 65.00, 1),
(1059, 'Deuses Americanos', 2016, 'Neil Gaiman', 'Seminovo', 45.00, 1),
(1060, 'Clean Code', 2009, 'Robert C. Martin', 'Novo', 98.00, 1),
(1061, 'O Codificador Limpo', 2012, 'Robert C. Martin', 'Novo', 85.00, 1),
(1062, 'Arquitetura Limpa', 2018, 'Robert C. Martin', 'Novo', 92.00, 1),
(1063, 'Design Patterns', 2000, 'Gang of Four', 'Bom', 120.00, 1),
(1064, 'Entendendo Algoritmos', 2017, 'Aditya Bhargava', 'Seminovo', 55.00, 1),
(1065, 'Sapiens: Uma Breve História da Humanidade', 2018, 'Yuval Noah Harari', 'Novo', 59.90, 1),
(1066, 'Homo Deus', 2016, 'Yuval Noah Harari', 'Bom', 54.90, 1),
(1067, 'Rápido e Devagar', 2012, 'Daniel Kahneman', 'Usado', 48.00, 1),
(1068, 'O Poder do Hábito', 2013, 'Charles Duhigg', 'Seminovo', 42.00, 1),
(1069, 'Pai Rico, Pai Pobre', 2017, 'Robert Kiyosaki', 'Usado', 35.00, 1),
(1070, 'A Arte da Guerra', 2005, 'Sun Tzu', 'Novo', 25.00, 1),
(1071, 'Mindset', 2017, 'Carol S. Dweck', 'Bom', 39.00, 1),
(1072, 'Essencialismo', 2015, 'Greg McKeown', 'Novo', 42.00, 1),
(1073, 'Comece pelo Porquê', 2018, 'Simon Sinek', 'Seminovo', 38.00, 1),
(1074, 'O Jeito Disney de Encantar Clientes', 2012, 'Disney Institute', 'Novo', 32.00, 1),
(1075, 'Scrum: A Arte de Fazer o Dobro do Trabalho na Metade do Tempo', 2016, 'Jeff Sutherland', 'Bom', 36.00, 1),
(1076, 'A Lógica do Cisne Negro', 2008, 'Nassim Taleb', 'Usado', 55.00, 1),
(1077, 'Antifrágil', 2013, 'Nassim Taleb', 'Novo', 68.00, 1),
(1078, 'Os Segredos da Mente Milionária', 2006, 'T. Harv Eker', 'Velho', 20.00, 1),
(1079, 'Como Fazer Amigos e Influenciar Pessoas', 2019, 'Dale Carnegie', 'Bom', 35.00, 1),
(1080, 'O Código Da Vinci', 2004, 'Dan Brown', 'Seminovo', 28.00, 1),
(1081, 'Anjos e Demônios', 2005, 'Dan Brown', 'Usado', 25.00, 1),
(1082, 'Inferno', 2013, 'Dan Brown', 'Bom', 32.00, 1),
(1083, 'A Menina que Roubava Livros', 2007, 'Markus Zusak', 'Usado', 29.00, 1),
(1084, 'O Caçador de Pipas', 2006, 'Khaled Hosseini', 'Seminovo', 34.00, 1),
(1085, 'A Culpa é das Estrelas', 2012, 'John Green', 'Bom', 26.00, 1),
(1086, 'Crepúsculo', 2008, 'Stephenie Meyer', 'Usado', 20.00, 1),
(1087, 'Lua Nova', 2008, 'Stephenie Meyer', 'Usado', 20.00, 1),
(1088, 'Eclipse', 2009, 'Stephenie Meyer', 'Usado', 20.00, 1),
(1089, 'Amanhecer', 2010, 'Stephenie Meyer', 'Usado', 20.00, 1),
(1090, 'Cinquenta Tons de Cinza', 2012, 'E.L. James', 'Bom', 22.00, 1),
(1091, 'It: A Coisa', 2014, 'Stephen King', 'Novo', 85.00, 1),
(1092, 'O Iluminado', 2012, 'Stephen King', 'Seminovo', 48.00, 1),
(1093, 'Carrie, a Estranha', 2013, 'Stephen King', 'Usado', 32.00, 1),
(1094, 'Misery', 2014, 'Stephen King', 'Novo', 45.00, 1),
(1095, 'A Torre Negra I', 2004, 'Stephen King', 'Bom', 52.00, 1),
(1096, 'Bird Box', 2015, 'Josh Malerman', 'Novo', 36.00, 1),
(1097, 'Garota Exemplar', 2013, 'Gillian Flynn', 'Seminovo', 38.00, 1),
(1098, 'Os Homens que Não Amavam as Mulheres', 2008, 'Stieg Larsson', 'Usado', 29.00, 1),
(1099, 'Extraordinário', 2013, 'R.J. Palacio', 'Novo', 32.00, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`CD_CLIENTE`),
  ADD UNIQUE KEY `U_CPF` (`CPF`);

--
-- Índices de tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`CD_COMPRA`),
  ADD KEY `CD_CLIENTE` (`CD_CLIENTE`);

--
-- Índices de tabela `compra_livro`
--
ALTER TABLE `compra_livro`
  ADD PRIMARY KEY (`CD_COMPRA`,`CD_LIVRO`),
  ADD KEY `CD_LIVRO` (`CD_LIVRO`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`CD_FUNCIONARIO`),
  ADD UNIQUE KEY `U_CPF_F` (`CPF`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`CD_LIVRO`),
  ADD KEY `CD_FUNCIONARIO` (`CD_FUNCIONARIO`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `CD_CLIENTE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `CD_COMPRA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `CD_FUNCIONARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `CD_LIVRO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1100;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`CD_CLIENTE`) REFERENCES `cliente` (`CD_CLIENTE`);

--
-- Restrições para tabelas `compra_livro`
--
ALTER TABLE `compra_livro`
  ADD CONSTRAINT `compra_livro_ibfk_1` FOREIGN KEY (`CD_COMPRA`) REFERENCES `compra` (`CD_COMPRA`) ON DELETE CASCADE,
  ADD CONSTRAINT `compra_livro_ibfk_2` FOREIGN KEY (`CD_LIVRO`) REFERENCES `livro` (`CD_LIVRO`);

--
-- Restrições para tabelas `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`CD_FUNCIONARIO`) REFERENCES `funcionario` (`CD_FUNCIONARIO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
