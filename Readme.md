# MVP of system which is responsible for handling the creation of orders

# Message Description

# Request
| Element | Description | Remarks |
| ------ | ------ | ------ |
| product_list | list of products (including quantity)  | required |
| country_code | FI/PL/NL/IE  | required |
| invoice_format | JSON/HTML  | required |
| return_type | EMAIL/JSON  | required |
| email |  | required if return_type is EMAIL |

Eg: Request
```sh
http://{localhost:8000}/orderhandling/getproduct.php
{
    "product_list": {
        "Milk":2,
        "Salt":3
        },
    "country_code": "FI",
    "invoice_format" : "JSON",
    "return_type" : "JSON",
    "email" : "sandesh.hyoju@gmail.comm"
}
```


# MySql Table and Dummy data
```sh
CREATE TABLE IF NOT EXISTS country (
	country_id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL UNIQUE,
	short_name VARCHAR(4) NOT NULL UNIQUE,
	currency_code VARCHAR(10) NOT NULL,
	currency_symbol VARCHAR(10) NOT NULL,
	PRIMARY KEY (country_id)
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS tax_category (
	tax_category_id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL UNIQUE,
	PRIMARY KEY (tax_category_id)
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS tax_rate (
	tax_rate_id INT NOT NULL AUTO_INCREMENT,
	country_id INT NOT NULL,
	tax_category_id INT NOT NULL,
	tax_rate INT NOT NULL,
	PRIMARY KEY (tax_rate_id),
	FOREIGN KEY (country_id) REFERENCES country (country_id),
	FOREIGN KEY (tax_category_id) REFERENCES tax_category (tax_category_id)
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS product_category (
	product_category_id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY (product_category_id)
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS product (
	product_id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	code VARCHAR(50) NOT NULL,
	product_category_id INT NOT NULL,
	tax_category_id INT NOT NULL,
	PRIMARY KEY (product_id),
	CONSTRAINT product_name_code_unique UNIQUE(name, code),
	FOREIGN KEY (product_category_id) REFERENCES product_category (product_category_id),
	FOREIGN KEY (tax_category_id) REFERENCES tax_category (tax_category_id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS product_price (
	product_price_id INT NOT NULL AUTO_INCREMENT,
	country_id INT NOT NULL,
	product_id INT NOT NULL,
	price FLOAT NOT NULL,
	PRIMARY KEY (product_price_id),
	FOREIGN KEY (country_id) REFERENCES country (country_id),
	FOREIGN KEY (product_id) REFERENCES product (product_id)
) ENGINE=INNODB;











INSERT INTO country VALUES 
(1, 'Finland', 'FI', 'EUR', '&euro;'),
(2, 'Poland', 'PL', 'PLN', 'zl'),
(3, 'Ireland', 'IE', 'EUR', '&euro;'),
(4, 'Neatherland', 'NL', 'EUR', '&euro;');

INSERT INTO tax_category VALUES 
(1, 'Category 1'),
(2, 'Category 2'),
(3, 'Category 3'),
(4, 'Category 4');


INSERT INTO tax_rate VALUES 
(1, 1, 1, 14),
(2, 1, 2, 20),
(3, 1, 3, 22),
(4, 1, 4, 24),

(5, 2, 1, 5),
(6, 2, 2, 5),
(7, 2, 3, 9),
(8, 2, 4, 13),

(9, 3, 1, 1),
(10, 3, 2, 6),
(11, 3, 3, 8),
(12, 3, 4, 12),


(13, 4, 1, 0),
(14, 4, 2, 3),
(15, 4, 3, 8),
(16, 4, 4, 10);



INSERT INTO product_category VALUES 
(1, 'Grocery'),
(2, 'Electronics'),
(3, 'Clothes');



INSERT INTO product VALUES 
(1, 'Milk', 'C2-123456', 1, 1),
(2, 'Salt', 'C1-234567', 1, 1),
(3, 'Sugar', 'C1-345678', 1, 1),
(4, 'Oil', 'C1-456789', 1, 1),
(5, 'RAM', 'C4-987654', 2, 4),
(6, 'Mobile-1', 'C4-876543', 2, 4),
(7, 'Mobile-2', 'C4-765432', 2, 4),
(8, 'Computer-1', 'C4-654321', 2, 4),
(9, 'Computer-2', 'C4-543210', 2, 4),
(10, 'T-shirt', 'C3-159264', 3, 3),
(11, 'Shirt', 'C3-489516', 3, 3),
(12, 'Pant', 'C3-753426', 3, 3),
(13, 'Cheese', 'C2-654984', 1, 2),
(14, 'Meat product', 'C3-963547', 1, 3),
(15, 'Fresh vegetable', 'C2-689325', 1, 2),
(16, 'Soap', 'C1-698563', 1, 1),
(17, 'Hard disk', 'C4-998875', 2, 4),
(18, 'Pen drive', 'C4-658895', 2, 4),
(19, 'Jacket', 'C3-665245', 3, 3),
(20, 'Sock', 'C2-365985', 3, 2);


INSERT INTO product_price VALUES 
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3),
(4, 1, 4, 4),
(5, 1, 5, 5),
(6, 1, 6, 6),
(7, 1, 7, 7),
(8, 1, 8, 8),
(9, 1, 9, 9),
(10, 1, 10, 10),
(11, 1, 11, 11),
(12, 1, 12, 12),
(13, 1, 13, 13),
(14, 1, 14, 14),
(15, 1, 15, 15),
(16, 1, 16, 16),
(17, 1, 17, 17),
(18, 1, 18, 18),
(19, 1, 19, 19),
(20, 1, 20, 20),

(21, 2, 1, 11),
(22, 2, 2, 12),
(23, 2, 3, 13),
(24, 2, 4, 14),
(25, 2, 5, 15),
(26, 2, 6, 16),
(27, 2, 7, 17),
(28, 2, 8, 18),
(29, 2, 9, 19),
(30, 2, 10, 20),
(31, 2, 11, 21),
(32, 2, 12, 22),
(33, 2, 13, 23),
(34, 2, 14, 24),
(35, 2, 15, 25),
(36, 2, 16, 26),
(37, 2, 17, 27),
(38, 2, 18, 28),
(39, 2, 19, 29),
(40, 2, 20, 30),

(41, 3, 1, 21),
(42, 3, 2, 22),
(43, 3, 3, 23),
(44, 3, 4, 24),
(45, 3, 5, 25),
(46, 3, 6, 26),
(47, 3, 7, 27),
(48, 3, 8, 28),
(49, 3, 9, 29),
(50, 3, 10, 30),
(51, 3, 11, 31),
(52, 3, 12, 32),
(53, 3, 13, 33),
(54, 3, 14, 34),
(55, 3, 15, 35),
(56, 3, 16, 36),
(57, 3, 17, 37),
(58, 3, 18, 38),
(59, 3, 19, 39),
(60, 3, 20, 40),

(61, 4, 1, 31),
(62, 4, 2, 32),
(63, 4, 3, 33),
(64, 4, 4, 34),
(65, 4, 5, 35),
(66, 4, 6, 36),
(67, 4, 7, 37),
(68, 4, 8, 38),
(69, 4, 9, 39),
(70, 4, 10, 40),
(71, 4, 11, 41),
(72, 4, 12, 42),
(73, 4, 13, 43),
(74, 4, 14, 44),
(75, 4, 15, 45),
(76, 4, 16, 46),
(77, 4, 17, 47),
(78, 4, 18, 48),
(79, 4, 19, 49),
(80, 4, 20, 50);




```