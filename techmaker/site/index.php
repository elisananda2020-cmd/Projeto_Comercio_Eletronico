<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Product.php';

start_store_session();

try {
    $homeProducts = home_products();
} catch (RuntimeException $exception) {
    error_log('Falha ao consultar vitrine. Código: ' . $exception->getCode());
    $homeProducts = null;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECHMAKER 3D - Tecnologia e Inovação Maker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="header.css">
     <link rel="stylesheet" href="footer.css">

    <?php $analyticsPage = 'home'; require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>

    <?php require __DIR__ . '/../includes/cart-panel.php'; ?>

    <header class="main-header">
        <div class="container header-content">
            <a href="#" class="logo">TechMaker</a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="produtos.php">Ofertas</a></li>
                    <li class="dropdown-menu">
                        <a href="produtos.php">Produtos <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="produtos.php?categoria=Impressora%203D">Impressão 3D</a></li>
                            <li><a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica">Kits Arduino</a></li>
                            <li><a href="produtos.php?categoria=Cortadora%20Laser">Corte a Laser</a></li>
                            <li><a href="index.php#mais-vendidos">Mais Vendidos</a></li>
                        </ul>
                    </li>
                    <li><a href="sobre.php">Sobre Nós</a></li> 
                    <li><a href="#diferenciais">Diferenciais</a></li>
                    <li><a href="institucional.php#contato">Vamos Criar?</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <?php require __DIR__ . '/../includes/catalog-search.php'; ?>
                <a href="registro.php" class="action-icon" title="Cadastre-se ou Faça Login"><i class="fas fa-user"></i></a>
                
                <?php require __DIR__ . '/../includes/cart-trigger.php'; ?>
            </div>
        </div>
    </header>

    <main>
        
        <section class="hero-banner">
            <div class="container hero-content-dark">
                <div class="highlight-chip reveal-item">
                    <i class="fas fa-microchip"></i> Tecnologia de Fabricação Digital
                </div>

                <h1>Transforme suas ideias em realidade</h1>
                <p>Impressoras 3D, cortadoras laser e componentes Arduino para makers, profissionais e entusiastas. Qualidade premium com suporte especializado.</p>
                
                <div class="hero-buttons">
                    <a href="#categorias" class="cta-button">Ver Produtos <i class="fas fa-arrow-right"></i></a>
                    <a href="#" class="cta-button-secondary"> <i class="fas fa-play"></i> Assistir Demo</a>
                </div>
            </div>
        </section>

        

        <section id="categorias" class="main-categories reveal-item">
            <div class="container">
                <h2>Explore Nossas Seções</h2>
                <p class="subtitle" style="color: var(--text-color);">Navegue por tecnologia ou aplicação e encontre o que precisa.</p>
                <div class="category-grid">
                    <a href="produtos.php?categoria=Impressora%203D" class="category-card reveal-item">
                        <i class="fas fa-cube"></i>
                        <h3>Impressão 3D</h3>
                        <p>Filamentos, peças de reposição e modelos prontos.</p>
                    </a>
                    <a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica" class="category-card reveal-item">
                        <i class="fas fa-microchip"></i>
                        <h3>Arduino Maker</h3>
                        <p>Kits, sensores, placas e componentes.</p>
                    </a>
                    <a href="produtos.php?categoria=Cortadora%20Laser" class="category-card reveal-item">
                        <i class="fas fa-cut"></i>
                        <h3>Corte a Laser</h3>
                        <p>Máquinas, insumos e projetos de corte/gravação.</p>
                    </a>
                </div>
            </div>
        </section>

        <section id="aplicacoes-carrossel" class="applications-carousel-section reveal-item">
            <div class="container">
                <h2 class="carousel-title">Onde a Inovação Acontece?</h2>
                <p class="subtitle" style="color: var(--text-color); margin-bottom: 2rem;">Inspire-se e encontre produtos para o seu nicho de interesse.</p>

                <div class="tab-buttons">
                    <button class="tab-btn active" data-target="colecionador"><i class="fas fa-trophy"></i> Itens de Colecionador</button>
                    <button class="tab-btn" data-target="educacao"><i class="fas fa-graduation-cap"></i> Ensino e Educação</button>
                    <button class="tab-btn" data-target="dia-a-dia"><i class="fas fa-tools"></i> Soluções do Dia a Dia</button>
                </div>

                <div class="carousel-slides">
                    <div class="carousel-slide active" data-slide="colecionador">
                        <div class="slide-content">
                            <div class="text-block">
                                <h3>Miniaturas e Réplicas Incríveis</h3>
                                <p>Crie peças únicas, action figures personalizadas, réplicas detalhadas de filmes e jogos, ou restaure modelos antigos com precisão milimétrica. O universo do colecionismo encontra a perfeição da fabricação digital.</p>
                                <a href="#" class="cta-button-light-secondary">Ver Modelos Colecionáveis</a>
                            </div>
                            <div class="image-block">
                                <img src="https://lh4.googleusercontent.com/7Zkt5twYBOnRLGa_tBzoX6WSuV-DVlT_nD_ioQhK91tPGRuekUoMFdtOur6RCIEcR3bL9Pf3V4VPEDrIirsR8EmXKsNCzi5IpVFu67RneoDDf1qc1WkKu92vOsFeiciUdOZUSG_j=s0" alt="Exemplo de Item de Colecionador feito com tecnologia Maker">
                            </div>
                        </div>
                    </div>

                    <div class="carousel-slide" data-slide="educacao">
                        <div class="slide-content">
                            <div class="text-block">
                                <h3>Aprender Fazendo: STEAM na Prática</h3>
                                <p>Transforme aulas de robótica, física e design em experiências interativas. Nossos kits Arduino e materiais para impressão 3D são ideais para escolas, faculdades e laboratórios maker, promovendo o aprendizado STEAM (Ciência, Tecnologia, Engenharia, Artes e Matemática).</p>
                                <a href="#" class="cta-button-light-secondary">Ver Kits Educacionais</a>
                            </div>
                             <div class="image-block">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR53KaSOcV6iOx7VdqZCalUMKfdMluwjcFodQ&s" alt="Exemplo de Projetos Educacionais STEAM/Robótica">
                            </div>
                        </div>
                    </div>

                    <div class="carousel-slide" data-slide="dia-a-dia">
                        <div class="slide-content">
                            <div class="text-block">
                                <h3>Reparos, Ferramentas e Gadgets</h3>
                                <p>Chega de gambiarras! Use a tecnologia maker para criar soluções práticas. Imprima suportes, ganchos, peças de reposição para eletrodomésticos, organizadores personalizados e pequenos gadgets que simplificam sua rotina doméstica ou profissional.</p>
                                <a href="#" class="cta-button-light-secondary">Ver Soluções Domésticas</a>
                            </div>
                             <div class="image-block">
                                <img src="https://things.3dfila.com.br/img/56253.jpg" alt="Exemplo de soluções do dia a dia impressas em 3D">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-indicators" id="applications-indicators">
                    <span class="indicator-dot active" data-index="0" data-target="colecionador"></span>
                    <span class="indicator-dot" data-index="1" data-target="educacao"></span>
                    <span class="indicator-dot" data-index="2" data-target="dia-a-dia"></span>
                </div>
            </div>
        </section>
        <section id="diferenciais" class="why-choose-us why-choose-us-dark reveal-item">
            <div class="container">
                <p class="chip-text">POR QUE ESCOLHER A TechMaker 3D?</p>
                <h2 class="section-title-highlight">
                    Compromisso com <span class="highlight-word">excelência</span>
                </h2>
                <p class="subtitle" style="color: rgba(255, 255, 255, 0.9);">Mais do que produtos, oferecemos uma experiência completa para transformar suas ideias em realidade.</p>
                
                <div class="features-grid">
                    <div class="feature-card reveal-item">
                        <i class="fas fa-truck" style="color: #FF7043; background: #FF70431A;"></i>
                        <h3>Entrega Rápida</h3>
                        <p>Enviamos para todo o Brasil com rastreamento em tempo real.</p>
                    </div>
                    <div class="feature-card reveal-item">
                        <i class="fas fa-shield-alt" style="color: #4CAF50; background: #4CAF501A;"></i>
                        <h3>Garantia Estendida</h3>
                        <p>Todos os produtos com garantia de 12 meses + suporte técnico.</p>
                    </div>
                    <div class="feature-card reveal-item">
                        <i class="fas fa-headset" style="color: #9C27B0; background: #9C27B01A;"></i>
                        <h3>Suporte Especializado</h3>
                        <p>Equipe técnica pronta para ajudar via chat, e-mail ou telefone.</p>
                    </div>
                    <div class="feature-card reveal-item">
                        <i class="fas fa-seedling" style="color: #FBC02D; background: #FBC02D1A;"></i>
                        <h3>Foco Sustentável</h3>
                        <p>Variedade de insumos e filamentos biodegradáveis e ecológicos.</p>
                    </div>
                    <div class="feature-card reveal-item">
                        <i class="fas fa-thumbs-up" style="color: #03A9F4; background: #03A9F41A;"></i>
                        <h3>Qualidade Premium</h3>
                        <p>Trabalhamos apenas com marcas e fornecedores líderes no setor maker.</p>
                    </div>
                    <div class="feature-card reveal-item">
                        <i class="fas fa-users" style="color: #E91E63; background: #E91E631A;"></i>
                        <h3>Comunidade Maker</h3>
                        <p>Acesso exclusivo a tutoriais, projetos e nossa rede ativa de criadores.</p>
                    </div>
                </div>
            </div>
        </section>

      <section id="mais-vendidos" class="best-sellers-products reveal-item">
    <div class="container">
        <h2>Mais Vendidos</h2>
        <p class="subtitle" style="color: var(--text-color);">O que está em alta na comunidade maker brasileira.</p>
        
        <?php if ($homeProducts === null): ?>
            <p role="status">Vitrine temporariamente indisponível.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($homeProducts as $product): ?>
                    <?php require __DIR__ . '/../includes/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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

    <script>
        // --- SCROLL REVEAL ---
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal-item').forEach(item => {
                observer.observe(item);
            });
            
            // --- FUNÇÕES DE CARROSSEL DE APLICAÇÕES ---
            const tabButtons = document.querySelectorAll('.tab-btn');
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('#applications-indicators .indicator-dot');

            function showSlide(target) {
                // Remove 'active' de todos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(ind => ind.classList.remove('active'));

                // Adiciona 'active' ao slide, botão e indicador correspondente
                const activeSlide = document.querySelector(`.carousel-slide[data-slide="${target}"]`);
                const activeBtn = document.querySelector(`.tab-btn[data-target="${target}"]`);
                const activeIndicator = document.querySelector(`#applications-indicators .indicator-dot[data-target="${target}"]`);
                
                if (activeSlide) activeSlide.classList.add('active');
                if (activeBtn) activeBtn.classList.add('active');
                if (activeIndicator) activeIndicator.classList.add('active');
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const target = e.target.closest('.tab-btn').dataset.target;
                    showSlide(target);
                });
            });

            indicators.forEach(indicator => {
                 indicator.addEventListener('click', (e) => {
                    const target = e.target.closest('.indicator-dot').dataset.target;
                    showSlide(target);
                });
            });

            // Inicializa o primeiro slide como ativo
            showSlide('colecionador');
        });
        

        // --- LÓGICA BÁSICA DO CARROSSEL (Precisa ser implementada para carrossel funcional) ---
        // A lógica de navegação e indicadores dos carrosseis (product-grid) deve ser adicionada aqui
        // usando as classes .left-btn, .right-btn e .carousel-indicators.
        
    </script>
    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
