-- Estoque fictício. Execute após database.sql somente em uma base de demonstração nova.
-- O pack STL usa unidades fictícias finitas para exercitar o mesmo fluxo do carrinho.
UPDATE products
SET stock_quantity = CASE id
    WHEN 1 THEN 8
    WHEN 2 THEN 5
    WHEN 3 THEN 3
    WHEN 4 THEN 18
    WHEN 5 THEN 30
    WHEN 6 THEN 22
    WHEN 7 THEN 7
    WHEN 8 THEN 2
    WHEN 9 THEN 14
    WHEN 10 THEN 100
    WHEN 11 THEN 9
    WHEN 12 THEN 16
END
WHERE id BETWEEN 1 AND 12
  AND stock_quantity = 0
  AND NOT EXISTS (SELECT 1 FROM orders);
