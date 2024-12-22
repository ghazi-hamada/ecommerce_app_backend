CREATE
OR REPLACE VIEW myfavorite AS
SELECT
    favorite.*,
    items.*,
    users.users_id,
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
    favorite
    INNER JOIN users ON users.users_id = favorite.favorite_usersid
    INNER JOIN items ON items.items_id = favorite.favorite_itemsid -- =============================================================
    CREATE
    OR REPLACE VIEW mycart AS
SELECT
    cart.*,
    items.*
FROM
    cart
    INNER JOIN items ON items.items_id = cart.cart_itemsid
WHERE
    cart.cart_orders = 0;

--  SELECT cart.*, items.*,(items.items_price *(1-(items.items_discount /100))) AS pric_after_dis ,(cart_quantity * (items.items_price *(1-(items.items_discount /100)))) AS total FROM cart  
-- INNER JOIN items ON items.items_id = cart.cart_itemsid;
CREATE
OR REPLACE VIEW items1view AS
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

CREATE
OR REPLACE VIEW ordersDetailsView AS
SELECT
    cart.*,
    items.*
FROM
    cart
    INNER JOIN items ON items.items_id = cart.cart_itemsid
WHERE
    cart.cart_orders != 0;

-- =============================================================
CREATE TABLE rating (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    rating_userid INT NOT NULL,
    rating_itemid INT NOT NULL,
    rating_value INT CHECK (
        rating_value BETWEEN 1
        AND 5
    ),
    rating_note varchar(255) DEFAULT "no note",
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (rating_userid, rating_itemid),
    -- هذا القيد يمنع التقييم المكرر لنفس المنتج من نفس المستخدم
    FOREIGN KEY (rating_userid) REFERENCES users(users_id) ON DELETE CASCADE,
    FOREIGN KEY (rating_itemid) REFERENCES items(items_id) ON DELETE CASCADE
);

-- =============================================================
CREATE TABLE banners (
    banners_id INT AUTO_INCREMENT PRIMARY KEY,
    banners_title VARCHAR(50) NOT NULL,
    banners_description VARCHAR(100) NOT NULL,
    banners_image_url VARCHAR(255) NOT NULL,
    banners_redirect_url VARCHAR(255),
    banners_start_date DATETIME,
    banners_end_date DATETIME,
    banners_status ENUM('active', 'inactive') DEFAULT 'active'
);

-- =============================================================
CREATE
OR REPLACE VIEW itemstopselling AS
SELECT
    COUNT(cart_id) AS countitems,
    cart.*,
    items.*,
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
    cart
    INNER JOIN items ON items.items_id = cart.cart_itemsid
WHERE
    cart_orders != 0
GROUP BY
    cart_itemsid;