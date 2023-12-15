<?php

namespace app\controllers;

use yii\rest\ActiveController;

use Swagger\Annotations as SWG;

use app\models\Employee;

/**
 * Class EmployeeController
 */
class EmployeeController extends ActiveController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];

    public $modelClass = Employee::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    /**
     * @SWG\Get(
     *     path="/employees",
     *     tags={"Сотрудники"},
     *     summary="Получение списка сотрудников.",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Список сотрудников",
     *         @SWG\Schema(ref = "#/definitions/Employee")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    public function actionIndex()
    {
        return parent::actionIndex();
    }

    /**
     * @SWG\Get(
     *     path="/employees/{id}",
     *     tags={"Сотрудники"},
     *     summary="Получение сотрудника",
     *     description="Получить запись сотрудника по ID.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id сотрудника",
     *     required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Запись сотрудника",
     *         @SWG\Schema(ref="#/definitions/Employee")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Сотрудник не найден"
     *     )
     * )
     */
    public function actionView()
    {
        return parent::actionView();
    }

    /**
     * @SWG\Post(
     *     path="/employees",
     *     tags={"Сотрудники"},
     *     summary="Создание сотрудника",
     *     @SWG\Parameter(name="firstname", in="formData", type="string",
     *     required=true, description="Имя"),
     *     @SWG\Parameter(name="patronymic", in="formData", type="string", 
     *     required=false, description="Отчество"),
     *     @SWG\Parameter(name="lastname", in="formData", type="string",
     *     required=true, description="Фамилия"),
     *     @SWG\Parameter(name="login", in="formData", type="string",
     *     required=true, description="Логин"),
     *     @SWG\Response(
     *         response=201,
     *         description="Успешное создание",
     *         @SWG\Schema(ref="#/definitions/Employee")
     *     )
     * )
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * @SWG\Put(
     *     path="/employees/{id}",
     *     tags={"Сотрудники"},
     *     summary="Обновление сотрудника",
     *     description="Обновляет запись сотрудника по id.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id сотрудника",
     *     required=true, type="integer"),
     *     @SWG\Parameter(name="firstname", in="formData", type="string",
     *     required=false, description="Имя"),
     *     @SWG\Parameter(name="patronymic", in="formData", type="string", 
     *     required=false, description="Отчество"),
     *     @SWG\Parameter(name="lastname", in="formData", type="string",
     *     required=false, description="Фамилия"),
     *     @SWG\Parameter(name="login", in="formData", type="string",
     *     required=false, description="Логин"),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешное обновление",
     *         @SWG\Schema(ref="#/definitions/Employee")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Сотрудник не найден"
     *     )
     * )
     */
    public function actionUpdate()
    {
        return parent::actionUpdate();
    }

    /**
     * @SWG\Delete(
     *     path="/employees/{id}",
     *     summary="Удаление сотрудника",
     *     tags={"Сотрудники"},
     *     description="Удаляет запись сотрудника по id.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id сотрудника",
     *     required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Успешное удаление",
     *         @SWG\Schema(ref="#/definitions/Employee")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Сотрудник не найден"
     *     )
     * )
     */
    public function actionDelete()
    {
        return parent::actionDelete();
    }
}
