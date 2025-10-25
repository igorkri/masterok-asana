<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\Timer;

/**
 * Тесты для Timer API
 */
class TimerApiCest
{
    public function _before(FunctionalTester $I)
    {
        // Подготовка перед каждым тестом
    }

    /**
     * Тест получения списка таймеров
     */
    public function testGetTimersList(FunctionalTester $I)
    {
        $I->wantTo('получить список таймеров через API');
        $I->sendGET('/api/timer/list?per-page=5');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'items' => [],
        ]);
    }

    /**
     * Тест получения статистики таймеров
     */
    public function testGetTimerStatistics(FunctionalTester $I)
    {
        $I->wantTo('получить статистику таймеров');
        $I->sendGET('/api/timer/statistics?archive=0');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
            'data' => [
                'total_records' => null,
                'total_minutes' => null,
                'total_time' => null,
                'total_price' => null,
            ],
        ]);
    }

    /**
     * Тест получения несуществующего таймера
     */
    public function testGetNonExistentTimer(FunctionalTester $I)
    {
        $I->wantTo('получить несуществующий таймер');
        $I->sendGET('/api/timer/view?id=999999');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    /**
     * Тест создания таймера
     */
    public function testCreateTimer(FunctionalTester $I)
    {
        $I->wantTo('создать новый таймер через API');

        // Создаем таймер
        $I->sendPOST('/api/timer/create', [
            'task_gid' => '12345',
            'time' => '02:30:00',
            'coefficient' => 1.2,
            'comment' => 'Test timer',
            'status' => 0,
            'archive' => 0,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);

        // Получаем ID созданного таймера
        $response = json_decode($I->grabResponse(), true);
        $timerId = $response['data']['id'] ?? null;

        $I->assertNotNull($timerId, 'Timer ID should not be null');

        // Проверяем, что таймер создан в БД
        $I->seeRecord(Timer::class, [
            'id' => $timerId,
            'task_gid' => '12345',
        ]);
    }

    /**
     * Тест обновления таймера
     */
    public function testUpdateTimer(FunctionalTester $I)
    {
        $I->wantTo('обновить таймер через API');

        // Создаем тестовый таймер
        $timer = new Timer();
        $timer->task_gid = '54321';
        $timer->time = '01:00:00';
        $timer->minute = 60;
        $timer->coefficient = 1.0;
        $timer->status = 0;
        $timer->archive = 0;
        $timer->save(false);

        // Обновляем таймер
        $I->sendPUT("/api/timer/update?id={$timer->id}", [
            'time' => '02:00:00',
            'coefficient' => 1.5,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);

        // Проверяем обновление в БД
        $I->seeRecord(Timer::class, [
            'id' => $timer->id,
            'coefficient' => 1.5,
        ]);

        // Удаляем тестовый таймер
        $timer->delete();
    }

    /**
     * Тест удаления таймера
     */
    public function testDeleteTimer(FunctionalTester $I)
    {
        $I->wantTo('удалить таймер через API');

        // Создаем тестовый таймер
        $timer = new Timer();
        $timer->task_gid = '11111';
        $timer->time = '01:00:00';
        $timer->minute = 60;
        $timer->coefficient = 1.0;
        $timer->status = 0;
        $timer->archive = 0;
        $timer->save(false);

        $timerId = $timer->id;

        // Удаляем таймер
        $I->sendDELETE("/api/timer/delete?id={$timerId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);

        // Проверяем, что таймер удален из БД
        $I->dontSeeRecord(Timer::class, ['id' => $timerId]);
    }

    /**
     * Тест архивирования таймера
     */
    public function testToggleTimerArchive(FunctionalTester $I)
    {
        $I->wantTo('архивировать таймер через API');

        // Создаем тестовый таймер
        $timer = new Timer();
        $timer->task_gid = '22222';
        $timer->time = '01:00:00';
        $timer->minute = 60;
        $timer->coefficient = 1.0;
        $timer->status = 0;
        $timer->archive = 0;
        $timer->save(false);

        // Архивируем таймер
        $I->sendPOST("/api/timer/toggle-archive?id={$timer->id}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
            'data' => [
                'archive' => 1,
            ],
        ]);

        // Проверяем в БД
        $I->seeRecord(Timer::class, [
            'id' => $timer->id,
            'archive' => 1,
        ]);

        // Разархивируем
        $I->sendPOST("/api/timer/toggle-archive?id={$timer->id}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'success' => true,
            'data' => [
                'archive' => 0,
            ],
        ]);

        // Удаляем тестовый таймер
        $timer->delete();
    }

    /**
     * Тест фильтрации таймеров
     */
    public function testFilterTimers(FunctionalTester $I)
    {
        $I->wantTo('фильтровать таймеры через API');

        // Тест фильтра по статусу
        $I->sendGET('/api/timer/list?status=0&per-page=10');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        // Тест фильтра по архиву
        $I->sendGET('/api/timer/list?archive=1&per-page=10');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Тест валидации при создании таймера
     */
    public function testCreateTimerValidation(FunctionalTester $I)
    {
        $I->wantTo('проверить валидацию при создании таймера');

        // Попытка создать таймер без обязательных полей
        $I->sendPOST('/api/timer/create', [
            'comment' => 'Test without required fields',
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
        ]);
    }
}

