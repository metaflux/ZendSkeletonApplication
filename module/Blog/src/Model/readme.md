# Create MySQL user #

mysql -u root -p

CREATE USER 'zf'@'localhost' IDENTIFIED BY 'zf';
CREATE USER 'zf'@'%' IDENTIFIED BY 'zf';

GRANT ALL PRIVILEGES ON *.* TO 'zf'@'localhost';
GRANT ALL PRIVILEGES ON *.* TO 'zf'@'%';