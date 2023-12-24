<?php


namespace Api;

use \ApiTester;
use Codeception\Util\HttpCode;
use Faker\Factory;

class EmployeeCest
{
    /**
     * Проверка: Запрос на получение списка сотрудников будет успешно
     * выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function getAllEmployees(ApiTester $I): void
    {
        //act
        $I->sendGet('employees');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'firstname' => ['type' => 'string'],
                    'lastname' => ['type' => 'string'],
                    'patronymic' => ['type' => 'string']
                ]
            ]
        );

        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    /**
     * Проверка: Запрос на получение записи сотрудника по ID будет успешно
     * выполнен.
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function getEmployee(ApiTester $I): void
    {
        //act
        $I->sendGet('employees/1');

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"object"}');

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'firstname' => ['type' => 'string'],
                    'lastname' => ['type' => 'string'],
                    'patronymic' => ['type' => 'string']
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    /**
     * Проверка: Запрос на получение записи сотрудника по ID неверного формата
     * вернет ответ Not Found.
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToGetEmployeeWithInvalidIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendGet('employees/a');

        //asset
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContains('Not Found: Страница не найдена.');
    }

    /**
     * Проверка: Запрос на получение записи сотрудника по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToGetEmployeeWithNonexistentIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendGet('employees/10000');

        //asset
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContains('"message":"Object not found: 10000",');
    }

    /**
     * Проверка: Запрос на создание записи сотрудника будет успешно выполнен. 
     * Код ответа 201.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function createEmployee(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $I->sendPost(
            'employees',
            [
                'firstname' => $faker->firstname,
                'lastname' => $faker->lastName,
                'patronymic' => "",
                'login' => $faker->email
            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'firstname' => 'string',
                'lastname' => 'string',
                'patronymic' => 'string',
                'login' => 'string'
            ]
        );
    }

    /**
     * Проверка: Запрос на создание записи сотрудника без указания параметра
     * login вернет ответ с текстом о необходимости заполнения данного поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateEmployeeWithoutLoginAndFail(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $I->sendPost(
            'employees',
            [
                'firstname' => $faker->name,
                'lastname' => $faker->lastName,
                'patronymic' => "",
            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContains(
            '"message":"Необходимо заполнить «Login»."'
        );
    }

    /**
     * Проверка: Запрос на создание записи сотрудника без указания параметра
     * firstname вернет ответ с текстом о необходимости заполнения данного поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateEmployeeWithoutFirstname(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $I->sendPost(
            'employees',
            [
                'lastname' => $faker->lastName,
                'patronymic' => "",
                'login' => $faker->email
            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                // 'firstname' => 'string',
                'lastname' => 'string',
                'patronymic' => 'string',
                'login' => 'string'
            ]
        );
    }

    /**
     * Проверка: Запрос на создание записи сотрудника без указания параметра
     * lastname вернет ответ с текстом о необходимости заполнения данного поля.
     * Код ответа 422.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryToCreateEmployeeWithoutLastname(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $I->sendPost(
            'employees',
            [
                'firstname' => $faker->name,
                'patronymic' => "",
                'login' => $faker->email

            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'firstname' => 'string',
                // 'lastname' => 'string',
                'patronymic' => 'string',
                'login' => 'string'
            ]
        );
    }

    /**
     * Проверка: Запрос на обновление записи сотрудника по ID будет успешно
     * выполнен. 
     * Код ответа 200.
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function updateEmployee(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $newLogin = $faker->name;

        $I->sendPatch(
            'employees/1',
            [
                'login' => $newLogin
            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['login' => $newLogin]);
    }

    /**
     * Проверка: Запрос на обновление записи сотрудника по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryUpdateEmployeeWithNonexistentIDAndFail(ApiTester $I): void
    {
        //act
        $faker = Factory::create();

        $newLogin = $faker->name;

        $I->sendPatch(
            'employees/100400',
            [
                'login' => $newLogin
            ]
        );

        //asset
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Object not found: 100400"');
    }

    /**
     * Проверка: Запрос на удаление записи сотрудника по ID будет успешно
     * выполнен. 
     * Код ответа 204.
     *
     * @return void
     */
    public function deleteEmployee(ApiTester $I): void
    {
        //act
        $I->sendGet('employees/1');
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendDelete('employees/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $I->sendGet('employees/1');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * Проверка: Запрос на удаление записи сотрудника по несуществующему ID
     * вернет ответ Object not found. 
     * Код ответа 404. 
     * Ответ получаем в формате JSON.
     *
     * @return void
     */
    public function tryDeleteEmployeeWithNonexistentIDAndFail(ApiTester $I): void
    {
        //act
        $I->sendDelete('employees/100000');

        //asset
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Object not found: 100000"');
    }
}
