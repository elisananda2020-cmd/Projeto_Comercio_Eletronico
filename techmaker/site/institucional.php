<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/http.php';

store_response_headers();
$analyticsPage = 'institutional';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atendimento e termos | TechMaker 3D</title>
    <link rel="stylesheet" href="styles.css">
    <?php require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>
    <main class="register-section">
        <div class="container">
            <article class="register-box">
                <h1>Atendimento e termos</h1>

                <h2 id="contato">Fale conosco</h2>
                <p>TechMaker 3D Demonstrações é a empresa fictícia deste projeto. O endereço ilustrativo é Rua do Protótipo, 300, Cidade Maker.</p>
                <p>E-mail de exemplo: <strong>atendimento@techmaker.example</strong>. O endereço é ilustrativo e não recebe mensagens.</p>

                <h2 id="ajuda">Central de ajuda</h2>
                <p>Para testar a loja, escolha produtos no catálogo, monte o carrinho e use nome, e-mail e senha de teste no cadastro. O resumo mostra os preços e as quantidades. Após a confirmação, o pedido aparece como pendente.</p>

                <h2 id="termos">Termos de serviço da demonstração</h2>
                <p>O cadastro e o pedido servem para demonstrar o funcionamento do sistema. Preços, disponibilidade e quantidades são ilustrativos. O pedido de teste não gera cobrança, obrigação de pagamento, despacho ou liberação de arquivos digitais.</p>

                <h2 id="entrega">Entrega simulada</h2>
                <p>No cenário fictício, o prazo ilustrativo para produtos físicos é de 3 a 7 dias úteis após o pagamento. O pack STL teria entrega digital após a confirmação. Nesta versão, nenhuma entrega é realizada.</p>

                <h2 id="trocas">Trocas e devoluções simuladas</h2>
                <p>Em um exemplo de atendimento, o comprador informa o número do pedido e o motivo da solicitação. A loja confirma o recebimento e orienta sobre troca, devolução ou cancelamento conforme o caso. Pedidos desta demonstração não geram remessa ou reembolso.</p>

                <h2 id="redes">Redes sociais</h2>
                <p>Os ícones de Facebook, Instagram e LinkedIn representam canais fictícios desta demonstração.</p>
                <p><a href="privacidade.php" class="form-link">Privacidade e cookies</a> · <a href="index.php" class="form-link">Voltar à página inicial</a></p>
            </article>
        </div>
    </main>
    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
