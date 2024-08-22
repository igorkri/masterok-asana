FROM yiisoftware/yii2-php:8.2-apache

# Установка Midnight Commander
RUN apt-get update && apt-get install -y mc

# Изменение корневого каталога Apache на frontend/web
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf

# Убедитесь, что frontend/web директория существует
WORKDIR /app/frontend/web

# Копирование файлов проекта
COPY ./frontend /app/frontend

# Настройка прав доступа
RUN chown -R www-data:www-data /app/frontend/web

# Открытие порта 80 для фронтенда
EXPOSE 80
