# 🚀 Быстрый старт API

## ✅ Готово к использованию!

API настроен и доступен **без авторизации** для удобства использования, так как бекенд уже защищен паролем.

---

## 📝 Проверка работы

### 1. Простой тест через браузер

Откройте в браузере:
```
http://localhost/admin/api/timer/statistics
```

Вы должны увидеть JSON ответ со статистикой.

---

### 2. Тест через curl

```bash
# Список таймеров
curl -X GET "http://localhost/admin/api/timer/list?per-page=5"

# Статистика
curl -X GET "http://localhost/admin/api/timer/statistics"

# Список актов
curl -X GET "http://localhost/admin/api/act-of-work/list?per-page=5"
```

---

### 3. Автоматический тест

Запустите тестовый скрипт:
```bash
./test-api.sh
```

---

## 🔑 Безопасность (опционально)

API работает **без ключа по умолчанию**. Если нужна дополнительная защита:

### Включение API Key аутентификации:

1. **Добавьте ключ в params.php:**
```bash
cd /home/igor/developer/masterok-asana
nano backend/config/params.php
```

Добавьте:
```php
return [
    // ... существующие параметры
    'apiKey' => 'your-secret-key-here', // Генерируйте сложный ключ
];
```

2. **Включите аутентификацию в контроллерах:**

Откройте каждый API контроллер и измените:
```php
$behaviors['apiKeyAuth'] = [
    'class' => ApiKeyAuth::class,
    'enabled' => true, // Измените на true
];
```

3. **Используйте с ключом:**
```bash
curl -X GET "http://localhost/admin/api/timer/list" \
  -H "X-API-Key: your-secret-key-here"
```

**Подробнее:** См. `/docs/api-security.md`

---

## 📚 Документація

- **Полна документація:** `/docs/api.md`
- **JavaScript примеры:** `/docs/api-javascript-examples.md`
- **Безопасность:** `/docs/api-security.md`
- **Тестування:** `/docs/api-testing.md` ⭐
- **Список файлов:** `/docs/API-FILES-LIST.md`

---

## 🔗 Основные endpoints

### Timer API
- `GET /admin/api/timer/list` - Список таймеров
- `GET /admin/api/timer/view?id=1` - Просмотр таймера
- `POST /admin/api/timer/create` - Создать таймер
- `PUT /admin/api/timer/update?id=1` - Обновить таймер
- `DELETE /admin/api/timer/delete?id=1` - Удалить таймер
- `GET /admin/api/timer/statistics` - Статистика
- `POST /admin/api/timer/toggle-archive?id=1` - Архивировать

### ActOfWork API
- `GET /admin/api/act-of-work/list` - Список актов
- `GET /admin/api/act-of-work/view?id=1` - Просмотр акта
- `POST /admin/api/act-of-work/create` - Создать акт
- `PUT /admin/api/act-of-work/update?id=1` - Обновить акт
- `DELETE /admin/api/act-of-work/delete?id=1` - Удалить акт
- `POST /admin/api/act-of-work/change-status?id=1` - Изменить статус
- `GET /admin/api/act-of-work/statistics` - Статистика

### ActOfWorkDetail API
- `GET /admin/api/act-of-work-detail/list` - Список деталей
- `GET /admin/api/act-of-work-detail/view?id=1` - Просмотр детали
- `POST /admin/api/act-of-work-detail/create` - Создать деталь
- `PUT /admin/api/act-of-work-detail/update?id=1` - Обновить деталь
- `DELETE /admin/api/act-of-work-detail/delete?id=1` - Удалить деталь
- `GET /admin/api/act-of-work-detail/by-act?act_id=1` - Детали по акту
- `GET /admin/api/act-of-work-detail/statistics` - Статистика

---

## 💡 Примеры использования

### JavaScript
```javascript
// Получить список таймеров
fetch('http://localhost/admin/api/timer/list?per-page=10')
  .then(response => response.json())
  .then(data => console.log(data));

// Создать таймер
fetch('http://localhost/admin/api/timer/create', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    task_gid: '12345',
    time: '02:30:00',
    coefficient: 1.2
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### PHP
```php
// Получить список таймеров
$response = file_get_contents('http://localhost/admin/api/timer/list?per-page=10');
$data = json_decode($response, true);

// Создать таймер
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode([
            'task_gid' => '12345',
            'time' => '02:30:00',
            'coefficient' => 1.2
        ])
    ]
];
$context = stream_context_create($options);
$response = file_get_contents('http://localhost/admin/api/timer/create', false, $context);
```

### Python
```python
import requests

# Получить список таймеров
response = requests.get('http://localhost/admin/api/timer/list?per-page=10')
data = response.json()

# Создать таймер
data = {
    'task_gid': '12345',
    'time': '02:30:00',
    'coefficient': 1.2
}
response = requests.post('http://localhost/admin/api/timer/create', json=data)
print(response.json())
```

---

## 🛠️ Postman

1. Откройте Postman
2. Import → File → Выберите `/docs/Masterok-Asana-API.postman_collection.json`
3. Измените переменную `baseUrl` на ваш URL
4. Запускайте запросы из коллекции

📖 **Детальна інструкція:** `/docs/api-postman-testing.md`

---

## ❓ FAQ

**Q: Нужна ли авторизация для использования API?**  
A: Нет, API доступен без авторизации по умолчанию. Можно включить API Key если нужно.

**Q: Где находятся API контроллеры?**  
A: В `/backend/controllers/api/`

**Q: Как изменить базовый URL?**  
A: Измените в настройках сервера или используйте `/admin/api/` как префикс

**Q: API не работает, что делать?**  
A: Проверьте:
- Сервер запущен
- URL правильный (включает `/admin/api/`)
- Проверьте логи в `/backend/runtime/logs/`

---

## 📞 Поддержка

Если что-то не работает:
1. Проверьте логи в `/backend/runtime/logs/`
2. Запустите `./test-api.sh` для диагностики
3. Посмотрите полную документацию в `/docs/api.md`

---

## ✨ Готово!

API настроен и готов к использованию. Начните с простых GET запросов для тестирования.

