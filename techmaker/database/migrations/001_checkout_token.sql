-- Aplique somente a bancos criados antes da etapa de pedidos.
ALTER TABLE orders
    ADD COLUMN checkout_token CHAR(64) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL UNIQUE;
