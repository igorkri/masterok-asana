# Безопасность и аутентификация API

## Текущая конфигурация

API контроллеры настроены для работы **без авторизации** по умолчанию, так как бекенд защищен паролем.

### Исключения в правилах доступа

В `/backend/config/main.php` добавлены исключения для API контроллеров:

```php
'as beforeAction' => [
    'class' => 'yii\filters\AccessControl',
    'rules' => [
        [
            'allow' => true,
            'controllers' => ['site'],
            'actions' => ['login', 'request-password-reset', 'reset-password'],
            'roles' => ['?'],
        ],
        [
            'allow' => true,
            'controllers' => ['api/timer-api', 'api/act-of-work-api', 'api/act-of-work-detail-api'],
            'roles' => ['?', '@'], // API доступен без авторизации
        ],
        [
            'allow' => true,
            'roles' => ['@'],
        ],
    ],
],
```

Это позволяет обращаться к API endpoints без необходимости входа в систему.

---

## Варианты защиты API

### Вариант 1: Без защиты (текущий)

**Статус:** ✅ Включен по умолчанию

**Описание:** API доступен всем без аутентификации

**Плюсы:**
- Простота использования
- Не требует передачи токенов
- Подходит для внутренних сетей

**Минусы:**
- Нет защиты от несанкционированного доступа
- Не рекомендуется для публичных серверов

**Использование:**
```javascript
// Простой запрос без заголовков
fetch('http://your-domain.com/admin/api/timer/list')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

### Вариант 2: API Key аутентификация

**Статус:** ⚙️ Настроен, но отключен

**Описание:** Проверка API ключа в заголовке `X-API-Key`

**Включение:**

1. Откройте `/backend/config/params.php` и добавьте:
```php
return [
    // ... существующие параметры ...
    'apiKey' => 'your-secret-api-key-here', // Генерируйте сложный ключ
];
```

2. В каждом API контроллере измените:
```php
$behaviors['apiKeyAuth'] = [
    'class' => ApiKeyAuth::class,
    'enabled' => true, // Измените на true
];
```

**Генерация безопасного API ключа:**
```bash
# В консоли Linux/Mac
openssl rand -hex 32

# Или в PHP
php -r "echo bin2hex(random_bytes(32));"
```

**Использование:**
```bash
curl -X GET "http://your-domain.com/admin/api/timer/list" \
  -H "X-API-Key: your-secret-api-key-here"
```

```javascript
fetch('http://your-domain.com/admin/api/timer/list', {
  headers: {
    'X-API-Key': 'your-secret-api-key-here'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Плюсы:**
- Простая реализация
- Достаточно для большинства случаев
- Один ключ для всех запросов

**Минусы:**
- Ключ может быть перехвачен
- Нет разделения прав доступа

---

### Вариант 3: IP фильтрация

Добавьте IP фильтрацию в контроллеры:

```php
public function behaviors()
{
    $behaviors = parent::behaviors();
    
    // IP фильтр
    $behaviors['ipFilter'] = [
        'class' => \yii\filters\AccessControl::class,
        'rules' => [
            [
                'allow' => true,
                'ips' => ['192.168.1.0/24', '10.0.0.0/8'], // Разрешенные IP
            ],
        ],
    ];
    
    return $behaviors;
}
```

**Плюсы:**
- Доступ только с определенных IP
- Хорошо для внутренних API

**Минусы:**
- Не работает с динамическими IP
- Сложно для мобильных приложений

---

### Вариант 4: Bearer Token (JWT)

Для более продвинутой аутентификации можно использовать JWT токены.

**Установка:**
```bash
composer require lcobucci/jwt
```

**Пример реализации в контроллере:**
```php
use yii\filters\auth\HttpBearerAuth;

public function behaviors()
{
    $behaviors = parent::behaviors();
    
    $behaviors['authenticator'] = [
        'class' => HttpBearerAuth::class,
    ];
    
    return $behaviors;
}
```

**Использование:**
```bash
curl -X GET "http://your-domain.com/admin/api/timer/list" \
  -H "Authorization: Bearer your-jwt-token"
```

---

## Рекомендации по безопасности

### Для Development

✅ Можно использовать без аутентификации  
✅ Убедитесь, что сервер доступен только локально  

### Для Production

🔒 **Обязательно включите аутентификацию!**

1. **API Key минимум:**
```php
// backend/config/params.php
'apiKey' => getenv('API_KEY'), // Читаем из переменной окружения
```

2. **HTTPS обязателен:**
- Настройте SSL сертификат
- Редирект с HTTP на HTTPS

3. **Ограничьте CORS:**
```php
'cors' => [
    'Origin' => ['https://your-frontend.com'], // Только ваш домен
    // ... остальное
],
```

4. **Rate Limiting:**
```php
$behaviors['rateLimiter'] = [
    'class' => \yii\filters\RateLimiter::class,
    'enableRateLimitHeaders' => true,
];
```

5. **Логирование:**
```php
// В каждом action
Yii::info([
    'action' => $this->action->id,
    'ip' => Yii::$app->request->userIP,
    'params' => Yii::$app->request->get(),
], 'api');
```

---

## Настройка переменных окружения

Создайте файл `.env` в корне проекта:
```bash
API_KEY=your-generated-secret-key-here
API_ENABLED=true
ALLOWED_ORIGINS=https://your-frontend.com,https://another-domain.com
```

Используйте в `params.php`:
```php
return [
    'apiKey' => getenv('API_KEY') ?: 'default-dev-key',
    'apiEnabled' => getenv('API_ENABLED') === 'true',
    'allowedOrigins' => explode(',', getenv('ALLOWED_ORIGINS') ?: '*'),
];
```

В контроллерах:
```php
$behaviors['apiKeyAuth'] = [
    'class' => ApiKeyAuth::class,
    'enabled' => Yii::$app->params['apiEnabled'] ?? false,
];

$behaviors['corsFilter'] = [
    'class' => Cors::class,
    'cors' => [
        'Origin' => Yii::$app->params['allowedOrigins'] ?? ['*'],
        // ...
    ],
];
```

---

## Мониторинг и логирование

### Логирование API запросов

Добавьте в `backend/config/main.php`:
```php
'log' => [
    'targets' => [
        [
            'class' => \yii\log\FileTarget::class,
            'levels' => ['info', 'error', 'warning'],
            'categories' => ['api'],
            'logFile' => '@runtime/logs/api.log',
            'maxFileSize' => 1024 * 2, // 2MB
            'maxLogFiles' => 10,
        ],
    ],
],
```

В контроллерах логируйте важные действия:
```php
public function actionCreate()
{
    Yii::info([
        'action' => 'create',
        'controller' => 'timer-api',
        'ip' => Yii::$app->request->userIP,
        'data' => Yii::$app->request->post(),
    ], 'api');
    
    // ... остальной код
}
```

---

## Примеры использования с разными методами

### С API Key

```javascript
// Конфигурация
const API_CONFIG = {
  baseUrl: 'http://your-domain.com/admin/api',
  apiKey: 'your-api-key-here'
};

// Helper функция
async function apiRequest(endpoint, method = 'GET', data = null) {
  const options = {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'X-API-Key': API_CONFIG.apiKey // Добавляем API ключ
    }
  };

  if (data && (method === 'POST' || method === 'PUT')) {
    options.body = JSON.stringify(data);
  }

  const response = await fetch(`${API_CONFIG.baseUrl}${endpoint}`, options);
  return await response.json();
}

// Использование
const timers = await apiRequest('/timer/list?per-page=10');
```

### С Bearer Token

```javascript
const API_CONFIG = {
  baseUrl: 'http://your-domain.com/admin/api',
  token: 'your-jwt-token'
};

async function apiRequest(endpoint, method = 'GET', data = null) {
  const options = {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${API_CONFIG.token}` // Bearer токен
    }
  };
  
  // ... остальной код
}
```

---

## Тестирование безопасности

### Проверка доступа без API ключа

```bash
# Должен вернуть ошибку если включена аутентификация
curl -X GET "http://your-domain.com/admin/api/timer/list"
```

### Проверка с правильным API ключом

```bash
# Должен вернуть данные
curl -X GET "http://your-domain.com/admin/api/timer/list" \
  -H "X-API-Key: your-api-key"
```

### Проверка с неправильным API ключом

```bash
# Должен вернуть ошибку 401
curl -X GET "http://your-domain.com/admin/api/timer/list" \
  -H "X-API-Key: wrong-key"
```

---

## Быстрая настройка для production

1. **Включите API Key:**
```bash
cd /home/igor/developer/masterok-asana
echo "API_KEY=$(openssl rand -hex 32)" >> .env
```

2. **Обновите params.php:**
```php
'apiKey' => getenv('API_KEY'),
```

3. **Включите аутентификацию в контроллерах:**
```php
'enabled' => true, // В apiKeyAuth
```

4. **Ограничьте CORS:**
```php
'Origin' => ['https://your-domain.com'],
```

5. **Перезапустите сервер**

---

## FAQ

**Q: Нужна ли аутентификация для локальной разработки?**  
A: Нет, для локальной разработки можно оставить без аутентификации.

**Q: Как защитить API в production?**  
A: Минимум - включите API Key аутентификацию и HTTPS.

**Q: Можно ли использовать session-based auth?**  
A: Да, но это не рекомендуется для REST API. Лучше использовать токены.

**Q: Что делать если API ключ скомпрометирован?**  
A: Немедленно сгенерируйте новый ключ и обновите на всех клиентах.

**Q: Нужно ли логировать все API запросы?**  
A: Рекомендуется логировать минимум операции изменения данных (POST, PUT, DELETE).

