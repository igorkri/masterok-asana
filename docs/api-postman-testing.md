# 📬 Тестування API через Postman

## 🚀 Швидкий старт

### Крок 1: Імпорт колекції

1. **Відкрийте Postman** (завантажте з https://www.postman.com/downloads/ якщо немає)

2. **Імпортуйте колекцію:**
   - Натисніть **Import** (зліва вгорі)
   - Виберіть **File**
   - Знайдіть файл: `/docs/Masterok-Asana-API.postman_collection.json`
   - Натисніть **Open**

3. **Колекція готова!** Ви побачите папку "Masterok-Asana API" в лівій панелі

---

### Крок 2: Налаштування базового URL

1. Клікніть правою кнопкою на колекції **Masterok-Asana API**
2. Виберіть **Edit**
3. Перейдіть на вкладку **Variables**
4. Знайдіть змінну `baseUrl`
5. Змініть `CURRENT VALUE` на ваш URL:
   ```
   http://localhost/admin/api
   ```
   або
   ```
   http://your-domain.com/admin/api
   ```
6. Натисніть **Save**

---

### Крок 3: Перший тест

1. Розгорніть папку **Timer API**
2. Клікніть на **Get Timers List**
3. Натисніть синю кнопку **Send**
4. Побачите результат внизу! 🎉

---

## 📋 Детальне тестування

### Timer API

#### 1. Get Timers List
**Метод:** GET  
**Endpoint:** `/timer/list`

**Параметри (Query Params):**
- `status` = `0` (опціонально)
- `archive` = `0` (опціонально)
- `per-page` = `20` (опціонально)

**Як тестувати:**
1. Відкрийте запит "Get Timers List"
2. Вкладка **Params** - тут можна змінити параметри
3. Натисніть **Send**
4. Перевірте відповідь - має бути масив таймерів

**Очікуваний результат:**
```json
[
  {
    "id": 608,
    "task_gid": "1210429926245201",
    "time": "02:13:06",
    "minute": 133,
    "coefficient": 1.2,
    ...
  }
]
```

---

#### 2. Get Timer by ID
**Метод:** GET  
**Endpoint:** `/timer/view?id=1`

**Як тестувати:**
1. Відкрийте запит "Get Timer by ID"
2. У **Params** змініть `id` на реальний ID з попереднього запиту
3. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "data": {
    "id": 608,
    "task_gid": "1210429926245201",
    "task_name": "Назва задачі",
    "time": "02:13:06",
    "price": 1200.00,
    ...
  }
}
```

---

#### 3. Get Timer Statistics
**Метод:** GET  
**Endpoint:** `/timer/statistics`

**Параметри:**
- `status` = `0` (опціонально)
- `archive` = `0` (опціонально)

**Як тестувати:**
1. Відкрийте запит "Get Timer Statistics"
2. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "data": {
    "total_records": "5",
    "total_minutes": "1784",
    "total_time": "29:44:00",
    "total_price": 123456.78
  }
}
```

---

#### 4. Create Timer
**Метод:** POST  
**Endpoint:** `/timer/create`

**Як тестувати:**
1. Відкрийте запит "Create Timer"
2. Перейдіть на вкладку **Body**
3. Переконайтесь що обрано **raw** та **JSON**
4. Змініть JSON (якщо потрібно):
   ```json
   {
     "task_gid": "12345",
     "time": "02:30:00",
     "coefficient": 1.2,
     "comment": "Тест через Postman",
     "status": 0,
     "archive": 0,
     "status_act": "ok"
   }
   ```
5. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "message": "Таймер успішно створено",
  "data": {
    "id": 123
  }
}
```

---

#### 5. Update Timer
**Метод:** PUT  
**Endpoint:** `/timer/update?id=1`

**Як тестувати:**
1. Відкрийте запит "Update Timer"
2. У **Params** змініть `id` на ID створеного таймера
3. У **Body** змініте дані:
   ```json
   {
     "time": "03:00:00",
     "coefficient": 1.5,
     "comment": "Оновлено через Postman"
   }
   ```
4. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "message": "Таймер успішно оновлено",
  "data": {
    "id": 123
  }
}
```

---

#### 6. Toggle Timer Archive
**Метод:** POST  
**Endpoint:** `/timer/toggle-archive?id=1`

**Як тестувати:**
1. Відкрийте запит "Toggle Timer Archive"
2. У **Params** вкажіть `id` таймера
3. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "message": "Таймер архівовано",
  "data": {
    "archive": 1
  }
}
```

---

#### 7. Delete Timer
**Метод:** DELETE  
**Endpoint:** `/timer/delete?id=1`

**Як тестувати:**
1. Відкрийте запит "Delete Timer"
2. У **Params** вкажіть `id` таймера для видалення
3. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "message": "Таймер успішно видалено"
}
```

---

### ActOfWork API

#### 1. Get Acts List
**Метод:** GET  
**Endpoint:** `/act-of-work/list`

**Параметри:**
- `status` = `pending`
- `per-page` = `20`

**Як тестувати:**
1. Відкрийте запит "Get Acts List"
2. Налаштуйте параметри в **Params**
3. Натисніть **Send**

---

#### 2. Get Act by ID
**Метод:** GET  
**Endpoint:** `/act-of-work/view?id=1`

**Як тестувати:**
1. Відкрийте запит "Get Act by ID"
2. Вкажіть реальний `id` акта
3. Натисніть **Send**

**Очікуваний результат:**
```json
{
  "success": true,
  "data": {
    "id": 13,
    "number": "1748869699",
    "status": "pending",
    "total_amount": 50000.00,
    "details": [...]
  }
}
```

---

#### 3. Create Act
**Метод:** POST  
**Endpoint:** `/act-of-work/create`

**Body приклад:**
```json
{
  "status": "pending",
  "type": "act",
  "period_type": "month",
  "period_year": "2025",
  "period_month": "January",
  "user_id": 1,
  "date": "2025-01-25",
  "description": "Тест акт через Postman",
  "total_amount": 50000.00,
  "paid_amount": 0.00
}
```

---

#### 4. Update Act
**Метод:** PUT  
**Endpoint:** `/act-of-work/update?id=1`

**Body приклад:**
```json
{
  "status": "paid",
  "paid_amount": 50000.00,
  "description": "Оновлено через Postman"
}
```

---

#### 5. Change Act Status
**Метод:** POST  
**Endpoint:** `/act-of-work/change-status?id=1`

**Body:**
```json
{
  "status": "paid"
}
```

**Доступні статуси:**
- `pending` - Очікує
- `paid` - Оплачено
- `partially_paid` - Частково оплачено
- `cancelled` - Скасовано
- `done` - Превірено, оплачено

---

#### 6. Get Act Statistics
**Метод:** GET  
**Endpoint:** `/act-of-work/statistics`

**Параметри:**
- `status` - фільтр по статусу
- `type` - фільтр по типу

---

#### 7. Delete Act
**Метод:** DELETE  
**Endpoint:** `/act-of-work/delete?id=1`

⚠️ **Увага:** Видаляє акт і всі його деталі!

---

### ActOfWorkDetail API

#### 1. Get Details List
**Метод:** GET  
**Endpoint:** `/act-of-work-detail/list`

**Параметри:**
- `act_of_work_id` - фільтр по акту
- `per-page` = `20`

---

#### 2. Get Details by Act ID
**Метод:** GET  
**Endpoint:** `/act-of-work-detail/by-act?act_id=1`

**Особливість:** Повертає всі деталі конкретного акта + підсумок

**Очікуваний результат:**
```json
{
  "success": true,
  "data": [...],
  "summary": {
    "total_records": 10,
    "total_amount": 50000.00,
    "total_hours": 125.5
  }
}
```

---

#### 3. Create Detail
**Метод:** POST  
**Endpoint:** `/act-of-work-detail/create`

**Body приклад:**
```json
{
  "act_of_work_id": 1,
  "time_id": 10,
  "task_gid": 12345,
  "project_gid": 67890,
  "project": "Назва проекту",
  "task": "Назва задачі",
  "description": "Опис деталі",
  "amount": 5000.00,
  "hours": 12.5
}
```

⚠️ **Важливо:** Після створення деталі автоматично оновлюється `total_amount` в акті!

---

#### 4. Update Detail
**Метод:** PUT  
**Endpoint:** `/act-of-work-detail/update?id=1`

**Body приклад:**
```json
{
  "amount": 6000.00,
  "hours": 15.0,
  "description": "Оновлений опис"
}
```

---

#### 5. Delete Detail
**Метод:** DELETE  
**Endpoint:** `/act-of-work-detail/delete?id=1`

⚠️ **Увага:** Після видалення деталі автоматично оновлюється `total_amount` в акті!

---

## 🔧 Налаштування Environment

Для роботи з різними середовищами (dev, staging, production):

### Крок 1: Створення Environment

1. Клікніть на іконку "⚙️" (зліва вгорі)
2. Виберіть **Environments**
3. Натисніть **+ Create Environment**
4. Назвіть: `Development`

### Крок 2: Додавання змінних

Додайте змінні:

| Variable | Initial Value | Current Value |
|----------|---------------|---------------|
| baseUrl | http://localhost/admin/api | http://localhost/admin/api |
| apiKey | (порожньо) | your-api-key-here |

### Крок 3: Використання змінних

У запитах:
- URL: `{{baseUrl}}/timer/list`
- Header: `X-API-Key: {{apiKey}}`

### Крок 4: Перемикання між середовищами

1. Створіть кілька environments (Dev, Staging, Production)
2. Вкажіть різні `baseUrl` для кожного
3. Перемикайтесь через dropdown вгорі справа

---

## 🧪 Тестування з API Key

Якщо увімкнена аутентифікація:

### Варіант 1: Через Headers

1. Відкрийте будь-який запит
2. Перейдіть на вкладку **Headers**
3. Додайте:
   - **Key:** `X-API-Key`
   - **Value:** `your-secret-key-here`
4. Натисніть **Send**

### Варіант 2: Через Environment

1. Додайте змінну `apiKey` в Environment
2. У запиті додайте header:
   - **Key:** `X-API-Key`
   - **Value:** `{{apiKey}}`
3. Змінюйте ключ тільки в одному місці!

---

## 📊 Тестування з Tests

Postman дозволяє писати автоматичні тести для перевірки відповідей.

### Приклад тесту для Timer List:

```javascript
// Перейдіть на вкладку Tests в запиті

// Перевірка статус коду
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

// Перевірка що відповідь - JSON
pm.test("Response is JSON", function () {
    pm.response.to.be.json;
});

// Перевірка що є масив
pm.test("Response is array", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.be.an('array');
});

// Перевірка структури першого елемента
pm.test("First item has required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.length > 0) {
        pm.expect(jsonData[0]).to.have.property('id');
        pm.expect(jsonData[0]).to.have.property('task_gid');
        pm.expect(jsonData[0]).to.have.property('time');
    }
});
```

### Приклад тесту для Create Timer:

```javascript
// Перевірка успішного створення
pm.test("Timer created successfully", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.be.true;
    pm.expect(jsonData.data).to.have.property('id');
});

// Зберігаємо ID для наступних запитів
pm.test("Save timer ID", function () {
    var jsonData = pm.response.json();
    if (jsonData.success) {
        pm.environment.set("timerId", jsonData.data.id);
    }
});
```

### Використання збереженого ID:

У наступних запитах (Update, Delete) використовуйте:
```
/timer/update?id={{timerId}}
```

---

## 🔄 Створення Test Suite

### Крок 1: Налаштування Collection Runner

1. Клікніть на колекцію "Masterok-Asana API"
2. Натисніть кнопку **▶️ Run**
3. Виберіть запити які хочете запустити
4. Натисніть **Run Masterok-Asana API**

### Крок 2: Порядок запитів

Правильний порядок для тестування всього циклу:

1. Get Timers List (перевірка доступності)
2. Get Timer Statistics (перевірка статистики)
3. Create Timer (створення)
4. Get Timer by ID (перевірка створеного)
5. Update Timer (оновлення)
6. Get Timer by ID (перевірка оновлення)
7. Toggle Archive (архівування)
8. Delete Timer (видалення)

### Крок 3: Перегляд результатів

Після запуску побачите:
- ✅ Passed tests (зелені)
- ❌ Failed tests (червоні)
- Детальну інформацію по кожному запиту

---

## 💡 Корисні поради

### 1. Збереження відповідей

Щоб зберегти відповідь як приклад:
1. Отримайте відповідь
2. Натисніть **Save Response**
3. Назвіть приклад (наприклад, "Success Response")
4. Тепер можете побачити приклад без запиту

### 2. Копіювання запитів

Щоб створити варіант запиту:
1. Правий клік на запиті
2. **Duplicate**
3. Змініть назву (наприклад, "Get Timers - Archived")
4. Змініть параметри

### 3. Організація запитів

Створіть папки для різних сценаріїв:
- ✅ Happy Path (успішні запити)
- ❌ Error Cases (помилкові запити)
- 🧪 Integration Tests (інтеграційні тести)

### 4. Швидкі клавіші

- `Ctrl + Enter` (або `Cmd + Enter`) - Send запит
- `Ctrl + S` (або `Cmd + S`) - Save запит
- `Ctrl + K` (або `Cmd + K`) - Пошук

---

## 🐛 Troubleshooting

### Помилка: "Could not send request"

**Причина:** Сервер недоступний

**Рішення:**
1. Перевірте що сервер запущений
2. Перевірте URL в Environment
3. Спробуйте в браузері: `http://localhost/admin/api/timer/statistics`

---

### Помилка: 404 Not Found

**Причина:** Неправильний URL або маршрут

**Рішення:**
1. Перевірте `baseUrl` в Environment
2. Переконайтесь що URL без подвійних слешів
3. Перевірте конфігурацію маршрутів в `backend/config/main.php`

---

### Помилка: 401 Unauthorized

**Причина:** Відсутній або невірний API ключ

**Рішення:**
1. Перевірте чи увімкнена аутентифікація
2. Додайте header `X-API-Key` з правильним ключем
3. Перевірте `backend/config/params.php`

---

### Помилка: 500 Internal Server Error

**Причина:** Помилка на сервері

**Рішення:**
1. Подивіться логи: `backend/runtime/logs/backend.log`
2. Перевірте валідність JSON в Body
3. Перевірте обов'язкові поля

---

## 📹 Відео-інструкція (текстова)

### Повний сценарій тестування Timer API:

```
1. Імпортуйте колекцію
   → Import → File → Виберіть postman_collection.json

2. Налаштуйте baseUrl
   → Edit Collection → Variables → baseUrl = http://localhost/admin/api

3. Тест: Get Timers List
   → Timer API → Get Timers List → Send
   → Перевірте список таймерів

4. Тест: Get Statistics
   → Get Timer Statistics → Send
   → Подивіться загальну статистику

5. Тест: Create Timer
   → Create Timer → Body → Змініть дані → Send
   → Скопіюйте ID з відповіді

6. Тест: Get Created Timer
   → Get Timer by ID → Params → id = (вставте ID) → Send
   → Перевірте що дані збережені

7. Тест: Update Timer
   → Update Timer → Params → id = (ваш ID)
   → Body → Змініть time на 03:00:00 → Send

8. Тест: Archive Timer
   → Toggle Timer Archive → Params → id = (ваш ID) → Send
   → archive має стати 1

9. Тест: Delete Timer
   → Delete Timer → Params → id = (ваш ID) → Send
   → Таймер видалено

10. Перевірка видалення
    → Get Timer by ID → id = (ваш ID) → Send
    → Має бути 404 Not Found
```

---

## ✅ Чеклист тестування

Перед запуском в production, перевірте:

- [ ] Всі GET запити повертають 200
- [ ] POST запити створюють записи
- [ ] PUT запити оновлюють записи
- [ ] DELETE запити видаляють записи
- [ ] Фільтри працюють правильно
- [ ] Пагінація працює
- [ ] Неіснуючі ID повертають 404
- [ ] Валідація працює (неправильні дані = помилка)
- [ ] Статистика рахується правильно
- [ ] CORS працює (якщо потрібно)
- [ ] API Key працює (якщо увімкнено)

---

## 📚 Додаткові ресурси

- **Postman Learning:** https://learning.postman.com/
- **Postman Scripts:** https://learning.postman.com/docs/writing-scripts/intro-to-scripts/
- **Postman Variables:** https://learning.postman.com/docs/sending-requests/variables/

---

**Готово! Тепер ви можете повністю протестувати API через Postman! 🚀**

