mysql -u root -p
CREATE USER 'novelhub_admin'@'localhost' IDENTIFIED BY 'thecocoatrees';
GRANT ALL PRIVILEGES ON novelhub_admin.* TO 'novelhub_admin'@'localhost';
FLUSH PRIVILEGES;