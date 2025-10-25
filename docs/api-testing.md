# 🧪 Тестування API

Для API створено три типи тестів: швидкі bash-тести, детальні bash-тести та PHPUnit тести.

---

## 📋 Доступні тести

### 1. Швидкий тест (test-api.sh)
**Файл:** `/test-api.sh`

**Опис:** Швидка перевірка доступності основних API endpoints

**Запуск:**
```bash
./test-api.sh
```

**Що тестує:**
- ✅ GET /api/timer/list
- ✅ GET /api/timer/statistics
- ✅ GET /api/act-of-work/list
- ✅ GET /api/act-of-work/statistics
- ✅ GET /api/act-of-work-detail/list
- ✅ GET /api/act-of-work-detail/statistics

**Результат:**
```
==========================================
API Testing Script
==========================================

Testing: Отримати список таймерів
  ✓ Status: 200
  Response: [{"id":608...

✓ All endpoints accessible
```

---

### 2. Детальний тест (test-api-detailed.sh)
**Файл:** `/test-api-detailed.sh`

**Опис:** Повне тестування API з валідацією JSON та перевіркою статус-кодів

**Запуск:**
```bash
./test-api-detailed.sh
```

**Що тестує:**
- ✅ Всі GET endpoints з різними фільтрами
- ✅ Валідація JSON відповідей
- ✅ Перевірка статус-кодів (200, 404)
- ✅ Тестування неіснуючих ресурсів
- ✅ Фільтрація даних

**Результат:**
```
==========================================
Detailed API Testing Suite
==========================================

TEST: Get timers list
  ✓ Status code: 200 (expected 200)
  ✓ Valid JSON response
  ✓ Response: [{"id":608...

...

Passed: 14 / 14
Failed: 0 / 14

✓ All tests passed!
```

---

### 3. PHPUnit тести (TimerApiCest.php)
**Файл:** `/backend/tests/functional/TimerApiCest.php`

**Опис:** Функціональні тести API з перевіркою роботи з базою даних

**Запуск:**
```bash
cd /home/igor/developer/masterok-asana
php vendor/bin/codecept run functional TimerApiCest
```

**Що тестує:**
- ✅ Отримання списку таймерів
- ✅ Отримання статистики
- ✅ Створення таймера (POST)
- ✅ Оновлення таймера (PUT)
- ✅ Видалення таймера (DELETE)
- ✅ Архівування таймера
- ✅ Фільтрація
- ✅ Валідація полів
- ✅ Перевірка записів в БД

**Методи тестування:**
```php
public function testGetTimersList(FunctionalTester $I)
public function testGetTimerStatistics(FunctionalTester $I)
public function testGetNonExistentTimer(FunctionalTester $I)
public function testCreateTimer(FunctionalTester $I)
public function testUpdateTimer(FunctionalTester $I)
public function testDeleteTimer(FunctionalTester $I)
public function testToggleTimerArchive(FunctionalTester $I)
public function testFilterTimers(FunctionalTester $I)
public function testCreateTimerValidation(FunctionalTester $I)
```

---

## 🚀 Швидкий запуск всіх тестів

```bash
# 1. Швидкий тест
./test-api.sh

# 2. Детальний тест
./test-api-detailed.sh

# 3. PHPUnit тести (якщо налаштовані)
php vendor/bin/codecept run functional TimerApiCest
```

---

## 📊 Покриття тестами

### Timer API - 100% покриття
- ✅ GET /api/timer/list
- ✅ GET /api/timer/view
- ✅ POST /api/timer/create
- ✅ PUT /api/timer/update
- ✅ DELETE /api/timer/delete
- ✅ GET /api/timer/statistics
- ✅ POST /api/timer/toggle-archive

### ActOfWork API - 100% покриття (GET endpoints)
- ✅ GET /api/act-of-work/list
- ✅ GET /api/act-of-work/view
- ✅ GET /api/act-of-work/statistics
- ⚠️ POST/PUT/DELETE endpoints (частково)

### ActOfWorkDetail API - 100% покриття (GET endpoints)
- ✅ GET /api/act-of-work-detail/list
- ✅ GET /api/act-of-work-detail/view
- ✅ GET /api/act-of-work-detail/statistics
- ⚠️ POST/PUT/DELETE endpoints (частково)

---

## 🔧 Налаштування тестів

### Змінити BASE_URL

У файлах `test-api.sh` та `test-api-detailed.sh`:
```bash
BASE_URL="http://your-domain.com/admin/api"
```

### Додати нові тести

**Bash скрипт:**
```bash
test_api "Test name" "GET" "/endpoint" "" "200"
```

**PHPUnit:**
```php
public function testMyNewTest(FunctionalTester $I)
{
    $I->wantTo('test something');
    $I->sendGET('/api/my-endpoint');
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
}
```

---

## 📝 Структура тестів

```
masterok-asana/
├── test-api.sh                      # Швидкий тест
├── test-api-detailed.sh             # Детальний тест
└── backend/
    └── tests/
        └── functional/
            └── TimerApiCest.php     # PHPUnit тести
```

---

## 🐛 Дебаг тестів

### Якщо тест не проходить:

1. **Перевірте URL:**
```bash
curl -v http://localhost/admin/api/timer/list
```

2. **Перевірте логи:**
```bash
tail -f backend/runtime/logs/backend.log
```

3. **Перевірте статус сервера:**
```bash
sudo systemctl status nginx
sudo systemctl status php-fpm
```

4. **Запустіть тест з деталями:**
```bash
bash -x ./test-api-detailed.sh
```

---

## 📈 Додавання власних тестів

### Створення нового тесту в bash:

```bash
# У файлі test-api-detailed.sh додайте:
test_api "My custom test" "GET" "/timer/view?id=1" "" "200"
```

### Створення нового PHPUnit тесту:

1. Створіть новий файл `ActOfWorkApiCest.php`
2. Скопіюйте структуру з `TimerApiCest.php`
3. Змініть назви методів та endpoints
4. Запустіть тест

---

## 🎯 Best Practices

### 1. Завжди тестуйте після змін
```bash
./test-api-detailed.sh
```

### 2. Перевіряйте статус-коди
- 200 - OK
- 404 - Not Found
- 500 - Server Error

### 3. Валідуйте JSON
Всі відповіді мають бути валідним JSON

### 4. Тестуйте edge cases
- Неіснуючі ID
- Порожні параметри
- Невалідні дані

### 5. Очищуйте тестові дані
PHPUnit тести автоматично очищають створені записи

---

## 📚 Додаткові ресурси

- **Codeception документація:** https://codeception.com/
- **REST API Testing:** https://www.guru99.com/testing-rest-api-manually.html
- **curl документація:** https://curl.se/docs/manual.html

---

## ✅ Чеклист перед deploy

- [ ] Всі bash тести проходять
- [ ] Детальний тест показує 100% success
- [ ] PHPUnit тести проходять (якщо налаштовані)
- [ ] Логи не містять помилок
- [ ] API доступний з зовнішніх запитів
- [ ] CORS налаштований правильно
- [ ] Аутентифікація працює (якщо увімкнена)

---

## 🆘 Підтримка

Якщо тести не проходять:
1. Перевірте конфігурацію в `/backend/config/main.php`
2. Переконайтесь що всі API контроллери створені
3. Перевірте права доступу до файлів
4. Подивіться логи помилок

**Контакти:**
- Документація: `/docs/api.md`
- Приклади: `/docs/api-javascript-examples.md`
- Безпека: `/docs/api-security.md`

