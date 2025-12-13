# Sistema de Gerenciamento para Sebos (SeboLinhas)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Status](https://img.shields.io/badge/Status-Concluído-success?style=for-the-badge)

> Um sistema administrativo web para controle de estoque e vendas de livrarias de livros usados.

![Preview do Sistema](css/img/index.png)

## Sobre o Projeto

Este software foi desenvolvido para atender às necessidades de gestão de Sebos. O objetivo central é oferecer uma ferramenta de gerenciamento para o administrador, garantindo controle total sobre o negócio sem expor dados administrativos ao consumidor final.

### Evolução Tecnológica
A trajetória de desenvolvimento deste software reflete uma evolução técnica e adaptação de tecnologias:

1. **A Origem (Java & SQL Server):** O projeto nasceu no ambiente acadêmico como Trabalho Final de POO. A arquitetura inicial foi construída em Java, focada na aplicação de conceitos de orientação a objetos.
2. **A Migração (PHP & MySQL):** Com a lógica de negócios validada, a solução foi portada para um ambiente web. Manteve-se a documentação original, mas houve uma transição completa para PHP e MySQL, implementando o framework Bootstrap para uma interface responsiva.

---

## Funcionalidades Principais

* **Gerenciamento de Acervo:** Cadastro completo de livros, edição de dados e controle de quantidade em estoque.
* **Gestão de Clientes:** Cadastro e manutenção da base de dados de clientes para histórico de compras.
* **Controle de Vendas (PDV):** Interface para registro de saídas de produtos e concretização de vendas.
* **Painel Administrativo:** Visão geral e restrita para gerenciamento do sistema.

---

## Tecnologias Utilizadas

* **Linguagem Back-end:** PHP (Suporte a OO)
* **Banco de Dados:** MySQL
* **Front-end:** HTML5, CSS3, Bootstrap 5
* **Servidor Local:** XAMPP

---

## Instalação e Configuração

Siga os passos abaixo para executar o projeto em um ambiente local.

### Pré-requisitos
* XAMPP.
* Git instalado.

### Passo a Passo

1. **Clonar o Repositório**
   Abra o terminal na pasta pública do seu servidor (ex: `htdocs` no XAMPP):
   ```bash
   git clone [https://github.com/Joao-Victor-Sena/sistema-sebo.git](https://github.com/Joao-Victor-Sena/sistema-sebo.git)
   ```

2. **Configuração do Banco de Dados**
   * Acesse o seu gerenciador de banco de dados (ex: PHPMyAdmin).
   * Crie um novo banco de dados com o nome: `integrador`.
   * Importe o arquivo SQL disponível na pasta `database/` deste repositório.

3. **Configuração da Conexão**
   * Localize o arquivo `conexao.php` na raiz do projeto.
   * Verifique se as credenciais de usuário e senha correspondem ao seu servidor local.
   * *Padrão XAMPP:* Usuário `root` e senha vazia.

4. **Execução**
   * Certifique-se de que os serviços Apache e MySQL estão rodando.
   * Acesse pelo navegador: `http://localhost/sistema-sebo`

---

## Acesso ao Sistema (Credenciais de Teste)

Após a instalação, utilize os usuários abaixo para testar os diferentes níveis de acesso:

### Perfil Gerente (Acesso Total)
* **Usuário (CPF):** `00000000000`
* **Senha:** `admin`

### Perfil Vendedor (Acesso Restrito)
* **Usuário (CPF):** `11111111111`
* **Senha:** `1234`

---

## Autores

* **João Victor Sena**
* **Micael Vasconcelos**
* **Pedro Henrique Moreira**
* **Tiago Kauã**

---
*Desenvolvido como projeto acadêmico de evolução de software e sistemas web.*