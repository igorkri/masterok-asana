#!/bin/bash

# Detailed API Testing Script with validation
# Детальні тести API з перевіркою відповідей

BASE_URL="http://localhost/admin/api"
FAILED=0
PASSED=0

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo "=========================================="
echo "Detailed API Testing Suite"
echo "=========================================="
echo ""

# Helper function to test endpoint
test_api() {
    local test_name=$1
    local method=$2
    local endpoint=$3
    local data=$4
    local expected_status=$5

    echo -e "${BLUE}TEST:${NC} $test_name"

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

    # Check status code
    if [ "$http_code" == "$expected_status" ]; then
        echo -e "  ${GREEN}✓${NC} Status code: $http_code (expected $expected_status)"
        PASSED=$((PASSED + 1))

        # Validate JSON
        if echo "$body" | python3 -m json.tool > /dev/null 2>&1; then
            echo -e "  ${GREEN}✓${NC} Valid JSON response"
        else
            echo -e "  ${YELLOW}⚠${NC} Invalid JSON response"
        fi

        # Show preview
        echo -e "  ${GREEN}Response:${NC} $(echo $body | head -c 80)..."
    else
        echo -e "  ${RED}✗${NC} Status code: $http_code (expected $expected_status)"
        echo -e "  ${RED}Response:${NC} $body"
        FAILED=$((FAILED + 1))
    fi
    echo ""
}

# Test Timer API
echo "=========================================="
echo "Timer API Tests"
echo "=========================================="
echo ""

test_api "Get timers list" "GET" "/timer/list?per-page=5" "" "200"
test_api "Get timer statistics" "GET" "/timer/statistics?archive=0" "" "200"

# Test with filters
test_api "Get timers with status filter" "GET" "/timer/list?status=0&per-page=3" "" "200"
test_api "Get archived timers statistics" "GET" "/timer/statistics?archive=1" "" "200"

# Test invalid requests
test_api "Get non-existent timer" "GET" "/timer/view?id=999999" "" "404"

echo ""
echo "=========================================="
echo "ActOfWork API Tests"
echo "=========================================="
echo ""

test_api "Get acts list" "GET" "/act-of-work/list?per-page=5" "" "200"
test_api "Get acts statistics" "GET" "/act-of-work/statistics" "" "200"

# Test with filters
test_api "Get pending acts" "GET" "/act-of-work/list?status=pending&per-page=3" "" "200"
test_api "Get paid acts statistics" "GET" "/act-of-work/statistics?status=paid" "" "200"

# Test invalid requests
test_api "Get non-existent act" "GET" "/act-of-work/view?id=999999" "" "404"

echo ""
echo "=========================================="
echo "ActOfWorkDetail API Tests"
echo "=========================================="
echo ""

test_api "Get details list" "GET" "/act-of-work-detail/list?per-page=5" "" "200"
test_api "Get details statistics" "GET" "/act-of-work-detail/statistics" "" "200"

# Test with filters
test_api "Get details by act" "GET" "/act-of-work-detail/list?act_of_work_id=1" "" "200"

# Test invalid requests
test_api "Get non-existent detail" "GET" "/act-of-work-detail/view?id=999999" "" "404"

echo ""
echo "=========================================="
echo "Test Results"
echo "=========================================="
echo ""

TOTAL=$((PASSED + FAILED))
echo -e "${GREEN}Passed:${NC} $PASSED / $TOTAL"
echo -e "${RED}Failed:${NC} $FAILED / $TOTAL"

if [ $FAILED -eq 0 ]; then
    echo -e "\n${GREEN}✓ All tests passed!${NC}"
    exit 0
else
    echo -e "\n${RED}✗ Some tests failed${NC}"
    exit 1
fi

