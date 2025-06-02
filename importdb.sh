#!/bin/bash

DB_CONTAINER="masterok-asana_db_1"
DB_NAME="ms_asana"
DB_USER="root"
DB_PASS="my_password_root"

echo "🧹 Удаление всех таблиц из базы данных $DB_NAME..."

TABLES=$(docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS -Nse "SELECT table_name FROM information_schema.tables WHERE table_schema='$DB_NAME';")

for TABLE in $TABLES; do
    echo "❌ Удаляем таблицу: $TABLE"
    docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS \`$TABLE\`; SET FOREIGN_KEY_CHECKS = 1;"
done

echo "📥 Імпорт дампа..."
docker exec -i $DB_CONTAINER mysql -u$DB_USER -p$DB_PASS $DB_NAME < frontend/runtime/igork847_asana.sql
