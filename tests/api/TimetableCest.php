<?php


namespace Api;

use \ApiTester;
use Codeception\Util\HttpCode;
use Faker\Factory;

class TimetableCest
{
    /**
     * Проверка: Запрос на получение расписания будет успешно выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function getAllTimetable(ApiTester $I): void
    {
        //act
        $I->sendGet('timetables');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
    }

    /**
     * Проверка: Запрос на создание записи в расписании будет успешно выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function createOneEntryInTimeTable(ApiTester $I): void
    {
        //act
        $I->sendPost(
            'timetable',
            [
                'meeting_id' => 1,
                'employee_id' => 3,
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'meeting_id' => 'string',
                'employee_id' => 'string',
            ]
        );
    }

    /**
     * Проверка: Запрос на создание записи в расписании с передачей
     * несуществующего ID встречи, вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryCreateTimetableWithNonExistenMeetingIDAndFail(
        ApiTester $I
    ): void {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'timetable',
            [
                'meeting_id' => $faker->biasedNumberBetween(100, 500),
                'employee_id' => $faker->biasedNumberBetween(1, 5),
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson(
            [
                'error' => 'Не удалось найти встречу с указанным ID'
            ]
        );
    }

    /**
     * Проверка: Запрос на создание записи в расписании с передачей
     * несуществующего ID сотрудника, вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryCreateTimetableWithNonExistenEmployeeIDAndFail(
        ApiTester $I
    ): void {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'timetable',
            [
                'meeting_id' => $faker->biasedNumberBetween(1, 5),
                'employee_id' => $faker->biasedNumberBetween(100, 500),
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson(
            [
                'error' => 'Не удалось найти сотрудника с указанным ID'
            ]
        );
    }


    /**
     * Проверка: Запрос на создание записи в расписании с одинаковыми
     * параметрами вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryCreateTimetableWithExistingDataAndFail(
        ApiTester $I
    ): void {
        //arrange
        $I->sendGet('timetables');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContains(
            '"meeting_id":1,"employee_id":1'
        );

        //act
        $I->sendPost(
            'timetable',
            [
                'meeting_id' => 1,
                'employee_id' => 1,
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson(
            [
                'error' => 'Данный сотрудник уже записан на указанное собрание'
            ]
        );
    }

    /**
     * Проверка: Запрос на получение собраний для конкретного сотрудника будет
     * успешно выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function generateTimetableDataForEmployee(
        ApiTester $I
    ): void {
        //act
        $I->sendPost(
            'generate-timetable',
            [
                'employee_id' => 1,
                'date' => '2023-12-20'
            ]
        );

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
     * Проверка: Запрос на получение собраний для сотрудника с несуществующим
     * ID вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateTimetableDataWithNonxistentEmployeeIdAndFail(
        ApiTester $I
    ): void {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'generate-timetable',
            [
                'employee_id' => $faker->numberBetween(1000, 2000),
                'date' => '2023-12-20'
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContains('"employee":"Не удалось найти сотрудника с указанным ID"');
    }

    /**
     * Проверка: Запрос на получение собраний для сотрудника с указанием даты
     * не верного формата, вернет ответ с текстом ошибки.
     * Код ответа 400.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateTimetableDataWithInvalidDateFormatAndFail(
        ApiTester $I
    ): void {
        //act
        $I->sendPost(
            'generate-timetable',
            [
                'employee_id' => 1,
                'date' => 'test'
            ]
        );;

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContains('"data":"Дата проведения собрания должна быть формата Y-m-d"');
    }

    /**
     * Проверка: Запрос на получение собраний для сотрудника с указанием ID не
     * верного формата вернет Not Found.
     * Код ответа 404.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateTimetableDataWithInvalidEmployeeIdAndFail(
        ApiTester $I
    ): void {
        //arrange
        $faker = Factory::create();

        //act
        $I->sendPost(
            'generate-timetable',
            [
                'employee_id' => $faker->text(),
                'date' => '2022-12-20'
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContains('"employee":"Не удалось найти сотрудника с указанным ID"');
    }

    /**
     * Проверка: Запрос на получение собраний на дату, где нет записей,
     * вернет пустой массив.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryGenerateTimetableDataWithNotActualDateAndReturnEmptyArray(
        ApiTester $I
    ): void {
        //act
        $I->sendPost(
            'generate-timetable',
            [
                'employee_id' => 1,
                'date' => '2022-12-20'
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
        $I->seeResponseContains('[]');
    }
}
