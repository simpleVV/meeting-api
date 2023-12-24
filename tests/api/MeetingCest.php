<?php


namespace Api;

use \ApiTester;
use Codeception\Util\HttpCode;
use Faker\Factory;

class MeetingCest
{
    /**
     * Проверка: Запрос на получение списка собраний будет успешно выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function getAllMeetings(ApiTester $I): void
    {
        //act
        $I->sendGet('meetings');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
    }

    /**
     * Проверка: Запрос на получение записи собрания по ID будет успешно
     * выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function getMeeting(ApiTester $I): void
    {
        //act
        $I->sendGet('meetings/1');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"object"}');
    }

    /**
     * Проверка: Запрос на получение записи собрания по ID неверного формата
     * вернет ответ Not Found.
     * Код ответа 404.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToGetMeetingWithInvalidIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendGet('meetings/b');

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContains('Not Found: Страница не найдена.');
    }

    /**
     * Проверка: Запрос на получение записи собрания по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToGetMeetingWithNonxistentIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendGet('meetings/400');

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContains('"message":"Object not found: 400",');
    }

    /**
     * Проверка: Запрос на создание записи собрания будет успешно выполнен. 
     * Код ответа 201.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function createMeeting(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'meetings',
            [
                'title' => $faker->title,
                'meeting_date' => $faker->date('Y-m-d', 'now'),
                'start_time' => $faker->time("09:00:00"),
                'end_time' => $faker->time("11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'title' => 'string',
                'meeting_date' => 'string',
                'start_time' => 'string',
                'end_time' => 'string',
            ]
        );
    }

    /**
     * Проверка: Запрос на создание записи собрания без указания параметра
     * title вернет ответ с текстом о необходимости заполнения данного поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateMeetingWithoutTitleAndFail(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'meetings',
            [
                'meeting_date' => $faker->date('Y-m-d', 'now'),
                'start_time' => $faker->date("2023-12-06 09:00:00"),
                'end_time' => $faker->date("2023-12-06 11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContains(
            '"message":"Необходимо заполнить «Название»."'
        );
    }

    /**
     * Проверка: Запрос на создание записи собрания без указания параметра
     * start_time вернет ответ с текстом о необходимости заполнения данного
     * поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateMeetingWithoutStartTimeAndFail(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'meetings',
            [
                'title' => $faker->title,
                'meeting_date' => $faker->date('Y-m-d', 'now'),
                'end_time' => $faker->time("11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContains(
            '"message":"Необходимо заполнить «Время начала собрания»."'
        );
    }

    /**
     * Проверка: Запрос на создание записи собрания без указания параметра
     * end_time вернет ответ с текстом о необходимости заполнения данного
     * поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateMeetingWithoutEndTimeAndFail(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'meetings',
            [
                'title' => $faker->title,
                'meeting_date' => $faker->date('Y-m-d', 'now'),
                'start_time' => $faker->time("11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContains(
            '"message":"Необходимо заполнить «Время окончания собрания»."'
        );
    }

    /**
     * Проверка: Запрос на обновление записи собрания по ID будет успешно
     * выполнен. 
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function updateMeeing(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();
        $newTitle = $faker->title;

        //act
        $I->sendPatch(
            'meetings/1',
            [
                'title' => $newTitle
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => $newTitle]);
    }

    /**
     * Проверка: Запрос на обновление записи собрания по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryUpdateMeetingWithNonexistentIDAndFail(ApiTester $I): void
    {
        //arrange
        $faker = Factory::create();
        $newTitle = $faker->title;

        //act
        $I->sendPatch(
            'meetings/400',
            [
                'login' => $newTitle
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Object not found: 400"');
    }

    /**
     * Проверка: Запрос на удаление записи собрания по ID будет успешно
     * выполнен. 
     * Код ответа 204.
     *
     * @return void
     */
    public function deleteMeeting(ApiTester $I): void
    {
        //act
        $I->sendGet('meetings/1');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendDelete('meetings/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $I->sendGet('meetings/1');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * Проверка: Запрос на удаление записи собрания по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryDeleteMeetingWithNonexistentIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendDelete('meetings/400');

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Object not found: 400"');
    }
}
