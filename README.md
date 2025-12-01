# Sistema de Gerenciamento para Sebos

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

## 📖 Sobre o Projeto

### Propósito e Público-Alvo
Este software foi desenvolvido especificamente para atender às necessidades de gestão de **Sebos** (livrarias de livros usados). O objetivo central da aplicação é oferecer controle total sobre os pilares do negócio:

* **Gerenciamento de Estoque (Acervo)**
* **Cadastro de Clientes**
* **Registro de Vendas**

É fundamental destacar que este é um sistema de uso estritamente **administrativo e interno**. O consumidor final da ferramenta é exclusivamente o **dono ou administrador do Sebo**. Os clientes da loja não possuem acesso à interface do sistema; ele serve como uma ferramenta de retaguarda ("back-office") para organizar a loja física e garantir que o proprietário tenha domínio completo sobre os dados do seu negócio.

### 🚀 Evolução Tecnológica: De Java para Web
A trajetória de desenvolvimento deste software reflete uma evolução técnica significativa e adaptação de tecnologias, dividida em duas fases:

1.  **A Origem (Java & SQL Server):**
    O projeto nasceu no ambiente acadêmico como o **Trabalho Final da disciplina de Programação Orientada a Objetos (POO)**. Inicialmente, toda a arquitetura foi construída em **Java**, integrada a um banco de dados **SQL Server**, focando estritamente na aplicação robusta de conceitos de orientação a objetos.

2.  **A Migração (PHP, MySQL & Bootstrap):**
    Com a documentação e a lógica de negócios validadas, a equipe decidiu migrar a solução para um ambiente web mais dinâmico e acessível. Mantendo fielmente a mesma documentação e regras do projeto original, houve uma transição completa da stack tecnológica:
    * **Back-end:** A lógica foi portada de Java para **PHP**.
    * **Banco de Dados:** Migração do SQL Server para **MySQL**.
    * **Front-end:** Implementação do framework **Bootstrap**, garantindo uma interface administrativa ágil, responsiva e moderna.

---

## 👨‍💻 Autores

Este projeto foi desenvolvido colaborativamente por:

* **João Victor Sena**
* **Micael Vasconcelos**
* **Tiago Kauã**

---

## 🛠️ Tecnologias Utilizadas

* **Linguagem:** PHP 
* **Banco de Dados:** MySQL
* **Front-end:** HTML5, CSS3, Bootstrap
* **Servidor Local Sugerido:** XAMPP

---

## ⚙️ Instalação e Configuração

Siga os passos abaixo para rodar o projeto em sua máquina local.

### Pré-requisitos
* Ter um ambiente de servidor local instalado (ex: [XAMPP](https://www.apachefriends.org/pt_br/index.html)).
* Ter o Git instalado.

### Passo a Passo

1.  **Clone o repositório**
    Abra o terminal na pasta `htdocs` (se usar XAMPP):
    ```bash
    git clone [https://github.com/Joao-Victor-Sena/sistema-sebo.git](https://github.com/Joao-Victor-Sena/sistema-sebo.git)
    ```

2.  **Configuração do Banco de Dados**
    * Abra o seu gerenciador de banco de dados (ex: PHPMyAdmin).
    * Crie um novo banco de dados com o nome integrador(verifique o nome no arquivo de conexão).
    * Importe o arquivo `.sql` disponível na pasta `database/` ou `sql/` deste projeto.

3.  **Configuração da Conexão**
    * Navegue até o arquivo de configuração de banco de dados do projeto (geralmente `conexao.php` ou `config.php`).
    * Verifique se as credenciais (usuário e senha) correspondem às do seu servidor local (o padrão do XAMPP é usuário `root` e senha vazia).

4.  **Executar**
    * Inicie o Apache e o MySQL no seu painel de controle (XAMPP/WAMP).
    * Acesse no navegador:
    ```
    http://localhost/sistema-sebo
    ```

---
*Desenvolvido como projeto acadêmico de evolução de software.*
