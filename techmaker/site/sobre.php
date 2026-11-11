<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';

start_store_session();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechMaker 3D - Sua Loja Maker</title>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php $analyticsPage = 'about'; require __DIR__ . '/../includes/analytics.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>

    <?php require __DIR__ . '/../includes/cart-panel.php'; ?>

    <header class="main-header">
        <div class="container header-content">
            <a href="index.php" class="logo">TechMaker</a>

            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Início</a></li>

                    <li class="dropdown-menu">
                        <a href="produtos.php">Nossos Produtos <i class="fas fa-caret-down"></i></a>

                        <ul class="dropdown-content">
                            <li><a href="produtos.php?categoria=Impressora%203D">Impressão 3D</a></li>
                            <li><a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica">Arduino Maker</a></li>
                            <li><a href="produtos.php?categoria=Cortadora%20Laser">Corte a Laser</a></li>
                        </ul>
                    </li>

                    <li><a href="#">Cultura Maker</a></li>
                    <li><a href="sobre.php">Sobre Nós</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <?php require __DIR__ . '/../includes/catalog-search.php'; ?>
                <a href="registro.php" class="action-icon" title="Minha Conta"><i class="fas fa-user"></i></a>
                <?php require __DIR__ . '/../includes/cart-trigger.php'; ?>
            </div>
        </div>
    </header>

    <main>
        <section class="page-title-banner">
            <div class="container">
                <h1>Sobre a TechMaker 3D</h1>
                <p>Nossa paixão por criar e inovar, desde a fundação.</p>
            </div>
        </section>

        <section id="historia" class="company-history">
            <div class="container">
                <h2>Nossa História e DNA Maker</h2>

                <div class="history-content">
                    <p>A TechMaker nasceu em 2024 de um Rio Branco, Acre (maratona de desenvolvimento) e da
                        frustração de seus fundadores em encontrar componentes de qualidade e suporte técnico
                        especializado no Brasil. Começamos em uma garagem, com uma única impressora 3D e a missão de
                        democratizar a tecnologia.</p>

                    <p>Ao longo dos anos, evoluímos de um pequeno fornecedor para um hub de inovação. Hoje, somos a
                        ponte entre a sua ideia e a realização dela, oferecendo desde os melhores filamentos até kits
                        completos de robótica e máquinas de corte a laser.</p>

                    <h3>Momento Chave: Expansão em 2025</h3>
                    <p>Em 2025, expandimos nosso catálogo para incluir soluções completas para robótica educacional e
                        projetos de prototipagem rápida, firmando parcerias com fornecedores internacionais e reforçando
                        nosso compromisso com a comunidade.</p>
                </div>

                <div class="history-timeline">
                    <div class="timeline-event">2024: Fundação</div>
                    <div class="timeline-event">2025: Expansão de Catálogo</div>
                    <div class="timeline-event">2026: Lançamento da Comunidade</div>
                </div>
            </div>
        </section>

        <section id="dados" class="company-data">
            <div class="container">
                <h2>Missão, Visão e Valores Chave</h2>

                <div class="data-grid">
                    <div class="data-card">
                        <i class="fas fa-bullseye"></i>
                        <h3>Missão</h3>
                        <p>Inspirar e capacitar criadores, educadores e engenheiros, fornecendo ferramentas e
                            conhecimento para transformar ideias em realidade.</p>
                    </div>
                    <div class="data-card">
                        <i class="fas fa-eye"></i>
                        <h3>Visão</h3>
                        <p>Ser o principal ecossistema Maker do Brasil, reconhecido pela qualidade dos produtos
                            e excelência no suporte à comunidade.</p>
                    </div>
                    <div class="data-card">
                        <i class="fas fa-gem"></i>
                        <h3>Valores</h3>
                        <ul>
                            <li>Inovação Constante</li>
                            <li>Paixão pela Comunidade</li>
                            <li>Integridade e Transparência</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="maker-section">
            <div class="container">
                <h2>Pronto para Criar?</h2>
                <p>Preencha o formulário e comece seu próximo projeto agora mesmo!</p>
                <a href="institucional.php#contato" class="secondary-button">Vamos Criar?</a>
            </div>
        </section>
    </main>

 <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-column">
                <h3>Sobre a TechMaker</h3>
                <p>Líder em tecnologia de fabricação digital, oferecendo o melhor em Impressão 3D, Laser e Eletrônica para sua criatividade.</p>
                <div class="social-icons">
                    <a href="institucional.php#redes" aria-label="Facebook da demonstração"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="institucional.php#redes" aria-label="Instagram da demonstração"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                    <a href="institucional.php#redes" aria-label="LinkedIn da demonstração"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Atendimento</h3>
                <ul>
                    <li><a href="institucional.php#contato">Fale Conosco</a></li>
                    <li><a href="institucional.php#ajuda">Central de Ajuda</a></li>
                    <li><a href="institucional.php#trocas">Política de Troca</a></li>
                    <li><a href="registro.php">Minha Conta</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Produtos</h3>
                <ul>
                    <li><a href="produtos.php?categoria=Impressora%203D">Impressoras</a></li>
                    <li><a href="produtos.php?categoria=Filamentos">Filamentos</a></li>
                    <li><a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica">Kits Eletrônicos</a></li>
                    <li><a href="produtos.php?categoria=Pe%C3%A7as%20e%20Componentes">Peças e Componentes</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                © 2025 TechMaker. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>

</html>
