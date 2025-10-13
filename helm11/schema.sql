create database helm11;
use helm11;

CREATE TABLE products (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255)
);

CREATE TABLE orders (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE order_items (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    order_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO products (name, description, price, image_url) VALUES
('Helm Bogo Classic', 'Helm retro dengan desain klasik dan nyaman.', 250000.00, 'images/helm1.jpg'),
('Helm Full Face Racing', 'Helm full face untuk keamanan maksimal saat berkendara.', 750000.00, 'images/helm2.jpg'),
('Helm Half Face Urban', 'Helm stylish untuk penggunaan sehari-hari di perkotaan.', 350000.00, 'images/helm3.jpg');

CREATE TABLE admins (
     id INT AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(100) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL
);

 INSERT INTO admins (username, password) VALUES ('admin', MD5('admin123'));