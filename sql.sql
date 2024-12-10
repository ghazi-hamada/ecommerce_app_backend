CREATE
OR REPLACE VIEW myfavorite AS
SELECT
    favorite.*,
    items.*,
    users.users_id
FROM
    favorite
    INNER JOIN users ON users.users_id = favorite.favorite_usersid
    INNER JOIN items ON items.items_id = favorite.favorite_itemsid
     -- =============================================================
    CREATE
    OR REPLACE VIEW mycart AS
SELECT
    cart.*,
    items.*
FROM
    cart
    INNER JOIN items ON items.items_id = cart.cart_itemsid
    WHERE cart.cart_orders = 0;

--  SELECT cart.*, items.*,(items.items_price *(1-(items.items_discount /100))) AS pric_after_dis ,(cart_quantity * (items.items_price *(1-(items.items_discount /100)))) AS total FROM cart  
-- INNER JOIN items ON items.items_id = cart.cart_itemsid;

CREATE OR REPLACE VIEW items1view AS
SELECT
    items.*,
    categories.*,
    CASE
        WHEN EXISTS (
            SELECT
                1
            FROM
                cart
            WHERE
                cart.cart_itemsid = items.items_id
                AND cart.cart_orders = 0
        ) THEN 1
        ELSE 0
    END AS item_in_cart
FROM
    items
    INNER JOIN categories ON items.items_cat = categories.categories_id;