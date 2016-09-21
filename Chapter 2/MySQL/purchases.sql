CREATE TABLE purchases (
    id INT(11) NOT NULL AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    address DECIMAL(6,2) NOT NULL,
    email DECIMAL(6,2) NOT NULL,
    credit_card VARCHAR(255) NOT NULL,
    items TEXT NOT NULL,
    total DECIMAL(6,2) NOT NULL,
    created DATETIME NOT NULL,
    PRIMARY KEY (id)
);

