-- SCRIPT COMPLETO DE RECRIACÃO DO BANCO SEBOLINHAS (CORRIGIDO)
-- Estrutura + Dados Básicos + 100 Livros de Exemplo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. ESTRUTURA (DDL)
DROP DATABASE IF EXISTS Integrador;
CREATE DATABASE Integrador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Integrador;

-- Tabela de Funcionários
CREATE TABLE FUNCIONARIO (
    CD_FUNCIONARIO INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    CPF VARCHAR(14) NOT NULL,
    FUNCAO VARCHAR(20) NOT NULL,
    SENHA VARCHAR(255) NOT NULL DEFAULT '1234',
    CONSTRAINT U_CPF_F UNIQUE (CPF)
);

-- Tabela de Clientes
CREATE TABLE CLIENTE (
    CD_CLIENTE INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    TELEFONE VARCHAR(20) DEFAULT NULL,
    CPF VARCHAR(14) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL,
    CONSTRAINT U_CPF UNIQUE (CPF)
) AUTO_INCREMENT=100;

-- Tabela de Livros
CREATE TABLE LIVRO (
    CD_LIVRO INT AUTO_INCREMENT PRIMARY KEY,
    TITULO VARCHAR(150) NOT NULL,
    ANO INT NOT NULL,
    AUTOR VARCHAR(100) NOT NULL,
    ESTADO VARCHAR(20) NOT NULL,
    PRECO DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    CD_FUNCIONARIO INT,
    FOREIGN KEY (CD_FUNCIONARIO) REFERENCES FUNCIONARIO(CD_FUNCIONARIO)
) AUTO_INCREMENT=1000;

-- Tabela de Compras (Vendas)
CREATE TABLE COMPRA (
    CD_COMPRA INT AUTO_INCREMENT PRIMARY KEY,
    DATA DATE NOT NULL,
    HORA TIME NOT NULL,
    CD_CLIENTE INT,
    TOTAL DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (CD_CLIENTE) REFERENCES CLIENTE(CD_CLIENTE)
);

-- Tabela de Itens da Compra (Detalhes)
CREATE TABLE COMPRA_LIVRO (
    CD_COMPRA INT,
    CD_LIVRO INT,
    QUANTIDADE INT NOT NULL DEFAULT 1,
    VALOR_UNITARIO DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (CD_COMPRA, CD_LIVRO),
    FOREIGN KEY (CD_COMPRA) REFERENCES COMPRA(CD_COMPRA) ON DELETE CASCADE,
    FOREIGN KEY (CD_LIVRO) REFERENCES LIVRO(CD_LIVRO)
);

-- 2. DADOS ESSENCIAIS (SEED)

-- Funcionários (Garante que o ID 1 exista para vincular os livros)
INSERT INTO FUNCIONARIO (NOME, CPF, FUNCAO, SENHA) VALUES 
('Administrador', '00000000000', 'Gerente', 'admin'),
('Vendedor Padrão', '11111111111', 'Vendedor', '1234');

-- Clientes de Teste
INSERT INTO CLIENTE (NOME, CPF, EMAIL, TELEFONE) VALUES 
('Consumidor Final', '00000000000', 'caixa@sebolinhas.com', '00000000000'),
('Maria Silva', '12345678900', 'maria@email.com', '11999998888');

-- 3. O ACERVO (100 Livros)
-- CORREÇÃO AQUI: A ordem das colunas agora é CD_FUNCIONARIO, PRECO para bater com os valores (1, 34.90)
INSERT INTO LIVRO (TITULO, ANO, AUTOR, ESTADO, CD_FUNCIONARIO, PRECO) VALUES 
-- LITERATURA BRASILEIRA
('Dom Casmurro', 1998, 'Machado de Assis', 'Usado', 1, 34.90),
('Memórias Póstumas de Brás Cubas', 2015, 'Machado de Assis', 'Bom', 1, 29.90),
('O Cortiço', 2020, 'Aluísio Azevedo', 'Novo', 1, 45.00),
('Vidas Secas', 1985, 'Graciliano Ramos', 'Seminovo', 1, 38.50),
('Grande Sertão: Veredas', 2019, 'João Guimarães Rosa', 'Novo', 1, 89.90),
('A Hora da Estrela', 1998, 'Clarice Lispector', 'Bom', 1, 32.00),
('Capitães da Areia', 2011, 'Jorge Amado', 'Usado', 1, 25.00),
('Iracema', 1975, 'José de Alencar', 'Velho', 1, 15.00),
('Macunaíma', 2002, 'Mário de Andrade', 'Bom', 1, 28.90),
('O Guarani', 1982, 'José de Alencar', 'Usado', 1, 22.00),
('O Alienista', 2021, 'Machado de Assis', 'Novo', 1, 35.00),
('A Moreninha', 1995, 'Joaquim Manuel de Macedo', 'Seminovo', 1, 30.00),
('Sagarana', 2018, 'João Guimarães Rosa', 'Novo', 1, 55.00),
('Fogo Morto', 1992, 'José Lins do Rego', 'Bom', 1, 42.00),
('Auto da Compadecida', 2014, 'Ariano Suassuna', 'Novo', 1, 48.90),
('Quincas Borba', 1988, 'Machado de Assis', 'Usado', 1, 20.00),
('Triste Fim de Policarpo Quaresma', 2005, 'Lima Barreto', 'Bom', 1, 27.50),
('Gabriela, Cravo e Canela', 2010, 'Jorge Amado', 'Seminovo', 1, 36.00),
('Dona Flor e Seus Dois Maridos', 1996, 'Jorge Amado', 'Usado', 1, 33.00),
('O Tempo e o Vento', 2022, 'Erico Verissimo', 'Novo', 1, 120.00),

-- CLÁSSICOS MUNDIAIS
('1984', 2009, 'George Orwell', 'Novo', 1, 49.90),
('A Revolução dos Bichos', 2005, 'George Orwell', 'Seminovo', 1, 29.90),
('Dom Quixote', 1980, 'Miguel de Cervantes', 'Usado', 1, 65.00),
('Orgulho e Preconceito', 2018, 'Jane Austen', 'Novo', 1, 39.90),
('Moby Dick', 2012, 'Herman Melville', 'Bom', 1, 55.00),
('O Grande Gatsby', 2010, 'F. Scott Fitzgerald', 'Seminovo', 1, 32.50),
('Cem Anos de Solidão', 1995, 'Gabriel García Márquez', 'Novo', 1, 58.00),
('Crime e Castigo', 2001, 'Fiódor Dostoiévski', 'Usado', 1, 45.00),
('Os Miseráveis', 1985, 'Victor Hugo', 'Bom', 1, 75.00),
('A Metamorfose', 1999, 'Franz Kafka', 'Novo', 1, 25.00),
('O Pequeno Príncipe', 2015, 'Antoine de Saint-Exupéry', 'Novo', 1, 22.00),
('O Conde de Monte Cristo', 2011, 'Alexandre Dumas', 'Bom', 1, 85.00),
('Drácula', 2020, 'Bram Stoker', 'Seminovo', 1, 42.00),
('Frankenstein', 2017, 'Mary Shelley', 'Usado', 1, 28.00),
('O Retrato de Dorian Gray', 2013, 'Oscar Wilde', 'Novo', 1, 34.00),
('Guerra e Paz', 2016, 'Liev Tolstói', 'Bom', 1, 95.00),
('A Divina Comédia', 1978, 'Dante Alighieri', 'Usado', 1, 50.00),
('Hamlet', 2004, 'William Shakespeare', 'Seminovo', 1, 26.00),
('Romeu e Julieta', 2008, 'William Shakespeare', 'Novo', 1, 28.00),
('A Odisséia', 2014, 'Homero', 'Bom', 1, 48.00),

-- FANTASIA E SCI-FI
('O Senhor dos Anéis: A Sociedade do Anel', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
('O Senhor dos Anéis: As Duas Torres', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
('O Senhor dos Anéis: O Retorno do Rei', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
('O Hobbit', 2012, 'J.R.R. Tolkien', 'Seminovo', 1, 45.00),
('Harry Potter e a Pedra Filosofal', 2001, 'J.K. Rowling', 'Usado', 1, 35.00),
('Harry Potter e a Câmara Secreta', 2002, 'J.K. Rowling', 'Usado', 1, 35.00),
('A Guerra dos Tronos', 2010, 'George R.R. Martin', 'Bom', 1, 55.00),
('Duna', 2021, 'Frank Herbert', 'Novo', 1, 78.00),
('Neuromancer', 2015, 'William Gibson', 'Seminovo', 1, 42.00),
('Fundação', 2009, 'Isaac Asimov', 'Bom', 1, 48.00),
('Eu, Robô', 2014, 'Isaac Asimov', 'Usado', 1, 29.00),
('Fahrenheit 451', 2012, 'Ray Bradbury', 'Novo', 1, 38.00),
('Admirável Mundo Novo', 2014, 'Aldous Huxley', 'Seminovo', 1, 36.00),
('O Guia do Mochileiro das Galáxias', 2010, 'Douglas Adams', 'Novo', 1, 32.00),
('Jogos Vorazes', 2011, 'Suzanne Collins', 'Bom', 1, 35.00),
('Percy Jackson e o Ladrão de Raios', 2010, 'Rick Riordan', 'Usado', 1, 28.00),
('As Crônicas de Nárnia', 2009, 'C.S. Lewis', 'Novo', 1, 85.00),
('O Nome do Vento', 2009, 'Patrick Rothfuss', 'Bom', 1, 58.00),
('Sandman: Prelúdio', 2015, 'Neil Gaiman', 'Novo', 1, 65.00),
('Deuses Americanos', 2016, 'Neil Gaiman', 'Seminovo', 1, 45.00),

-- TÉCNICOS E NÃO FICÇÃO
('Clean Code', 2009, 'Robert C. Martin', 'Novo', 1, 98.00),
('O Codificador Limpo', 2012, 'Robert C. Martin', 'Novo', 1, 85.00),
('Arquitetura Limpa', 2018, 'Robert C. Martin', 'Novo', 1, 92.00),
('Design Patterns', 2000, 'Gang of Four', 'Bom', 1, 120.00),
('Entendendo Algoritmos', 2017, 'Aditya Bhargava', 'Seminovo', 1, 55.00),
('Sapiens: Uma Breve História da Humanidade', 2018, 'Yuval Noah Harari', 'Novo', 1, 59.90),
('Homo Deus', 2016, 'Yuval Noah Harari', 'Bom', 1, 54.90),
('Rápido e Devagar', 2012, 'Daniel Kahneman', 'Usado', 1, 48.00),
('O Poder do Hábito', 2013, 'Charles Duhigg', 'Seminovo', 1, 42.00),
('Pai Rico, Pai Pobre', 2017, 'Robert Kiyosaki', 'Usado', 1, 35.00),
('A Arte da Guerra', 2005, 'Sun Tzu', 'Novo', 1, 25.00),
('Mindset', 2017, 'Carol S. Dweck', 'Bom', 1, 39.00),
('Essencialismo', 2015, 'Greg McKeown', 'Novo', 1, 42.00),
('Comece pelo Porquê', 2018, 'Simon Sinek', 'Seminovo', 1, 38.00),
('O Jeito Disney de Encantar Clientes', 2012, 'Disney Institute', 'Novo', 1, 32.00),
('Scrum: A Arte de Fazer o Dobro do Trabalho na Metade do Tempo', 2016, 'Jeff Sutherland', 'Bom', 1, 36.00),
('A Lógica do Cisne Negro', 2008, 'Nassim Taleb', 'Usado', 1, 55.00),
('Antifrágil', 2013, 'Nassim Taleb', 'Novo', 1, 68.00),
('Os Segredos da Mente Milionária', 2006, 'T. Harv Eker', 'Velho', 1, 20.00),
('Como Fazer Amigos e Influenciar Pessoas', 2019, 'Dale Carnegie', 'Bom', 1, 35.00),

-- BEST SELLERS E DIVERSOS
('O Código Da Vinci', 2004, 'Dan Brown', 'Seminovo', 1, 28.00),
('Anjos e Demônios', 2005, 'Dan Brown', 'Usado', 1, 25.00),
('Inferno', 2013, 'Dan Brown', 'Bom', 1, 32.00),
('A Menina que Roubava Livros', 2007, 'Markus Zusak', 'Usado', 1, 29.00),
('O Caçador de Pipas', 2006, 'Khaled Hosseini', 'Seminovo', 1, 34.00),
('A Culpa é das Estrelas', 2012, 'John Green', 'Bom', 1, 26.00),
('Crepúsculo', 2008, 'Stephenie Meyer', 'Usado', 1, 20.00),
('Lua Nova', 2008, 'Stephenie Meyer', 'Usado', 1, 20.00),
('Eclipse', 2009, 'Stephenie Meyer', 'Usado', 1, 20.00),
('Amanhecer', 2010, 'Stephenie Meyer', 'Usado', 1, 20.00),
('Cinquenta Tons de Cinza', 2012, 'E.L. James', 'Bom', 1, 22.00),
('It: A Coisa', 2014, 'Stephen King', 'Novo', 1, 85.00),
('O Iluminado', 2012, 'Stephen King', 'Seminovo', 1, 48.00),
('Carrie, a Estranha', 2013, 'Stephen King', 'Usado', 1, 32.00),
('Misery', 2014, 'Stephen King', 'Novo', 1, 45.00),
('A Torre Negra I', 2004, 'Stephen King', 'Bom', 1, 52.00),
('Bird Box', 2015, 'Josh Malerman', 'Novo', 1, 36.00),
('Garota Exemplar', 2013, 'Gillian Flynn', 'Seminovo', 1, 38.00),
('Os Homens que Não Amavam as Mulheres', 2008, 'Stieg Larsson', 'Usado', 1, 29.00),
('Extraordinário', 2013, 'R.J. Palacio', 'Novo', 1, 32.00);

COMMIT;