# API Documentation - Timer, ActOfWork, ActOfWorkDetail

## Базовый URL
```
http://your-domain.com/admin/api/
```

---

## Timer API

### 1. Получить список таймеров
**GET** `/api/timer/list`

**Параметры запроса (query):**
- `status` - фильтр по статусу (0-5)
- `archive` - фильтр по архиву (0-1)
- `task_gid` - фильтр по ID задачи
- `per-page` - количество записей на странице (по умолчанию 20)
- `page` - номер страницы

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/timer/list?status=0&archive=0&per-page=10"
```

**Ответ:**
```json
{
  "items": [
    {
      "id": 1,
      "task_gid": "12345",
      "time": "02:30:00",
      "minute": 150,
      "coefficient": 1.2,
      "status": 0,
      "archive": 0
    }
  ],
  "pagination": {
    "totalCount": 100,
    "pageCount": 10,
    "currentPage": 1,
    "perPage": 10
  }
}
```

---

### 2. Получить один таймер
**GET** `/api/timer/view?id=1`

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/timer/view?id=1"
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "task_gid": "12345",
    "task_name": "Назва задачі",
    "project_name": "Назва проекту",
    "time": "02:30:00",
    "time_hour": 2.5,
    "minute": 150,
    "coefficient": 1.2,
    "price": 1200.00,
    "comment": "Коментар",
    "status": 0,
    "status_text": "Чекає на звіт",
    "archive": 0,
    "status_act": "ok",
    "created_at": "2025-01-01 10:00:00",
    "updated_at": "2025-01-02 12:00:00"
  }
}
```

---

### 3. Создать таймер
**POST** `/api/timer/create`

**Body (JSON):**
```json
{
  "task_gid": "12345",
  "time": "02:30:00",
  "coefficient": 1.2,
  "comment": "Коментар до таймера",
  "status": 0,
  "archive": 0,
  "status_act": "ok"
}
```

**Пример запроса:**
```bash
curl -X POST "http://your-domain.com/admin/api/timer/create" \
  -H "Content-Type: application/json" \
  -d '{
    "task_gid": "12345",
    "time": "02:30:00",
    "coefficient": 1.2,
    "comment": "Коментар"
  }'
```

**Ответ:**
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

### 4. Обновить таймер
**PUT** `/api/timer/update?id=1`

**Body (JSON):**
```json
{
  "time": "03:00:00",
  "coefficient": 1.5,
  "comment": "Оновлений коментар",
  "status": 1
}
```

**Пример запроса:**
```bash
curl -X PUT "http://your-domain.com/admin/api/timer/update?id=1" \
  -H "Content-Type: application/json" \
  -d '{
    "time": "03:00:00",
    "coefficient": 1.5
  }'
```

**Ответ:**
```json
{
  "success": true,
  "message": "Таймер успішно оновлено",
  "data": {
    "id": 1
  }
}
```

---

### 5. Удалить таймер
**DELETE** `/api/timer/delete?id=1`

**Пример запроса:**
```bash
curl -X DELETE "http://your-domain.com/admin/api/timer/delete?id=1"
```

**Ответ:**
```json
{
  "success": true,
  "message": "Таймер успішно видалено"
}
```

---

### 6. Получить статистику по таймерам
**GET** `/api/timer/statistics`

**Параметры запроса:**
- `status` - фильтр по статусу
- `archive` - фильтр по архиву (по умолчанию 0)

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/timer/statistics?status=0&archive=0"
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "total_records": 150,
    "total_minutes": 45000,
    "total_time": "750:00:00",
    "total_price": 360000.00
  }
}
```

---

### 7. Архивировать/разархивировать таймер
**POST** `/api/timer/toggle-archive?id=1`

**Пример запроса:**
```bash
curl -X POST "http://your-domain.com/admin/api/timer/toggle-archive?id=1"
```

**Ответ:**
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

## ActOfWork API

### 1. Получить список актов
**GET** `/api/act-of-work/list`

**Параметры запроса:**
- `status` - фильтр по статусу
- `type` - фильтр по типу
- `user_id` - фильтр по пользователю
- `period_year` - фильтр по году
- `period_month` - фильтр по месяцу
- `per-page` - количество записей на странице

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/act-of-work/list?status=pending&per-page=20"
```

---

### 2. Получить один акт
**GET** `/api/act-of-work/view?id=1`

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/act-of-work/view?id=1"
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "number": "ACT-2025-001",
    "status": "pending",
    "status_text": "Очікує",
    "type": "act",
    "type_text": "Акт",
    "period": "{\"type\":\"month\",\"year\":\"2025\",\"month\":\"January\"}",
    "period_text": "Місяць (Січень 2025)",
    "period_type": "month",
    "period_year": "2025",
    "period_month": "January",
    "user_id": 1,
    "user_name": "admin",
    "date": "2025-01-25",
    "description": "Опис робіт",
    "total_amount": 50000.00,
    "paid_amount": 0.00,
    "file_excel": null,
    "telegram_status": "pending",
    "sort": 0,
    "created_at": "2025-01-25 10:00:00",
    "updated_at": "2025-01-25 10:00:00",
    "details": [
      {
        "id": 1,
        "task_gid": "12345",
        "project_gid": "67890",
        "project": "Назва проекту",
        "task": "Назва задачі",
        "description": "Опис деталі",
        "amount": 5000.00,
        "hours": 12.5
      }
    ]
  }
}
```

---

### 3. Создать акт
**POST** `/api/act-of-work/create`

**Body (JSON):**
```json
{
  "number": "ACT-2025-001",
  "status": "pending",
  "type": "act",
  "period_type": "month",
  "period_year": "2025",
  "period_month": "January",
  "user_id": 1,
  "date": "2025-01-25",
  "description": "Опис робіт за січень 2025",
  "total_amount": 50000.00,
  "paid_amount": 0.00
}
```

**Пример запроса:**
```bash
curl -X POST "http://your-domain.com/admin/api/act-of-work/create" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "pending",
    "type": "act",
    "user_id": 1,
    "date": "2025-01-25",
    "total_amount": 50000.00
  }'
```

**Ответ:**
```json
{
  "success": true,
  "message": "Акт успішно створено",
  "data": {
    "id": 123,
    "number": "1737792000"
  }
}
```

---

### 4. Обновить акт
**PUT** `/api/act-of-work/update?id=1`

**Body (JSON):**
```json
{
  "status": "paid",
  "paid_amount": 50000.00,
  "description": "Оновлений опис"
}
```

---

### 5. Удалить акт
**DELETE** `/api/act-of-work/delete?id=1`

---

### 6. Изменить статус акта
**POST** `/api/act-of-work/change-status?id=1`

**Body (JSON):**
```json
{
  "status": "paid"
}
```

**Пример запроса:**
```bash
curl -X POST "http://your-domain.com/admin/api/act-of-work/change-status?id=1" \
  -H "Content-Type: application/json" \
  -d '{"status": "paid"}'
```

**Ответ:**
```json
{
  "success": true,
  "message": "Статус акту успішно змінено",
  "data": {
    "status": "paid",
    "status_text": "Оплачено"
  }
}
```

---

### 7. Получить статистику по актам
**GET** `/api/act-of-work/statistics`

**Параметры запроса:**
- `status` - фильтр по статусу
- `type` - фильтр по типу

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/act-of-work/statistics?status=pending"
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "total_records": 25,
    "total_amount": 1250000.00,
    "total_paid_amount": 500000.00,
    "unpaid_amount": 750000.00
  }
}
```

---

## ActOfWorkDetail API

### 1. Получить список деталей актов
**GET** `/api/act-of-work-detail/list`

**Параметры запроса:**
- `act_of_work_id` - фильтр по ID акта
- `task_gid` - фильтр по ID задачи
- `project_gid` - фильтр по ID проекта
- `per-page` - количество записей на странице

---

### 2. Получить одну деталь акта
**GET** `/api/act-of-work-detail/view?id=1`

**Ответ:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "act_of_work_id": 1,
    "act_number": "ACT-2025-001",
    "time_id": 10,
    "task_gid": "12345",
    "project_gid": "67890",
    "project": "Назва проекту",
    "task": "Назва задачі",
    "description": "Опис деталі",
    "amount": 5000.00,
    "hours": 12.5,
    "created_at": "2025-01-25 10:00:00",
    "updated_at": "2025-01-25 10:00:00"
  }
}
```

---

### 3. Создать деталь акта
**POST** `/api/act-of-work-detail/create`

**Body (JSON):**
```json
{
  "act_of_work_id": 1,
  "time_id": 10,
  "task_gid": "12345",
  "project_gid": "67890",
  "project": "Назва проекту",
  "task": "Назва задачі",
  "description": "Опис деталі",
  "amount": 5000.00,
  "hours": 12.5
}
```

**Пример запроса:**
```bash
curl -X POST "http://your-domain.com/admin/api/act-of-work-detail/create" \
  -H "Content-Type: application/json" \
  -d '{
    "act_of_work_id": 1,
    "time_id": 10,
    "task_gid": "12345",
    "project_gid": "67890",
    "amount": 5000.00,
    "hours": 12.5
  }'
```

**Ответ:**
```json
{
  "success": true,
  "message": "Деталь акту успішно створено",
  "data": {
    "id": 123
  }
}
```

---

### 4. Обновить деталь акта
**PUT** `/api/act-of-work-detail/update?id=1`

**Body (JSON):**
```json
{
  "amount": 6000.00,
  "hours": 15.0,
  "description": "Оновлений опис"
}
```

---

### 5. Удалить деталь акта
**DELETE** `/api/act-of-work-detail/delete?id=1`

---

### 6. Получить детали акта по ID акта
**GET** `/api/act-of-work-detail/by-act?act_id=1`

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/act-of-work-detail/by-act?act_id=1"
```

**Ответ:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "time_id": 10,
      "task_gid": "12345",
      "project_gid": "67890",
      "project": "Назва проекту",
      "task": "Назва задачі",
      "description": "Опис деталі",
      "amount": 5000.00,
      "hours": 12.5
    }
  ],
  "summary": {
    "total_records": 10,
    "total_amount": 50000.00,
    "total_hours": 125.5
  }
}
```

---

### 7. Получить статистику по деталям актов
**GET** `/api/act-of-work-detail/statistics`

**Параметры запроса:**
- `act_of_work_id` - фильтр по ID акта

**Пример запроса:**
```bash
curl -X GET "http://your-domain.com/admin/api/act-of-work-detail/statistics?act_of_work_id=1"
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "total_records": 10,
    "total_amount": 50000.00,
    "total_hours": 125.5
  }
}
```

---

## Коды статусов Timer

- `0` - STATUS_WAIT - Чекає на звіт
- `1` - STATUS_PROCESS - В процесі
- `2` - STATUS_PLANNED - Заплановано
- `3` - STATUS_INVOICE - Копіювати в акти та згенерувати файл
- `4` - STATUS_PAID - Оплачено
- `5` - STATUS_NEED_CLARIFICATION - Потребує уточнення

## Коды статусов ActOfWork

- `pending` - Очікує
- `in_progress` - В процесі
- `paid` - Оплачено
- `partially_paid` - Частково оплачено
- `cancelled` - Скасовано
- `archived` - Архівовано
- `draft` - Чернетка
- `done` - Превірено, оплачено

## Типы ActOfWork

- `act` - Акт
- `receipt_of_funds` - Надходження коштів
- `new_project` - Новий проект
- `other` - Інший тип

---

## Обработка ошибок

Все API endpoints возвращают JSON с полем `success`:

**Успешный ответ:**
```json
{
  "success": true,
  "message": "Операція виконана успішно",
  "data": { ... }
}
```

**Ошибка:**
```json
{
  "success": false,
  "message": "Помилка при виконанні операції",
  "errors": {
    "field_name": ["Помилка валідації"]
  }
}
```

---

## Примечания

1. Все даты в формате `Y-m-d H:i:s` (например: 2025-01-25 10:00:00)
2. Время в формате `H:i:s` (например: 02:30:00)
3. Суммы (amount, price) - float с точностью до 2 знаков
4. При создании ActOfWorkDetail автоматически обновляется `total_amount` в ActOfWork
5. При удалении ActOfWorkDetail также обновляется `total_amount` в ActOfWork
6. При удалении ActOfWork удаляются все связанные ActOfWorkDetail

