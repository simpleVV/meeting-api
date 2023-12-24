<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\rest\Serializer;

use app\models\Meeting;

/**
 * Class MeetingController
 */
class MeetingController extends ActiveController
{

    public $serializer = [
        'class' => Serializer::class,
    ];

    public $modelClass = Meeting::class;

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
     *     path="/meetings",
     *     tags={"Собрания"},
     *     summary="Получение списка собраний.",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Список всех собраний",
     *         @SWG\Schema(ref = "#/definitions/Meeting")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @SWG\Get(
     *     path="/meetings/{id}",
     *     tags={"Собрания"},
     *     summary="Получение собрания",
     *     description="Получить запись собрания по ID.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id собрания",
     *     required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Запись собрания",
     *         @SWG\Schema(ref="#/definitions/Meeting")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Собрание не найдено"
     *     )
     * )
     */
    public function actionView()
    {
        return parent::actionView();
    }

    /**
     * @SWG\Post(
     *     path="/meetings",
     *     tags={"Собрания"},
     *     summary="Создание собрания",
     *     @SWG\Parameter(name="title", in="formData", type="string",
     *     required=true, description="Название"),
     *     @SWG\Parameter(name="meeting_date", in="formData", type="string",
     *     required=true, description="Дата проведения встречи в формате Y-m-d"),
     *     @SWG\Parameter(name="start_time", in="formData", type="string", 
     *     required=true, description="Время начала в формате H:i:s"),
     *     @SWG\Parameter(name="end_time", in="formData", type="string",
     *     required=true, description="Время окончания в формате H:i:s"),
     *     @SWG\Response(
     *         response=201,
     *         description="Успешное создание",
     *         @SWG\Schema(ref="#/definitions/Meeting")
     *     )
     * )
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * @SWG\Put(
     *     path="/meetings/{id}",
     *     tags={"Собрания"},
     *     summary="Обновление собрания",
     *     description="Обновляет запись собрания по id.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id собрания",
     *     required=true, type="integer"),
     *     @SWG\Parameter(name="title", in="formData", type="string",
     *     required=false, description="Название"),
     *     @SWG\Parameter(name="meeting_date", in="formData", type="string",
     *     required=false, description="Дата проведения встречи в формате Y-m-d"),
     *     @SWG\Parameter(name="start_time", in="formData", type="string", 
     *     required=false, description="Время начала в формате H:i:s"),
     *     @SWG\Parameter(name="end_time", in="formData", type="string",
     *     required=false, description="Время окончания в формате H:i:s"),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешное обновление",
     *         @SWG\Schema(ref="#/definitions/Meeting")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Собрание не найдено"
     *     )
     * )
     */
    public function actionUpdate()
    {
        return parent::actionUpdate();
    }

    /**
     * @SWG\Delete(
     *     path="/meetings/{id}",
     *     tags={"Собрания"},
     *     summary="Удаление собрания",
     *     description="Удаляет запись собрания по id.",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="id", in="path", description="id собрания",
     *     required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Успешное удаление",
     *         @SWG\Schema(ref="#/definitions/Meeting")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Собрание не найдено"
     *     )
     * )
     */
    public function actionDelete()
    {
        return parent::actionDelete();
    }
}
