# Create MySQL user #

mysql -u root -p

CREATE USER 'zf'@'localhost' IDENTIFIED BY 'zf';
CREATE USER 'zf'@'%' IDENTIFIED BY 'zf';

GRANT ALL PRIVILEGES ON *.* TO 'zf'@'localhost';
GRANT ALL PRIVILEGES ON *.* TO 'zf'@'%';

CREATE DATABASE `zf`;

use zf;
CREATE TABLE `posts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL DEFAULT '0',
	`text` TEXT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin2_general_ci'
ENGINE=InnoDB;

INSERT INTO posts (title, text) VALUES ('Blog #1', 'Welcome to my first blog post');
INSERT INTO posts (title, text) VALUES ('Blog #2', 'Welcome to my second blog post');
INSERT INTO posts (title, text) VALUES ('Blog #3', 'Welcome to my third blog post');
INSERT INTO posts (title, text) VALUES ('Blog #4', 'Welcome to my fourth blog post');
INSERT INTO posts (title, text) VALUES ('Blog #5', 'Welcome to my fifth blog post');