FROM yiisoftware/yii2-php:8.2-apache

# Установка Midnight Commander
RUN apt-get update && apt-get install -y mc

# Изменение корневого каталога Apache на backend/web
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

# Убедитесь, что backend/web директория существует
WORKDIR /app/backend/web

# Копирование файлов проекта
COPY ./backend /app/backend

# Настройка прав доступа
RUN chown -R www-data:www-data /app/backend/web

# Открытие порта 80 для бэкенда
EXPOSE 80
