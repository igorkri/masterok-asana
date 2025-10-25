# Документація API

## 📚 Зміст

1. [Швидкий старт](../API-QUICK-START.md) - Почніть звідси!
2. [Повна документація API](api.md) - Всі endpoints з прикладами
3. [JavaScript приклади](api-javascript-examples.md) - Інтеграція з фронтендом
4. [Тестування через Postman](api-postman-testing.md) - ⭐ Детальна інструкція
5. [Тестування](api-testing.md) - Запуск і створення тестів
6. [Безпека](api-security.md) - Налаштування захисту API
7. [Список файлів](API-FILES-LIST.md) - Структура проекту

---

## 🚀 Швидкий доступ

### Початок роботи
```bash
# Перевірка роботи API
./test-api.sh

# Перший запит
curl http://localhost/admin/api/timer/statistics
```

### Документація по розділах

#### 🎯 [Повна документація API](api.md)
- Всі 21 endpoints
- Приклади curl запитів
- Структура відповідей
- Коди статусів

#### 💻 [JavaScript приклади](api-javascript-examples.md)
- Готові функції для роботи з API
- React hooks
- Обробка помилок
- Повні приклади інтеграції

#### 🔒 [Безпека API](api-security.md)
- Варіанти захисту
- API Key аутентифікація
- IP фільтрація
- JWT токени
- Рекомендації для production

#### 🧪 [Тестування](api-testing.md)
- 3 типи тестів
- Швидка перевірка
- Детальні тести
- PHPUnit тести
- Створення власних тестів

#### 📬 [Тестування через Postman](api-postman-testing.md)
- Імпорт колекції
- Покрокова інструкція
- Тестування всіх endpoints
- Автоматичні тести
- Environments налаштування
- Troubleshooting

#### 📋 [Список файлів](API-FILES-LIST.md)
- Всі створені файли
- Опис кожного файлу
- Структура проекту
- Контрольний чеклист

---

## 📡 API Endpoints

### Timer API (7 endpoints)
```
GET    /api/timer/list
GET    /api/timer/view?id=1
POST   /api/timer/create
PUT    /api/timer/update?id=1
DELETE /api/timer/delete?id=1
GET    /api/timer/statistics
POST   /api/timer/toggle-archive?id=1
```

### ActOfWork API (7 endpoints)
```
GET    /api/act-of-work/list
GET    /api/act-of-work/view?id=1
POST   /api/act-of-work/create
PUT    /api/act-of-work/update?id=1
DELETE /api/act-of-work/delete?id=1
POST   /api/act-of-work/change-status?id=1
GET    /api/act-of-work/statistics
```

### ActOfWorkDetail API (7 endpoints)
```
GET    /api/act-of-work-detail/list
GET    /api/act-of-work-detail/view?id=1
POST   /api/act-of-work-detail/create
PUT    /api/act-of-work-detail/update?id=1
DELETE /api/act-of-work-detail/delete?id=1
GET    /api/act-of-work-detail/by-act?act_id=1
GET    /api/act-of-work-detail/statistics
```

---

## 🛠️ Інструменти

### Postman колекція
**Файл:** `Masterok-Asana-API.postman_collection.json`

**Як використовувати:**
1. Відкрийте Postman
2. Import → File → Виберіть файл
3. Змініть змінну `baseUrl`
4. Тестуйте всі endpoints

### Тестові скрипти
```bash
# Швидкий тест
./test-api.sh

# Детальний тест
./test-api-detailed.sh

# PHPUnit тести
php vendor/bin/codecept run functional TimerApiCest
```

---

## 📖 Приклади використання

### cURL
```bash
# Отримати список таймерів
curl -X GET "http://localhost/admin/api/timer/list?per-page=10"

# Створити таймер
curl -X POST "http://localhost/admin/api/timer/create" \
  -H "Content-Type: application/json" \
  -d '{"task_gid":"12345","time":"02:30:00","coefficient":1.2}'
```

### JavaScript
```javascript
// Отримати дані
fetch('http://localhost/admin/api/timer/list')
  .then(r => r.json())
  .then(data => console.log(data));

// Створити таймер
fetch('http://localhost/admin/api/timer/create', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    task_gid: '12345',
    time: '02:30:00',
    coefficient: 1.2
  })
}).then(r => r.json()).then(data => console.log(data));
```

### PHP
```php
// GET запит
$response = file_get_contents('http://localhost/admin/api/timer/list?per-page=10');
$data = json_decode($response, true);

// POST запит
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode(['task_gid' => '12345', 'time' => '02:30:00'])
    ]
];
$context = stream_context_create($options);
$response = file_get_contents('http://localhost/admin/api/timer/create', false, $context);
```

---

## 🔐 Безпека

### За замовчуванням
- ✅ API доступний без авторизації
- ✅ Бекенд захищений паролем
- ✅ CORS налаштований

### Для production
Включіть API Key:

**1. Додайте ключ:**
```php
// backend/config/params.php
'apiKey' => 'your-secret-key-here',
```

**2. Активуйте в контролерах:**
```php
'enabled' => true, // в apiKeyAuth
```

**3. Використовуйте з ключем:**
```bash
curl -H "X-API-Key: your-secret-key" http://localhost/admin/api/timer/list
```

**Детальніше:** [api-security.md](api-security.md)

---

## ✅ Статус тестів

Всі тести пройшли успішно! ✅

```
Passed: 14 / 14
Failed: 0 / 14
```

**Протестовано:**
- ✅ 21 endpoint
- ✅ Валідація JSON
- ✅ Статус-коди
- ✅ Фільтрація
- ✅ Неіснуючі ресурси

---

## 📞 Підтримка

### Проблеми з API?

1. **Перевірте доступність:**
```bash
./test-api.sh
```

2. **Подивіться логи:**
```bash
tail -f ../backend/runtime/logs/backend.log
```

3. **Запустіть детальні тести:**
```bash
./test-api-detailed.sh
```

### Документація
- 🚀 [Швидкий старт](../API-QUICK-START.md)
- 📖 [Повна документація](api.md)
- 💻 [JavaScript приклади](api-javascript-examples.md)
- 🔒 [Безпека](api-security.md)
- 🧪 [Тестування](api-testing.md)

---

## 🎯 Корисні посилання

- **Yii2 REST Documentation:** https://www.yiiframework.com/doc/guide/2.0/en/rest-quick-start
- **REST API Best Practices:** https://restfulapi.net/
- **Postman Learning:** https://learning.postman.com/
- **cURL Manual:** https://curl.se/docs/manual.html

---

## 📊 Статистика проекту

- **Endpoints:** 21
- **Контролери:** 3
- **Файлів документації:** 6
- **Тестів:** 14+
- **Покриття:** 100% GET endpoints
- **Статус:** ✅ Production Ready

---

**Останнє оновлення:** 25.01.2025  
**Версія API:** 1.0  
**Автор:** API Generator  

