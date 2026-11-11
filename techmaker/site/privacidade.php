<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/http.php';

store_response_headers();
$analyticsPage = 'privacy';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacidade | TechMaker</title>
    <link rel="stylesheet" href="styles.css">
    <?php require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>
    <main class="register-section">
        <div class="container">
            <section class="register-box">
                <h1>Privacidade e cookies</h1>
                <h2>Conta e pedidos</h2>
                <p>Ao criar uma conta, você informa seu nome, e-mail e senha. A loja armazena o nome, o e-mail e um hash da senha para identificar a conta e permitir o login. Ao confirmar um pedido, a loja associa à conta os itens, as quantidades, os preços, a data e o status do pedido.</p>
                <h2>Sessão da loja</h2>
                <p>Um cookie essencial guarda o identificador da sessão. O login e os itens do carrinho ficam na sessão no servidor durante a navegação.</p>
                <h2>Análise de navegação</h2>
                <p>Quando habilitado pela loja e aceito por você, o Google Analytics mede a navegação e interações com o catálogo e o carrinho. Você pode alterar sua escolha em Preferências de cookies, no rodapé.</p>
                <p>A preferência fica salva no navegador. A medição registra páginas visitadas, pesquisas e interações com produtos. Os eventos usam endereços de página sem parâmetros e excluem o texto digitado na busca, os dados da conta e os dados do formulário.</p>
                <h2>Responsável e contato de exemplo</h2>
                <p>TechMaker 3D Demonstrações é a empresa fictícia do projeto. O canal ilustrativo para dúvidas e solicitações de privacidade é <strong>privacidade@techmaker.example</strong>. Use apenas dados de teste nesta demonstração.</p>
                <p><a href="institucional.php#termos" class="form-link">Termos da demonstração</a></p>
                <p><a href="index.php" class="form-link">Voltar à página inicial</a></p>
            </section>
        </div>
    </main>
    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
