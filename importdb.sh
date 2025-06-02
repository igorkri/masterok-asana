#!/bin/bash

DB_CONTAINER="masterok-asana_db_1"
DB_NAME="ms_asana"
DB_USER="root"
DB_PASS="my_password_root"
DUMP_FILE="frontend/runtime/igork847_asana.sql"

echo "🧹 Удаление всех таблиц из базы данных $DB_NAME..."

TABLES=$(docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS -Nse "SELECT table_name FROM information_schema.tables WHERE table_schema='$DB_NAME';")

for TABLE in $TABLES; do
    echo "❌ Удаляем таблицу: $TABLE"
    docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS \`$TABLE\`; SET FOREIGN_KEY_CHECKS = 1;"
done

echo "📥 Імпорт дампа..."
docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS $DB_NAME < $DUMP_FILE

echo "🔁 Скидання AUTO_INCREMENT на MAX(id) + 1..."

# Отримуємо список таблиць з колонкою `id`
TABLES_WITH_ID=$(docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS -Nse "
    SELECT table_name
    FROM information_schema.columns
    WHERE table_schema = '$DB_NAME' AND column_name = 'id';
")

for TABLE in $TABLES_WITH_ID; do
    MAX_ID=$(docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS -Nse "
        SELECT IFNULL(MAX(id), 0) + 1 FROM \`$DB_NAME\`.\`$TABLE\`;
    ")
    echo "🛠 Установка AUTO_INCREMENT = $MAX_ID для таблиці $TABLE"
    docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS -e "
        ALTER TABLE \`$DB_NAME\`.\`$TABLE\` AUTO_INCREMENT = $MAX_ID;
    "
done

echo "✅ Готово!"
