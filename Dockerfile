FROM yiisoftware/yii2-php:8.0-apache

# Установка Midnight Commander, Vim и других необходимых пакетов
RUN apt update && apt install -y mc vim

# Копирование всех файлов проекта
COPY . /app
# Создание конфигурационного файла Apache для masterok-asana.loc
RUN echo "<VirtualHost *:80> \
    \n    ServerName masterok-asana.loc \
    \n    ServerAlias www.masterok-asana.loc \
    \n    ServerAdmin webmaster@masterok-asana.loc \
    \n    DocumentRoot /app \
    \n    <Directory /app> \
    \n        Options -Indexes \
    \n        Require all granted \
    \n        Options FollowSymLinks \
    \n        AllowOverride All \
    \n    </Directory> \
    \n    ErrorLog \${APACHE_LOG_DIR}/error-masterok-asana.log \
    \n    CustomLog \${APACHE_LOG_DIR}/access-masterok-asana.log combined \
    \n</VirtualHost>" > /etc/apache2/sites-available/masterok-asana.conf

RUN chown -R www-data:www-data /app

# Активируем виртуальный хост
RUN a2ensite masterok-asana.conf

# Деактивируем дефолтный виртуальный хост, если необходимо
RUN a2dissite 000-default.conf

# Настройка прав доступа
RUN chown -R www-data:www-data /app/frontend/web /app/backend/web /app/common /app/vendor /app/environments

# Включение модуля переписывания URL (mod_rewrite)
RUN a2enmod rewrite

# Открытие порта 80
EXPOSE 80
