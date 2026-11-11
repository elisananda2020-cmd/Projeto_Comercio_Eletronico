# TechMaker 3D

Loja em PHP e MySQL com catálogo, cadastro, carrinho em sessão e registro de pedidos pendentes.

## Instalação local

Requisitos de produção: PHP 8.2 ou superior em 64 bits, extensões `pdo_mysql` e `session`, e MySQL 8.0.16 ou superior com InnoDB e `utf8mb4`. O ambiente local de testes usa o MariaDB 10.4.32 do XAMPP. [Restrições CHECK no MySQL](https://dev.mysql.com/doc/refman/8.0/en/information-schema-check-constraints-table.html)

1. Crie um banco vazio, por exemplo `techmaker`, e importe [database/database.sql](database/database.sql) com uma conta administrativa. No XAMPP, a importação pode ser feita pelo phpMyAdmin, com o banco selecionado.
2. Em uma instalação anterior à etapa de pedidos, faça backup e aplique [001_checkout_token.sql](database/migrations/001_checkout_token.sql) uma única vez se a coluna `orders.checkout_token` ainda estiver ausente. Uma instalação nova já inclui a coluna.
3. Crie um usuário exclusivo para a aplicação. Exemplo para um banco local acessado por TCP, com um valor de senha escolhido na instalação.

   ```sql
   CREATE USER 'techmaker_app'@'127.0.0.1' IDENTIFIED BY '<senha-da-aplicação>';
   GRANT SELECT, INSERT ON techmaker.users TO 'techmaker_app'@'127.0.0.1';
   GRANT SELECT, UPDATE ON techmaker.products TO 'techmaker_app'@'127.0.0.1';
   GRANT SELECT, INSERT ON techmaker.orders TO 'techmaker_app'@'127.0.0.1';
   GRANT SELECT, INSERT ON techmaker.order_items TO 'techmaker_app'@'127.0.0.1';
   ```

4. Na raiz do projeto, configure as variáveis e inicie o servidor local. Em PowerShell:

   ```powershell
   $env:DB_HOST = '127.0.0.1'
   $env:DB_PORT = '3306'
   $env:DB_NAME = 'techmaker'
   $env:DB_USER = 'techmaker_app'
   $env:DB_PASSWORD = Read-Host 'Senha do banco' -MaskInput
   php -S 127.0.0.1:8000 -t site
   ```

   Abra [a loja local](http://127.0.0.1:8000/). Use PowerShell 7 para o comando `Read-Host -MaskInput`. Se o PHP do XAMPP estiver fora do PATH, substitua `php` pelo caminho do executável.

O catálogo inicial contém 12 produtos e estoque zero. Para rodar a demonstração, importe [database/demo_stock.sql](database/demo_stock.sql) após o esquema, na mesma base de teste. O arquivo disponibiliza os 12 produtos com quantidades fictícias. O pack STL usa 100 unidades simuladas para exercitar a baixa de estoque. Use dados fictícios no cadastro. O pedido pendente já dá baixa no estoque.

## Hospedagem

- Aponte a raiz pública do domínio para `site/`. Mantenha `config/`, `database/`, `includes/`, `models/` e `services/` no diretório pai, fora da área pública. Configure `index.php` como página inicial.
- Configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASSWORD` no ambiente do processo PHP. Guarde as credenciais no painel da hospedagem ou na configuração privada do serviço.
- Sirva por HTTPS e defina `SESSION_COOKIE_SECURE=1` em produção, inclusive quando a conexão HTTPS termina em um proxy. Deixe `display_errors=Off` e `log_errors=On` na configuração do PHP, com os logs fora da raiz pública. Aplique limite de requisições aos POSTs de `registro.php` no servidor ou proxy público.
- Os contatos, os termos e as políticas exibidos são demonstrativos. Para uma loja comercial, substitua esses dados e o estoque pelas informações reais e integre pagamento e entrega.
- O GitHub pode guardar o código. Para rodar a aplicação, use uma hospedagem que execute PHP e ofereça MySQL. O GitHub Pages serve arquivos estáticos e não executa PHP. [Documentação do GitHub Pages](https://docs.github.com/en/pages/getting-started-with-github-pages/creating-a-github-pages-site)

## Google Analytics 4

Defina `GA4_MEASUREMENT_ID` no ambiente do PHP com o ID da propriedade da loja. A variável pode ficar vazia durante o desenvolvimento. A tag é carregada após o aceite em **Preferências de cookies**.

No fluxo da Web, desative a medição otimizada para usar os eventos controlados pelo site e mantenha a coleta de dados fornecidos pelo usuário desativada. Valide no Tag Assistant que a tag fica bloqueada antes do consentimento e que os eventos aparecem após o aceite. A pesquisa envia a ocorrência e a quantidade de resultados. O evento `purchase` fica reservado para a confirmação real do pagamento. [Medição otimizada](https://support.google.com/analytics/answer/9216061) · [Coleta de dados fornecidos pelo usuário](https://support.google.com/analytics/answer/14077171) · [Tag Assistant](https://developers.google.com/tag-platform/security/guides/consent-debugging)
