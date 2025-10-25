# API JavaScript Examples

Примеры использования API для Timer, ActOfWork и ActOfWorkDetail на JavaScript.

## Конфигурация

```javascript
const API_BASE_URL = 'http://your-domain.com/admin/api';

// Вспомогательная функция для выполнения запросов
async function apiRequest(endpoint, method = 'GET', data = null) {
  const options = {
    method: method,
    headers: {
      'Content-Type': 'application/json',
    }
  };

  if (data && (method === 'POST' || method === 'PUT')) {
    options.body = JSON.stringify(data);
  }

  try {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
    return await response.json();
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
}
```

---

## Timer API Examples

### 1. Получить список таймеров

```javascript
async function getTimers(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await apiRequest(`/timer/list?${params}`);
  
  console.log('Timers:', response.items);
  console.log('Total:', response.pagination.totalCount);
  
  return response;
}

// Использование
getTimers({ status: 0, archive: 0, 'per-page': 20 })
  .then(data => console.log(data));
```

### 2. Получить один таймер

```javascript
async function getTimer(id) {
  const response = await apiRequest(`/timer/view?id=${id}`);
  
  if (response.success) {
    console.log('Timer:', response.data);
    console.log('Price:', response.data.price);
  }
  
  return response;
}

// Использование
getTimer(1).then(data => console.log(data));
```

### 3. Создать таймер

```javascript
async function createTimer(timerData) {
  const data = {
    task_gid: timerData.taskGid,
    time: timerData.time, // "02:30:00"
    coefficient: timerData.coefficient || 1.2,
    comment: timerData.comment || '',
    status: timerData.status || 0,
    archive: 0,
    status_act: 'ok'
  };

  const response = await apiRequest('/timer/create', 'POST', data);
  
  if (response.success) {
    console.log('Timer created with ID:', response.data.id);
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
createTimer({
  taskGid: '12345',
  time: '02:30:00',
  coefficient: 1.2,
  comment: 'Робота над задачею'
}).then(data => console.log(data));
```

### 4. Обновить таймер

```javascript
async function updateTimer(id, updates) {
  const response = await apiRequest(`/timer/update?id=${id}`, 'PUT', updates);
  
  if (response.success) {
    console.log('Timer updated');
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
updateTimer(1, {
  time: '03:00:00',
  coefficient: 1.5,
  comment: 'Оновлений коментар'
}).then(data => console.log(data));
```

### 5. Удалить таймер

```javascript
async function deleteTimer(id) {
  const response = await apiRequest(`/timer/delete?id=${id}`, 'DELETE');
  
  if (response.success) {
    console.log('Timer deleted');
  }
  
  return response;
}

// Использование
deleteTimer(1).then(data => console.log(data));
```

### 6. Получить статистику

```javascript
async function getTimerStatistics(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await apiRequest(`/timer/statistics?${params}`);
  
  if (response.success) {
    console.log('Total records:', response.data.total_records);
    console.log('Total time:', response.data.total_time);
    console.log('Total price:', response.data.total_price);
  }
  
  return response;
}

// Использование
getTimerStatistics({ status: 0, archive: 0 })
  .then(data => console.log(data));
```

### 7. Архивировать/разархивировать таймер

```javascript
async function toggleTimerArchive(id) {
  const response = await apiRequest(`/timer/toggle-archive?id=${id}`, 'POST');
  
  if (response.success) {
    console.log(response.message);
    console.log('Archive status:', response.data.archive);
  }
  
  return response;
}

// Использование
toggleTimerArchive(1).then(data => console.log(data));
```

---

## ActOfWork API Examples

### 1. Получить список актов

```javascript
async function getActsOfWork(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await apiRequest(`/act-of-work/list?${params}`);
  
  console.log('Acts:', response.items);
  
  return response;
}

// Использование
getActsOfWork({ status: 'pending', 'per-page': 20 })
  .then(data => console.log(data));
```

### 2. Получить один акт

```javascript
async function getActOfWork(id) {
  const response = await apiRequest(`/act-of-work/view?id=${id}`);
  
  if (response.success) {
    console.log('Act:', response.data);
    console.log('Total amount:', response.data.total_amount);
    console.log('Details count:', response.data.details.length);
  }
  
  return response;
}

// Использование
getActOfWork(1).then(data => console.log(data));
```

### 3. Создать акт

```javascript
async function createActOfWork(actData) {
  const data = {
    status: actData.status || 'pending',
    type: actData.type || 'act',
    period_type: actData.periodType || 'month',
    period_year: actData.periodYear || new Date().getFullYear().toString(),
    period_month: actData.periodMonth || 'January',
    user_id: actData.userId,
    date: actData.date || new Date().toISOString().split('T')[0],
    description: actData.description || '',
    total_amount: actData.totalAmount,
    paid_amount: actData.paidAmount || 0
  };

  const response = await apiRequest('/act-of-work/create', 'POST', data);
  
  if (response.success) {
    console.log('Act created with ID:', response.data.id);
    console.log('Generated number:', response.data.number);
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
createActOfWork({
  userId: 1,
  periodType: 'month',
  periodYear: '2025',
  periodMonth: 'January',
  date: '2025-01-25',
  description: 'Акт за січень 2025',
  totalAmount: 50000.00
}).then(data => console.log(data));
```

### 4. Обновить акт

```javascript
async function updateActOfWork(id, updates) {
  const response = await apiRequest(`/act-of-work/update?id=${id}`, 'PUT', updates);
  
  if (response.success) {
    console.log('Act updated');
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
updateActOfWork(1, {
  status: 'paid',
  paid_amount: 50000.00,
  description: 'Оновлений опис'
}).then(data => console.log(data));
```

### 5. Изменить статус акта

```javascript
async function changeActStatus(id, newStatus) {
  const response = await apiRequest(
    `/act-of-work/change-status?id=${id}`, 
    'POST', 
    { status: newStatus }
  );
  
  if (response.success) {
    console.log('Status changed to:', response.data.status_text);
  }
  
  return response;
}

// Использование
changeActStatus(1, 'paid').then(data => console.log(data));
```

### 6. Удалить акт

```javascript
async function deleteActOfWork(id) {
  if (!confirm('Ви впевнені, що хочете видалити цей акт?')) {
    return;
  }
  
  const response = await apiRequest(`/act-of-work/delete?id=${id}`, 'DELETE');
  
  if (response.success) {
    console.log('Act deleted');
  }
  
  return response;
}

// Использование
deleteActOfWork(1).then(data => console.log(data));
```

### 7. Получить статистику по актам

```javascript
async function getActStatistics(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await apiRequest(`/act-of-work/statistics?${params}`);
  
  if (response.success) {
    console.log('Total records:', response.data.total_records);
    console.log('Total amount:', response.data.total_amount);
    console.log('Unpaid amount:', response.data.unpaid_amount);
  }
  
  return response;
}

// Использование
getActStatistics({ status: 'pending' })
  .then(data => console.log(data));
```

---

## ActOfWorkDetail API Examples

### 1. Получить список деталей

```javascript
async function getActDetails(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await apiRequest(`/act-of-work-detail/list?${params}`);
  
  console.log('Details:', response.items);
  
  return response;
}

// Использование
getActDetails({ act_of_work_id: 1 })
  .then(data => console.log(data));
```

### 2. Получить одну деталь

```javascript
async function getActDetail(id) {
  const response = await apiRequest(`/act-of-work-detail/view?id=${id}`);
  
  if (response.success) {
    console.log('Detail:', response.data);
  }
  
  return response;
}

// Использование
getActDetail(1).then(data => console.log(data));
```

### 3. Создать деталь акта

```javascript
async function createActDetail(detailData) {
  const data = {
    act_of_work_id: detailData.actOfWorkId,
    time_id: detailData.timeId,
    task_gid: detailData.taskGid,
    project_gid: detailData.projectGid,
    project: detailData.project || '',
    task: detailData.task || '',
    description: detailData.description || '',
    amount: detailData.amount,
    hours: detailData.hours
  };

  const response = await apiRequest('/act-of-work-detail/create', 'POST', data);
  
  if (response.success) {
    console.log('Detail created with ID:', response.data.id);
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
createActDetail({
  actOfWorkId: 1,
  timeId: 10,
  taskGid: '12345',
  projectGid: '67890',
  project: 'Назва проекту',
  task: 'Назва задачі',
  amount: 5000.00,
  hours: 12.5
}).then(data => console.log(data));
```

### 4. Обновить деталь акта

```javascript
async function updateActDetail(id, updates) {
  const response = await apiRequest(`/act-of-work-detail/update?id=${id}`, 'PUT', updates);
  
  if (response.success) {
    console.log('Detail updated');
  } else {
    console.error('Errors:', response.errors);
  }
  
  return response;
}

// Использование
updateActDetail(1, {
  amount: 6000.00,
  hours: 15.0
}).then(data => console.log(data));
```

### 5. Удалить деталь акта

```javascript
async function deleteActDetail(id) {
  const response = await apiRequest(`/act-of-work-detail/delete?id=${id}`, 'DELETE');
  
  if (response.success) {
    console.log('Detail deleted');
  }
  
  return response;
}

// Использование
deleteActDetail(1).then(data => console.log(data));
```

### 6. Получить детали по ID акта

```javascript
async function getDetailsByAct(actId) {
  const response = await apiRequest(`/act-of-work-detail/by-act?act_id=${actId}`);
  
  if (response.success) {
    console.log('Details:', response.data);
    console.log('Summary:', response.summary);
  }
  
  return response;
}

// Использование
getDetailsByAct(1).then(data => console.log(data));
```

---

## Полный пример: Создание акта с деталями

```javascript
async function createActWithDetails(actData, detailsArray) {
  try {
    // Создаем акт
    const actResponse = await createActOfWork(actData);
    
    if (!actResponse.success) {
      throw new Error('Failed to create act');
    }
    
    const actId = actResponse.data.id;
    console.log('Act created with ID:', actId);
    
    // Создаем детали
    const detailPromises = detailsArray.map(detail => 
      createActDetail({
        ...detail,
        actOfWorkId: actId
      })
    );
    
    const detailResponses = await Promise.all(detailPromises);
    
    const successCount = detailResponses.filter(r => r.success).length;
    console.log(`Created ${successCount} of ${detailsArray.length} details`);
    
    // Получаем обновленный акт
    const updatedAct = await getActOfWork(actId);
    
    return updatedAct;
    
  } catch (error) {
    console.error('Error creating act with details:', error);
    throw error;
  }
}

// Использование
const actData = {
  userId: 1,
  periodType: 'month',
  periodYear: '2025',
  periodMonth: 'January',
  date: '2025-01-25',
  description: 'Акт за січень 2025',
  totalAmount: 15000.00
};

const details = [
  {
    timeId: 10,
    taskGid: '12345',
    projectGid: '67890',
    amount: 5000.00,
    hours: 12.5
  },
  {
    timeId: 11,
    taskGid: '12346',
    projectGid: '67890',
    amount: 10000.00,
    hours: 25.0
  }
];

createActWithDetails(actData, details)
  .then(act => console.log('Final act:', act));
```

---

## React Hook Example

```javascript
import { useState, useEffect } from 'react';

// Кастомный хук для работы с Timer API
function useTimers(filters = {}) {
  const [timers, setTimers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    loadTimers();
  }, [JSON.stringify(filters)]);

  const loadTimers = async () => {
    setLoading(true);
    try {
      const response = await getTimers(filters);
      setTimers(response.items || []);
      setError(null);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const create = async (data) => {
    const response = await createTimer(data);
    if (response.success) {
      await loadTimers();
    }
    return response;
  };

  const update = async (id, data) => {
    const response = await updateTimer(id, data);
    if (response.success) {
      await loadTimers();
    }
    return response;
  };

  const remove = async (id) => {
    const response = await deleteTimer(id);
    if (response.success) {
      await loadTimers();
    }
    return response;
  };

  return {
    timers,
    loading,
    error,
    reload: loadTimers,
    create,
    update,
    remove
  };
}

// Использование в компоненте
function TimerList() {
  const { timers, loading, error, create, remove } = useTimers({ 
    status: 0, 
    archive: 0 
  });

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      {timers.map(timer => (
        <div key={timer.id}>
          <span>{timer.taskG?.name}</span>
          <button onClick={() => remove(timer.id)}>Delete</button>
        </div>
      ))}
    </div>
  );
}
```

---

## Обработка ошибок

```javascript
async function safeApiCall(apiFunction, ...args) {
  try {
    const response = await apiFunction(...args);
    
    if (response.success) {
      return { success: true, data: response.data };
    } else {
      // Обработка ошибок валидации
      const errorMessages = Object.values(response.errors || {})
        .flat()
        .join(', ');
      
      return { 
        success: false, 
        error: response.message || errorMessages 
      };
    }
  } catch (error) {
    // Обработка сетевых ошибок
    console.error('Network error:', error);
    return { 
      success: false, 
      error: 'Помилка з\'єднання з сервером' 
    };
  }
}

// Использование
const result = await safeApiCall(createTimer, {
  taskGid: '12345',
  time: '02:30:00',
  coefficient: 1.2
});

if (result.success) {
  console.log('Success:', result.data);
} else {
  console.error('Error:', result.error);
}
```

