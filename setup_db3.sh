#install and get updates
sudo apt install -y php php-mbstring php-bcmath composer rsyslog
#make database directory
sudo mkdir database

cd database

#clone database repo
git clone https://github.com/sk2544/database.git

#composer installation
sudo apt install composer
composer install
composer require php-amqplib/php-amqplib
sudo apt install php-mysqli

#install mysql
sudo apt-get install mysql-server

sudo mysql_secure_installation

sudo mysql -u root -p

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Database1@';

GRANT ALL PRIVILEGES ON *.* TO 'administrator'@'localhost' IDENTIFIED BY 'Database1@';

CREATE DATABASE login;

CREATE TABLE users;

INSERT INTO users (username, password);

CREATE DATABASE register;

CREATE TABLE users;

INSERT INTO users (email, username, password);
