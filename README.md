ConectaTech - Rede Social (corrigido)
------------------------------------
O pacote corrigido:

- Caminhos corrigidos (uso de dirname(__DIR__) para evitar problemas no Windows/XAMPP).
- Implementação completa de: registro, login, logout, criação de posts (texto + imagem).
- Estilização com Bootstrap 5 + CSS custom.
- .env simples (sem dependências) para configuração do DB.
- migrations.sql com tabelas de users e posts.

Como usar:
1. Extraia a pasta para seu htdocs (ex: C:\xampp\htdocs\conectatech_social_php_fixed).
2. Crie o banco executando migrations.sql no phpMyAdmin ou via CLI.
3. Ajuste .env se necessário.
4. Acesse http://localhost/conectatech_social_php_fixed/public/ (ou configure DocumentRoot para a pasta public).
