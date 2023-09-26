Настройка микрокомпьютера (установка Yii)
========================

Создаём пользователя с рут правами  
Примерная инструкция - `https://www.digitalocean.com/community/tutorials/how-to-create-a-new-sudo-enabled-user-on-ubuntu-18-04-quickstart-ru`

Делаем logout, и входим уже под нашим новым пользователем

Устанавливаем нужный софт

    sudo apt install apache2 php git php8.1-zip php8.1-intl php8.1-mbstring  sqlite3 libsqlite3-dev php-sqlite3 curl php8.1-curl \
    php8.1-xml php8.1-gd php8.1-imagick  libapache2-mod-php




Проставляем права и устанавливаем composer

    sudo su
    cd /var/www/html
    rm -rf *
    chmod 777 -R  /var/www/html
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    exit  

Возвращаемся обратно к пользователю, клонируем репозиторий в нужную нам папку, подтягиваем зависимости

    cd /var/www/html
    git clone https://github.com/kvderevyanko/gk-server .
    composer update

Запускаем команды для yii. При запуске команды app/start в консоль выведется инструкция, что  нужно сделать (задание для
крона и права доступа к базе)

    cd /var/www/html
    php yii app/start
    php yii migrate

Удаляем стандартный файл конфигурации апача, заполняем своими настройками

    sudo rm /etc/apache2/sites-available/000-default.conf
    sudo nano /etc/apache2/sites-available/000-default.conf

Записываем в файл данные. Если путь установки yii отличается от /var/www/html/, то заменяем на свой путь.

    <VirtualHost *:80>

    DocumentRoot /var/www/html/web  

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    
    <Directory "/var/www/html/web">
        RewriteEngine on
    
        # Если запрашиваемая в URL директория или файл существуют обращаемся к ним напрямую
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # Если нет - перенаправляем запрос на index.php
        RewriteRule . index.php
    </Directory>
    
    </VirtualHost>

Ставим модуль апача и делаем его рестарт.

    sudo a2enmod rewrite
    sudo service apache2 restart

Если ошибок после рестарта нет, значит всё ок.



Получение температуры для крона:

    php yii device/temperature  

Cron каждые 10 минут для получения температуры

    */10 * * * * php  /var/www/html/yii device/temperature

Запуск мотора раз в минуту/раз в 30 секунд

    * * * * * php  /var/www/html/yii device/motor
    * * * * * sleep 30; php  /var/www/html/yii device/motor

Запуск команд

    * * * * * php  /var/www/html/yii command

