sudo apt-get upgrade -y
sudo apt-get update -y

sudo apt install -y php php-mbstring php-bcmath composer rsyslog

cd ~/database

git clone  https://github.com/MattToegel/IT490.git

sudo apt install composer

composer install

composer require php-amqplib/php-amqplib

sudo apt install php-mysqli
