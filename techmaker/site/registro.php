<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../services/AuthService.php';

function auth_redirect(string $mode): never
{
    header('Location: registro.php?modo=' . $mode, true, 303);
    exit;
}

function auth_field(string $field): string
{
    return is_string($_POST[$field] ?? null) ? $_POST[$field] : '';
}

function escape_auth(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

start_store_session();
header('Cache-Control: no-store');
$mode = $_GET['modo'] ?? 'cadastro';
$login = $mode === 'login';
$user = current_user();
$error = '';
$notice = $_SESSION['auth_notice'] ?? '';
unset($_SESSION['auth_notice']);
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = auth_field('action');
    $login = $action === 'login';

    try {
        if (!valid_csrf_token($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            throw new DomainException('O formulário expirou. Tente novamente.');
        }
        if ($action === 'logout') {
            require_user();
            change_authenticated_user(null);
            auth_redirect('login');
        }
        if ($user !== null) {
            auth_redirect('conta');
        }
        if ($action !== 'cadastro' && !$login) {
            http_response_code(400);
            throw new DomainException('Operação inválida.');
        }

        $name = trim(auth_field('name'));
        $email = strtolower(trim(auth_field('email')));
        $password = auth_field('password');
        if (strlen($email) > 254 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new DomainException('Informe um e-mail válido.');
        }

        $passwordFits = strlen($password) <= 72 && !str_contains($password, "\0");
        if ($login) {
            $account = $passwordFits ? authenticate_user($email, $password) : null;
            if ($account === null) {
                throw new DomainException('E-mail ou senha inválidos.');
            }
            change_authenticated_user($account);
            if (!empty($_SESSION['resume_checkout'])) {
                unset($_SESSION['resume_checkout']);
                header('Location: checkout.php', true, 303);
                exit;
            }
            auth_redirect('conta');
        }

        if (!preg_match('/\A[^\p{C}]{1,150}\z/u', $name)) {
            throw new DomainException('Informe um nome válido com até 150 caracteres.');
        }
        if (!$passwordFits || !preg_match('/\A.{8,}\z/us', $password)) {
            throw new DomainException('Use uma senha de pelo menos 8 caracteres e no máximo 72 bytes.');
        }
        if ($password !== auth_field('confirm-password')) {
            throw new DomainException('A confirmação da senha deve ser igual à senha.');
        }
        if (auth_field('terms') !== 'on') {
            throw new DomainException('Confirme a opção de aceite para continuar.');
        }

        register_user($name, $email, $password);
        $_SESSION['auth_notice'] = 'Cadastro concluído. Faça login para acessar sua conta.';
        auth_redirect('login');
    } catch (DomainException $exception) {
        $error = $exception->getMessage();
    } catch (RuntimeException $exception) {
        error_log('Falha na operação de autenticação. Código: ' . $exception->getCode());
        http_response_code(503);
        $error = 'Serviço temporariamente indisponível. Tente novamente.';
    }
} elseif ($mode === 'conta') {
    $user = require_user();
} elseif ($user !== null) {
    auth_redirect('conta');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - TechMaker 3D</title>
   <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="header.css">
     <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php $analyticsPage = 'account'; require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>

    <?php require __DIR__ . '/../includes/cart-panel.php'; ?>

     <header class="main-header">
        <div class="container header-content">
            <a href="index.php" class="logo">TechMaker</a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Página Principal</a></li>
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
                </ul>
            </nav>

            <div class="header-actions">
                <?php require __DIR__ . '/../includes/catalog-search.php'; ?>
                <a href="registro.php" class="action-icon" title="Minha Conta"><i class="fas fa-user"></i></a>
                
                <?php require __DIR__ . '/../includes/cart-trigger.php'; ?>
            </div>
        </div>
    </header>

    <section class="register-section">
        <div class="container">
            <div class="register-box">
                <?php if ($error !== ''): ?>
                    <p class="subtitle" role="alert"><?= escape_auth($error) ?></p>
                <?php elseif ($notice !== ''): ?>
                    <p class="subtitle" role="status"><?= escape_auth($notice) ?></p>
                <?php endif; ?>

                <?php if ($user !== null): ?>
                    <h2><i class="fas fa-user"></i> Minha Conta</h2>
                    <p class="subtitle">Olá, <?= escape_auth($user['name']) ?>.</p>
                    <form action="registro.php" method="POST" class="register-form">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="cta-button register-submit-btn">Sair da conta</button>
                    </form>
                <?php else: ?>
                <?php if ($login): ?>
                    <h2><i class="fas fa-user"></i> Acesse Sua Conta</h2>
                    <p class="subtitle">Entre com seu e-mail e sua senha.</p>
                <?php else: ?>
                <h2><i class="fas fa-user-plus"></i> Crie Sua Conta</h2>
                <p class="subtitle">Cadastre-se para ter acesso completo aos nossos produtos e serviços.</p>
                <?php endif; ?>

                <form action="registro.php" method="POST" class="register-form">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="<?= $login ? 'login' : 'cadastro' ?>">

                    <?php if (!$login): ?>
                    <div class="input-group">
                        <label for="name"><i class="fas fa-id-card"></i> Nome Completo</label>
                        <input type="text" id="name" name="name" placeholder="Seu nome completo" autocomplete="name" value="<?= escape_auth($name) ?>" required>
                    </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="email"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" id="email" name="email" placeholder="email@exemplo.com" autocomplete="username" maxlength="254" value="<?= escape_auth($email) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="password"><i class="fas fa-lock"></i> Senha</label>
                        <?php if ($login): ?>
                            <input type="password" id="password" name="password" autocomplete="current-password" required>
                        <?php else: ?>
                            <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" minlength="8" required>
                        <?php endif; ?>
                    </div>

                    <?php if (!$login): ?>
                    <div class="input-group">
                        <label for="confirm-password"><i class="fas fa-lock"></i> Confirmar Senha</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Repita a senha" autocomplete="new-password" minlength="8" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Eu concordo com os <a href="institucional.php#termos" class="form-link">Termos de Serviço</a> e <a href="privacidade.php" class="form-link">Política de Privacidade</a>.</label>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="cta-button register-submit-btn">
                        <i class="fas fa-check"></i> <?= $login ? 'Entrar' : 'Finalizar Cadastro' ?>
                    </button>
                    
                    <div class="login-link-box">
                        <?php if ($login): ?>
                            Ainda não tem uma conta? <a href="registro.php" class="form-link">Cadastre-se</a>
                        <?php else: ?>
                            Já tem uma conta? <a href="registro.php?modo=login" class="form-link">Faça Login</a>
                        <?php endif; ?>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
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
                &copy; 2025 TechMaker. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
