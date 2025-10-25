#!/bin/bash

# API Testing Script
# Тестування всіх створених API endpoints

BASE_URL="http://localhost/admin/api"

echo "=========================================="
echo "API Testing Script"
echo "=========================================="
echo ""

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Функция для тестирования endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local data=$3
    local description=$4

    echo -e "${YELLOW}Testing:${NC} $description"
    echo -e "  ${YELLOW}Method:${NC} $method"
    echo -e "  ${YELLOW}URL:${NC} $BASE_URL$endpoint"

    if [ "$method" == "GET" ]; then
        response=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL$endpoint")
    elif [ "$method" == "POST" ]; then
        response=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL$endpoint" \
            -H "Content-Type: application/json" \
            -d "$data")
    elif [ "$method" == "PUT" ]; then
        response=$(curl -s -w "\n%{http_code}" -X PUT "$BASE_URL$endpoint" \
            -H "Content-Type: application/json" \
            -d "$data")
    elif [ "$method" == "DELETE" ]; then
        response=$(curl -s -w "\n%{http_code}" -X DELETE "$BASE_URL$endpoint")
    fi

    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n-1)

    if [ "$http_code" -ge 200 ] && [ "$http_code" -lt 300 ]; then
        echo -e "  ${GREEN}✓ Status: $http_code${NC}"
        echo -e "  ${GREEN}Response:${NC} $(echo $body | head -c 100)..."
    else
        echo -e "  ${RED}✗ Status: $http_code${NC}"
        echo -e "  ${RED}Response:${NC} $body"
    fi
    echo ""
}

echo "=========================================="
echo "Timer API Tests"
echo "=========================================="
echo ""

# Timer API Tests
test_endpoint "GET" "/timer/list?per-page=5" "" "Отримати список таймерів"
test_endpoint "GET" "/timer/statistics?archive=0" "" "Отримати статистику таймерів"

echo ""
echo "=========================================="
echo "ActOfWork API Tests"
echo "=========================================="
echo ""

# ActOfWork API Tests
test_endpoint "GET" "/act-of-work/list?per-page=5" "" "Отримати список актів"
test_endpoint "GET" "/act-of-work/statistics" "" "Отримати статистику актів"

echo ""
echo "=========================================="
echo "ActOfWorkDetail API Tests"
echo "=========================================="
echo ""

# ActOfWorkDetail API Tests
test_endpoint "GET" "/act-of-work-detail/list?per-page=5" "" "Отримати список деталей актів"
test_endpoint "GET" "/act-of-work-detail/statistics" "" "Отримати статистику деталей"

echo ""
echo "=========================================="
echo "Testing Complete"
echo "=========================================="
echo ""
echo "Для детального тестування використовуйте:"
echo "  - Документацію в docs/api.md"
echo "  - JavaScript приклади в docs/api-javascript-examples.md"
echo "  - Postman або Insomnia для інтерактивного тестування"
echo ""

