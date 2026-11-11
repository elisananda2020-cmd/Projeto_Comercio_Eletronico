-- Importe em um banco MySQL vazio com utf8mb4.
SET NAMES utf8mb4;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(254) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
    promotional_price DECIMAL(10, 2) DEFAULT NULL,
    image_url VARCHAR(2048) NOT NULL,
    image_alt VARCHAR(200) NOT NULL,
    stock_quantity INT UNSIGNED NOT NULL DEFAULT 0,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    badge VARCHAR(30) DEFAULT NULL,
    installment_label VARCHAR(40) NOT NULL,
    INDEX idx_products_category (category),
    CHECK (promotional_price IS NULL OR (promotional_price >= 0 AND promotional_price < price))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    total DECIMAL(14, 2) NOT NULL CHECK (total >= 0),
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    checkout_token CHAR(64) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10, 2) NOT NULL CHECK (unit_price >= 0),
    UNIQUE KEY uq_order_items_product (order_id, product_id),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id),
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estoque inicial zero: o catálogo original não informa quantidades.
INSERT INTO products
    (name, description, category, price, promotional_price, image_url, image_alt, is_featured, badge, installment_label)
VALUES
('Ender 3 V3 SE - Impressora 3D FDM', 'Impressora 3D com nivelamento automático...', 'Impressora 3D', 1599.00, 1299.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQsB_OLW8tlcAa9M9obHA7UgPbzDxc-A4puFw&s', 'Ender 3 V3 SE', TRUE, 'Destaque', 'em até 12x'),
('Elegoo Mars 4 Ultra - Impressora 3D Resina', 'Impressora de resina com tela LCD 9K para detalhes...', 'Impressora 3D', 2499.00, NULL, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8c8GrsI96SDG5KYRBPYOtC2OdqTS2WwyEkQ&s', 'Elegoo Mars 4 Ultra', TRUE, 'Destaque', 'em até 12x'),
('Cortadora Laser ATOMSTACK A5 PRO...', 'Cortadora e gravadora laser 40W com grande ár...', 'Cortadora Laser', 4290.00, 3899.00, 'https://promo.idealdistribuidora.com/wp-content/webp-express/webp-images/uploads/2022/06/mandala-mdf.png.webp', 'Cortadora Laser ATOMSTACK', TRUE, 'Destaque', 'em até 12x'),
('Arduino Mega 2560 R3 - Kit Completo', 'Kit Arduino Mega com mais de 200 componente...', 'Arduino', 449.00, 389.00, 'https://i.all3dp.com/wp-content/uploads/2020/08/20160418/Arduino-Robokitten.jpeg', 'Arduino Mega 2560 R3', TRUE, 'Destaque', 'em até 12x'),
('Filamento PLA Premium 1kg - Preto', 'Material de alta qualidade para impressoras FDM.', 'Filamentos', 119.90, NULL, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8c8GrsI96SDG5KYRBPYOtC2OdqTS2WwyEkQ&s', 'Filamento PLA', FALSE, 'Novo', 'em até 3x'),
('Kit 37 Sensores para Arduino', 'Comece seus projetos de IoT e robótica.', 'Arduino & Eletrônica', 199.00, 149.00, 'https://i.all3dp.com/wp-content/uploads/2023/01/17134314/spooky-tksgeo-via-printables-230103_download-e1673959997743.jpg', 'Kit de Sensores', FALSE, NULL, 'em até 6x'),
('Anycubic Kobra 2 Neo - Fast Print', 'Impressora de alta velocidade para iniciantes.', 'Impressora 3D', 1699.00, NULL, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRyK0jVG61KQiWznLvF29IOWaKanYYy5eMQGA&s', 'Anycubic Kobra 2 Neo', FALSE, 'Novo', 'em até 10x'),
('Phrozen Sonic Mega 8K (Volume Grande)', 'Impressora de resina com volume massivo.', 'Impressora 3D', 7999.00, 7199.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR5dzWIRSgHb6MrP9uetqnEjz-zgcJqe9qAaQ&s', 'Phrozen Sonic Mega 8K', FALSE, NULL, 'em até 12x'),
('Kit 4 Ganchos de Bolsa/Mochila (Modelo 3D)', 'Acessórios utilitários e ergonômicos para impressão.', 'Acessórios', 39.90, NULL, 'https://i.all3dp.com/workers/images/fit=scale-down,w=1200,gravity=0.5x0.5,format=auto/wp-content/uploads/2024/08/06113540/Bag-Coat-Hanger.jpg', 'Kit 4 Ganchos de Bolsa', FALSE, 'Oferta', 'em até 2x'),
('Pack 10 Projetos 3D Essenciais (STL)', 'Arquivos digitais para impressão de objetos úteis.', 'Kits e Projetos', 49.90, NULL, 'https://wishbox.net.br/wp-content/uploads/2019/12/10-projetos-legais-e-uteis-para-imprimir-em-3d.jpg', 'Pack de Modelos 3D', FALSE, 'Digital', 'Pagamento Único'),
('Placa-Mãe SKR Mini E3 V3.0 (32 Bits)', 'Upgrade silencioso e de alta performance para Ender 3.', 'Peças e Componentes', 399.00, 319.20, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTR8Li1GexqMkd5BYTaqk0rgYI8fmm06g3fRg&s', 'Placa de Controle Impressora', FALSE, NULL, 'em até 8x'),
('Filamento PLA Madeira (Wood) - 500g', 'Material com acabamento e textura de madeira real.', 'Filamentos', 139.90, NULL, 'https://www.smooth3d.com.br/files/2023/02/IMG_1008-1.JPG', 'Filamento PLA Madeira', FALSE, 'Premium', 'em até 4x');
