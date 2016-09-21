CREATE TABLE shopping_items (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
   image VARCHAR(255) NOT NULL,
PRIMARY KEY  (id)
);


INSERT INTO `shopping_items` VALUES (NULL,'Tablet', '199.99', 'tablet.png');
INSERT INTO `shopping_items` VALUES (NULL, 'Cellphone', '199.99', 'cellphone.png');
INSERT INTO `shopping_items` VALUES (NULL,'Laptop', '599.99', 'laptop.png');
INSERT INTO `shopping_items` VALUES (NULL,'Cable', '14.99', 'cable.png');
INSERT INTO `shopping_items` VALUES (NULL, 'Watch', '99.99', 'watch.png');
