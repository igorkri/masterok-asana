# API для Timer, ActOfWork и ActOfWorkDetail

## Описание

REST API для управления таймерами, актами выполненных работ и их деталями в системе учета времени Masterok-Asana.

## Созданные файлы

### API Контроллеры

1. **TimerApiController.php** - `/backend/controllers/api/TimerApiController.php`
   - Управление таймерами (CRUD операции)
   - Статистика по таймерам
   - Архивирование таймеров

2. **ActOfWorkApiController.php** - `/backend/controllers/api/ActOfWorkApiController.php`
   - Управление актами выполненных работ
   - Изменение статусов актов
   - Статистика по актам

3. **ActOfWorkDetailApiController.php** - `/backend/controllers/api/ActOfWorkDetailApiController.php`
   - Управление деталями актов
   - Автоматический пересчет общей суммы акта
   - Статистика по деталям

### Документация

1. **api.md** - `/docs/api.md`
   - Полная документация API
   - Описание всех endpoints
   - Примеры запросов curl
   - Структура ответов

2. **api-javascript-examples.md** - `/docs/api-javascript-examples.md`
   - Примеры использования API на JavaScript
   - React hooks для работы с API
   - Обработка ошибок
   - Полные примеры интеграции

## Быстрый старт

### Базовый URL
```
http://your-domain.com/admin/api/
```

### Примеры использования

#### 1. Получить список таймеров
```bash
curl -X GET "http://your-domain.com/admin/api/timer/list?status=0&per-page=20"
```

#### 2. Создать новый таймер
```bash
curl -X POST "http://your-domain.com/admin/api/timer/create" \
  -H "Content-Type: application/json" \
  -d '{
    "task_gid": "12345",
    "time": "02:30:00",
    "coefficient": 1.2,
    "comment": "Робота над задачею"
  }'
```

#### 3. Получить статистику
```bash
curl -X GET "http://your-domain.com/admin/api/timer/statistics?status=0"
```

## Основные функции

### Timer API
- ✅ Список таймеров с фильтрацией
- ✅ Просмотр деталей таймера
- ✅ Создание таймера
- ✅ Обновление таймера
- ✅ Удаление таймера
- ✅ Статистика (общее время, стоимость)
- ✅ Архивирование/разархивирование

### ActOfWork API
- ✅ Список актов с фильтрацией
- ✅ Просмотр деталей акта (включая детали)
- ✅ Создание акта
- ✅ Обновление акта
- ✅ Удаление акта (с каскадным удалением деталей)
- ✅ Изменение статуса акта
- ✅ Статистика (сумма, оплачено, долг)

### ActOfWorkDetail API
- ✅ Список деталей с фильтрацией
- ✅ Просмотр деталей
- ✅ Создание деталей
- ✅ Обновление деталей
- ✅ Удаление деталей
- ✅ Получение деталей по акту
- ✅ Статистика по деталям
- ✅ Автоматический пересчет total_amount в акте

## Особенности

### CORS
Все API контроллеры настроены с поддержкой CORS:
- Разрешены все источники (`*`)
- Поддержка методов: GET, POST, PUT, PATCH, DELETE, OPTIONS
- Разрешены любые заголовки

### Автоматические действия
1. **При создании/обновлении/удалении ActOfWorkDetail**:
   - Автоматически пересчитывается `total_amount` в связанном ActOfWork

2. **При удалении ActOfWork**:
   - Каскадно удаляются все связанные ActOfWorkDetail

3. **При сохранении Timer**:
   - Автоматически пересчитывается поле `minute` из `time`
   - Обновляется связанная задача в TaskCustomFields
   - Меняется статус задачи на "update"

### Валидация
Все API endpoints выполняют валидацию данных согласно правилам моделей Yii2.

### Формат ответов
Все API endpoints возвращают JSON с единым форматом:

**Успешный ответ:**
```json
{
  "success": true,
  "message": "Операция выполнена успешно",
  "data": { ... }
}
```

**Ошибка:**
```json
{
  "success": false,
  "message": "Описание ошибки",
  "errors": {
    "field_name": ["Ошибка валидации"]
  }
}
```

## Доступные endpoints

### Timer API
- `GET /api/timer/list` - Список таймеров
- `GET /api/timer/view` - Просмотр таймера
- `POST /api/timer/create` - Создание таймера
- `PUT /api/timer/update` - Обновление таймера
- `DELETE /api/timer/delete` - Удаление таймера
- `GET /api/timer/statistics` - Статистика
- `POST /api/timer/toggle-archive` - Архивирование

### ActOfWork API
- `GET /api/act-of-work/list` - Список актов
- `GET /api/act-of-work/view` - Просмотр акта
- `POST /api/act-of-work/create` - Создание акта
- `PUT /api/act-of-work/update` - Обновление акта
- `DELETE /api/act-of-work/delete` - Удаление акта
- `POST /api/act-of-work/change-status` - Изменение статуса
- `GET /api/act-of-work/statistics` - Статистика

### ActOfWorkDetail API
- `GET /api/act-of-work-detail/list` - Список деталей
- `GET /api/act-of-work-detail/view` - Просмотр детали
- `POST /api/act-of-work-detail/create` - Создание детали
- `PUT /api/act-of-work-detail/update` - Обновление детали
- `DELETE /api/act-of-work-detail/delete` - Удаление детали
- `GET /api/act-of-work-detail/by-act` - Детали по акту
- `GET /api/act-of-work-detail/statistics` - Статистика

## Требования

- PHP >= 7.4
- Yii2 Framework
- MySQL/MariaDB
- Расширение PHP: json, mbstring

## Настройка

### 1. Маршруты настроены в:
```
/backend/config/main.php
```

### 2. Структура контроллеров:
```
/backend/controllers/api/
  ├── TimerApiController.php
  ├── ActOfWorkApiController.php
  └── ActOfWorkDetailApiController.php
```

### 3. Модели находятся в:
```
/common/models/
  ├── Timer.php
  ├── ActOfWork.php
  └── ActOfWorkDetail.php
```

## Тестирование

Для тестирования API можно использовать:
- curl (примеры в документации)
- Postman
- Insomnia
- Любой HTTP клиент

## Безопасность

### Рекомендации для production:

1. **Включить аутентификацию**:
   Раскомментировать в контроллерах:
   ```php
   $behaviors['authenticator'] = [
       'class' => HttpBearerAuth::class,
   ];
   ```

2. **Ограничить CORS**:
   Изменить `'Origin' => ['*']` на список разрешенных доменов

3. **Добавить rate limiting**:
   ```php
   $behaviors['rateLimiter'] = [
       'class' => \yii\filters\RateLimiter::class,
   ];
   ```

4. **Логирование**:
   Все запросы логируются стандартным логгером Yii2

## Поддержка

Для вопросов и предложений:
- Создайте issue в репозитории
- Обратитесь к документации в `/docs/`

## Лицензия

См. файл LICENSE.md в корне проекта

