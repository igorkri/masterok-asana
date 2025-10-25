# 📬 Postman - Швидка шпаргалка

## 🚀 За 3 хвилини

### 1. Імпорт (30 сек)
```
Postman → Import → File → 
/docs/Masterok-Asana-API.postman_collection.json
```

### 2. Налаштування (30 сек)
```
Правий клік на колекції → Edit → Variables →
baseUrl = http://localhost/admin/api
```

### 3. Перший тест (10 сек)
```
Timer API → Get Timers List → Send
```

### 4. Готово! ✅

---

## 📋 Швидкі тести

### Timer
```
✓ Get Timers List        → GET  /timer/list
✓ Get Statistics         → GET  /timer/statistics
✓ Create Timer          → POST /timer/create
✓ Update Timer          → PUT  /timer/update?id=1
✓ Delete Timer          → DEL  /timer/delete?id=1
```

### ActOfWork
```
✓ Get Acts List         → GET  /act-of-work/list
✓ Get Statistics        → GET  /act-of-work/statistics
✓ Create Act           → POST /act-of-work/create
✓ Change Status        → POST /act-of-work/change-status?id=1
```

### ActOfWorkDetail
```
✓ Get Details List      → GET  /act-of-work-detail/list
✓ Get by Act           → GET  /act-of-work-detail/by-act?act_id=1
✓ Create Detail        → POST /act-of-work-detail/create
```

---

## 🔧 Швидкі налаштування

### Environment
```
⚙️ → Environments → + Create → Development
Додати: baseUrl = http://localhost/admin/api
```

### API Key (якщо потрібно)
```
Headers → 
Key: X-API-Key
Value: your-secret-key
```

---

## 💡 Корисні команди

### Тести (вкладка Tests)
```javascript
// Статус 200
pm.test("Status OK", () => {
    pm.response.to.have.status(200);
});

// Є JSON
pm.test("Is JSON", () => {
    pm.response.to.be.json;
});

// Зберегти ID
pm.environment.set("timerId", jsonData.data.id);
```

### Змінні
```
{{baseUrl}}/timer/list
{{timerId}}
{{apiKey}}
```

---

## ⚡ Швидкі клавіші

- `Ctrl+Enter` - Send
- `Ctrl+S` - Save
- `Ctrl+K` - Search
- `Ctrl+/` - Toggle sidebar

---

## 🐛 Швидкі рішення

**404 Not Found?**
→ Перевірте baseUrl в Variables

**Could not send?**
→ Сервер не запущений

**401 Unauthorized?**
→ Додайте X-API-Key в Headers

---

**📖 Повна інструкція:** `/docs/api-postman-testing.md`

