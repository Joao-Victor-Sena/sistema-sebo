/*Codigo usado por alunos*/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS Integrador;
CREATE DATABASE Integrador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Integrador;

CREATE TABLE FUNCIONARIO (
    CD_FUNCIONARIO INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    CPF VARCHAR(14) NOT NULL,
    FUNCAO VARCHAR(20) NOT NULL,
    SENHA VARCHAR(255) NOT NULL DEFAULT '1234',
    CONSTRAINT U_CPF_F UNIQUE (CPF)
);

CREATE TABLE CLIENTE (
    CD_CLIENTE INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    TELEFONE VARCHAR(20) DEFAULT NULL,
    CPF VARCHAR(14) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL,
    CONSTRAINT U_CPF UNIQUE (CPF)
) AUTO_INCREMENT=100;

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

CREATE TABLE COMPRA (
    CD_COMPRA INT AUTO_INCREMENT PRIMARY KEY,
    DATA DATE NOT NULL,
    HORA TIME NOT NULL,
    CD_CLIENTE INT,
    TOTAL DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (CD_CLIENTE) REFERENCES CLIENTE(CD_CLIENTE)
);

CREATE TABLE COMPRA_LIVRO (
    CD_COMPRA INT,
    CD_LIVRO INT,
    QUANTIDADE INT NOT NULL DEFAULT 1,
    VALOR_UNITARIO DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (CD_COMPRA, CD_LIVRO),
    FOREIGN KEY (CD_COMPRA) REFERENCES COMPRA(CD_COMPRA) ON DELETE CASCADE,
    FOREIGN KEY (CD_LIVRO) REFERENCES LIVRO(CD_LIVRO)
);

INSERT INTO FUNCIONARIO (NOME, CPF, FUNCAO, SENHA) VALUES 
('Administrador', '00000000000', 'Gerente', 'admin'),
('Vendedor Padrão', '11111111111', 'Vendedor', '1234'),
('Carlos Souza', '22222222222', 'Estoquista', '1234'),
('Ana Pereira', '33333333333', 'Caixa', '1234'),
('Roberto Lima', '44444444444', 'Vendedor', '1234');

INSERT INTO CLIENTE (CD_CLIENTE, NOME, CPF, EMAIL, TELEFONE) VALUES 
(100, 'Consumidor Final', '12000000000', 'caixa@sebolinhas.com', '00000000000'),
(101, 'Maria Silva', '12345678900', 'maria@email.com', '11999998888'),
(102, 'João Santos', '98765432100', 'joao@email.com', '11988887777'),
(103, 'Fernanda Oliveira', '45678912300', 'fernanda@email.com', '21977776666'),
(104, 'Lucas Martins', '78912345600', 'lucas@email.com', '31966665555');

INSERT INTO LIVRO (CD_LIVRO, TITULO, ANO, AUTOR, ESTADO, CD_FUNCIONARIO, PRECO) VALUES 
(1000, 'Dom Casmurro', 1998, 'Machado de Assis', 'Usado', 1, 34.90),
(1001, 'Memórias Póstumas de Brás Cubas', 2015, 'Machado de Assis', 'Bom', 1, 29.90),
(1002, 'O Cortiço', 2020, 'Aluísio Azevedo', 'Novo', 1, 45.00),
(1003, 'Vidas Secas', 1985, 'Graciliano Ramos', 'Seminovo', 1, 38.50),
(1004, 'Grande Sertão: Veredas', 2019, 'João Guimarães Rosa', 'Novo', 1, 89.90),
(1005, 'A Hora da Estrela', 1998, 'Clarice Lispector', 'Bom', 1, 32.00),
(1006, 'Capitães da Areia', 2011, 'Jorge Amado', 'Usado', 1, 25.00),
(1007, 'Iracema', 1975, 'José de Alencar', 'Velho', 1, 15.00),
(1008, 'Macunaíma', 2002, 'Mário de Andrade', 'Bom', 1, 28.90),
(1009, 'O Guarani', 1982, 'José de Alencar', 'Usado', 1, 22.00),
(1010, 'O Alienista', 2021, 'Machado de Assis', 'Novo', 1, 35.00),
(1011, 'A Moreninha', 1995, 'Joaquim Manuel de Macedo', 'Seminovo', 1, 30.00),
(1012, 'Sagarana', 2018, 'João Guimarães Rosa', 'Novo', 1, 55.00),
(1013, 'Fogo Morto', 1992, 'José Lins do Rego', 'Bom', 1, 42.00),
(1014, 'Auto da Compadecida', 2014, 'Ariano Suassuna', 'Novo', 1, 48.90),
(1015, 'Quincas Borba', 1988, 'Machado de Assis', 'Usado', 1, 20.00),
(1016, 'Triste Fim de Policarpo Quaresma', 2005, 'Lima Barreto', 'Bom', 1, 27.50),
(1017, 'Gabriela, Cravo e Canela', 2010, 'Jorge Amado', 'Seminovo', 1, 36.00),
(1018, 'Dona Flor e Seus Dois Maridos', 1996, 'Jorge Amado', 'Usado', 1, 33.00),
(1019, 'O Tempo e o Vento', 2022, 'Erico Verissimo', 'Novo', 1, 120.00),
(1020, '1984', 2009, 'George Orwell', 'Novo', 1, 49.90),
(1021, 'A Revolução dos Bichos', 2005, 'George Orwell', 'Seminovo', 1, 29.90),
(1022, 'Dom Quixote', 1980, 'Miguel de Cervantes', 'Usado', 1, 65.00),
(1023, 'Orgulho e Preconceito', 2018, 'Jane Austen', 'Novo', 1, 39.90),
(1024, 'Moby Dick', 2012, 'Herman Melville', 'Bom', 1, 55.00),
(1025, 'O Grande Gatsby', 2010, 'F. Scott Fitzgerald', 'Seminovo', 1, 32.50),
(1026, 'Cem Anos de Solidão', 1995, 'Gabriel García Márquez', 'Novo', 1, 58.00),
(1027, 'Crime e Castigo', 2001, 'Fiódor Dostoiévski', 'Usado', 1, 45.00),
(1028, 'Os Miseráveis', 1985, 'Victor Hugo', 'Bom', 1, 75.00),
(1029, 'A Metamorfose', 1999, 'Franz Kafka', 'Novo', 1, 25.00),
(1030, 'O Pequeno Príncipe', 2015, 'Antoine de Saint-Exupéry', 'Novo', 1, 22.00),
(1031, 'O Conde de Monte Cristo', 2011, 'Alexandre Dumas', 'Bom', 1, 85.00),
(1032, 'Drácula', 2020, 'Bram Stoker', 'Seminovo', 1, 42.00),
(1033, 'Frankenstein', 2017, 'Mary Shelley', 'Usado', 1, 28.00),
(1034, 'O Retrato de Dorian Gray', 2013, 'Oscar Wilde', 'Novo', 1, 34.00),
(1035, 'Guerra e Paz', 2016, 'Liev Tolstói', 'Bom', 1, 95.00),
(1036, 'A Divina Comédia', 1978, 'Dante Alighieri', 'Usado', 1, 50.00),
(1037, 'Hamlet', 2004, 'William Shakespeare', 'Seminovo', 1, 26.00),
(1038, 'Romeu e Julieta', 2008, 'William Shakespeare', 'Novo', 1, 28.00),
(1039, 'A Odisséia', 2014, 'Homero', 'Bom', 1, 48.00),
(1040, 'O Senhor dos Anéis: A Sociedade do Anel', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
(1041, 'O Senhor dos Anéis: As Duas Torres', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
(1042, 'O Senhor dos Anéis: O Retorno do Rei', 2019, 'J.R.R. Tolkien', 'Novo', 1, 69.90),
(1043, 'O Hobbit', 2012, 'J.R.R. Tolkien', 'Seminovo', 1, 45.00),
(1044, 'Harry Potter e a Pedra Filosofal', 2001, 'J.K. Rowling', 'Usado', 1, 35.00),
(1045, 'Harry Potter e a Câmara Secreta', 2002, 'J.K. Rowling', 'Usado', 1, 35.00),
(1046, 'A Guerra dos Tronos', 2010, 'George R.R. Martin', 'Bom', 1, 55.00),
(1047, 'Duna', 2021, 'Frank Herbert', 'Novo', 1, 78.00),
(1048, 'Neuromancer', 2015, 'William Gibson', 'Seminovo', 1, 42.00),
(1049, 'Fundação', 2009, 'Isaac Asimov', 'Bom', 1, 48.00),
(1050, 'Eu, Robô', 2014, 'Isaac Asimov', 'Usado', 1, 29.00),
(1051, 'Fahrenheit 451', 2012, 'Ray Bradbury', 'Novo', 1, 38.00),
(1052, 'Admirável Mundo Novo', 2014, 'Aldous Huxley', 'Seminovo', 1, 36.00),
(1053, 'O Guia do Mochileiro das Galáxias', 2010, 'Douglas Adams', 'Novo', 1, 32.00),
(1054, 'Jogos Vorazes', 2011, 'Suzanne Collins', 'Bom', 1, 35.00),
(1055, 'Percy Jackson e o Ladrão de Raios', 2010, 'Rick Riordan', 'Usado', 1, 28.00),
(1056, 'As Crônicas de Nárnia', 2009, 'C.S. Lewis', 'Novo', 1, 85.00),
(1057, 'O Nome do Vento', 2009, 'Patrick Rothfuss', 'Bom', 1, 58.00),
(1058, 'Sandman: Prelúdio', 2015, 'Neil Gaiman', 'Novo', 1, 65.00),
(1059, 'Deuses Americanos', 2016, 'Neil Gaiman', 'Seminovo', 1, 45.00),
(1060, 'Clean Code', 2009, 'Robert C. Martin', 'Novo', 1, 98.00),
(1061, 'O Codificador Limpo', 2012, 'Robert C. Martin', 'Novo', 1, 85.00),
(1062, 'Arquitetura Limpa', 2018, 'Robert C. Martin', 'Novo', 1, 92.00),
(1063, 'Design Patterns', 2000, 'Gang of Four', 'Bom', 1, 120.00),
(1064, 'Entendendo Algoritmos', 2017, 'Aditya Bhargava', 'Seminovo', 1, 55.00),
(1065, 'Sapiens: Uma Breve História da Humanidade', 2018, 'Yuval Noah Harari', 'Novo', 1, 59.90),
(1066, 'Homo Deus', 2016, 'Yuval Noah Harari', 'Bom', 1, 54.90),
(1067, 'Rápido e Devagar', 2012, 'Daniel Kahneman', 'Usado', 1, 48.00),
(1068, 'O Poder do Hábito', 2013, 'Charles Duhigg', 'Seminovo', 1, 42.00),
(1069, 'Pai Rico, Pai Pobre', 2017, 'Robert Kiyosaki', 'Usado', 1, 35.00),
(1070, 'A Arte da Guerra', 2005, 'Sun Tzu', 'Novo', 1, 25.00),
(1071, 'Mindset', 2017, 'Carol S. Dweck', 'Bom', 1, 39.00),
(1072, 'Essencialismo', 2015, 'Greg McKeown', 'Novo', 1, 42.00),
(1073, 'Comece pelo Porquê', 2018, 'Simon Sinek', 'Seminovo', 1, 38.00),
(1074, 'O Jeito Disney de Encantar Clientes', 2012, 'Disney Institute', 'Novo', 1, 32.00),
(1075, 'Scrum: A Arte de Fazer o Dobro do Trabalho na Metade do Tempo', 2016, 'Jeff Sutherland', 'Bom', 1, 36.00),
(1076, 'A Lógica do Cisne Negro', 2008, 'Nassim Taleb', 'Usado', 1, 55.00),
(1077, 'Antifrágil', 2013, 'Nassim Taleb', 'Novo', 1, 68.00),
(1078, 'Os Segredos da Mente Milionária', 2006, 'T. Harv Eker', 'Velho', 1, 20.00),
(1079, 'Como Fazer Amigos e Influenciar Pessoas', 2019, 'Dale Carnegie', 'Bom', 1, 35.00),
(1080, 'O Código Da Vinci', 2004, 'Dan Brown', 'Seminovo', 1, 28.00),
(1081, 'Anjos e Demônios', 2005, 'Dan Brown', 'Usado', 1, 25.00),
(1082, 'Inferno', 2013, 'Dan Brown', 'Bom', 1, 32.00),
(1083, 'A Menina que Roubava Livros', 2007, 'Markus Zusak', 'Usado', 1, 29.00),
(1084, 'O Caçador de Pipas', 2006, 'Khaled Hosseini', 'Seminovo', 1, 34.00),
(1085, 'A Culpa é das Estrelas', 2012, 'John Green', 'Bom', 1, 26.00),
(1086, 'Crepúsculo', 2008, 'Stephenie Meyer', 'Usado', 1, 20.00),
(1087, 'Lua Nova', 2008, 'Stephenie Meyer', 'Usado', 1, 20.00),
(1088, 'Eclipse', 2009, 'Stephenie Meyer', 'Usado', 1, 20.00),
(1089, 'Amanhecer', 2010, 'Stephenie Meyer', 'Usado', 1, 20.00),
(1090, 'Cinquenta Tons de Cinza', 2012, 'E.L. James', 'Bom', 1, 22.00),
(1091, 'It: A Coisa', 2014, 'Stephen King', 'Novo', 1, 85.00),
(1092, 'O Iluminado', 2012, 'Stephen King', 'Seminovo', 1, 48.00),
(1093, 'Carrie, a Estranha', 2013, 'Stephen King', 'Usado', 1, 32.00),
(1094, 'Misery', 2014, 'Stephen King', 'Novo', 1, 45.00),
(1095, 'A Torre Negra I', 2004, 'Stephen King', 'Bom', 1, 52.00),
(1096, 'Bird Box', 2015, 'Josh Malerman', 'Novo', 1, 36.00),
(1097, 'Garota Exemplar', 2013, 'Gillian Flynn', 'Seminovo', 1, 38.00),
(1098, 'Os Homens que Não Amavam as Mulheres', 2008, 'Stieg Larsson', 'Usado', 1, 29.00),
(1099, 'Extraordinário', 2013, 'R.J. Palacio', 'Novo', 1, 32.00);

INSERT INTO COMPRA (DATA, HORA, CD_CLIENTE, TOTAL) VALUES ('2023-10-01', '10:30:00', 101, 79.90);
INSERT INTO COMPRA_LIVRO (CD_COMPRA, CD_LIVRO, QUANTIDADE, VALOR_UNITARIO) VALUES 
(1, 1000, 1, 34.90),
(1, 1002, 1, 45.00);

INSERT INTO COMPRA (DATA, HORA, CD_CLIENTE, TOTAL) VALUES ('2023-10-02', '14:15:00', 102, 49.90);
INSERT INTO COMPRA_LIVRO (CD_COMPRA, CD_LIVRO, QUANTIDADE, VALOR_UNITARIO) VALUES 
(2, 1020, 1, 49.90);

INSERT INTO COMPRA (DATA, HORA, CD_CLIENTE, TOTAL) VALUES ('2023-10-03', '16:45:00', 103, 70.00);
INSERT INTO COMPRA_LIVRO (CD_COMPRA, CD_LIVRO, QUANTIDADE, VALOR_UNITARIO) VALUES 
(3, 1044, 1, 35.00),
(3, 1045, 1, 35.00);

COMMIT;