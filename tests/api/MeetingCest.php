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

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'title' => ['type' => 'string'],
                    'dt_start' => ['type' => 'string'],
                    'dt_end' => ['type' => 'string']
                ]
            ]
        );

        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
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

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'title' => ['type' => 'string'],
                    'dt_start' => ['type' => 'string'],
                    'dt_end' => ['type' => 'string']
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
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
                'dt_start' => $faker->date("2023-12-06 09:00:00"),
                'dt_end' => $faker->date("2023-12-06 11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'title' => 'string',
                'dt_start' => 'string',
                'dt_end' => 'string',
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
                'dt_start' => $faker->date("2023-12-06 09:00:00"),
                'dt_end' => $faker->date("2023-12-06 11:00:00")
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContains(
            '"message":"Необходимо заполнить «Название»."'
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

    /**
     * Проверка: Запрос на получение собраний для конкретного сотрудника будет
     * успешно выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function generateMeetingsDataForEmployee(
        ApiTester $I
    ): void {
        //act
        $I->sendGet('meetings/1/2023-12-04');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'title' => ['type' => 'string'],
                    'dt_start' => ['type' => 'string'],
                    'dt_end' => ['type' => 'string']
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    /**
     * Проверка: Запрос на получение собраний для конкретного сотрудника вернет
     * список собраний без записей, где уже присутствует
     * сотрудник.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function generateMeetingsDataForEmployeeWithoutExistingEntry(
        ApiTester $I
    ): void {
        //arrange
        $I->sendPost(
            'timetable',
            [
                'meeting_id' => 2,
                'employee_id' => 1,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContains('"meeting_id":"2","employee_id":"1"');

        //act
        $I->sendGet('meetings/1/2023-12-04');

        //assertм
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->cantSeeResponseContains('"id":2,"title":"Собрание 2"');
    }

    /**
     * Проверка: Запрос на получение собраний для сотрудника с несуществующим
     * ID вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateMeetingsDataWithNonxistentEmployeeIdAndFail(
        ApiTester $I
    ): void {
        //act
        $I->sendGet('meetings/1000/2023-12-04');

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson(
            [
                'error' => 'Не удалось найти сотрудника с указанным ID'
            ]
        );
    }

    /**
     * Проверка: Запрос на получение собраний для сотрудника с указанием даты
     * не верного формата, вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateMeetingsDataWithInvalidDateFormatAndFail(
        ApiTester $I
    ): void {
        //act
        $I->sendGet('meetings/1/test');

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson(
            [
                'error' => 'Дата проведения собрания должна быть формата Y-m-d'
            ]
        );
    }

    /**
     * Проверка: Запрос на получение собраний для сотрудника с указанием ID не
     * верного формата вернет Not Found.
     * Код ответа 404.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateMeetingsDataWithInvalidEmployeeIdAndFail(
        ApiTester $I
    ): void {
        //act
        $I->sendGet('meetings/test/2023-12-04');

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContains('Not Found: Страница не найдена.');
    }

    /**
     * Проверка: Запрос на получение собраний на дату, где нет записей,
     * вернет null.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateMeetingsDataWithNotActualDateAndReturnNull(
        ApiTester $I
    ): void {
        //act
        $I->sendGet('meetings/1/2023-11-04');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"null"}');
    }
}
