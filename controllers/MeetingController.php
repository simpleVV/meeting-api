<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

use app\models\Timetable;
use app\models\Meeting;
use app\models\Employee;
use app\components\ErrorResponseInterface;

/**
 * Class MeetingController
 */
class MeetingController extends ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];

    public $modelClass = Meeting::class;
    protected $errorResponse;

    public function __construct($id, $module, ErrorResponseInterface $errorResponse, $config = [])
    {
        $this->errorResponse = $errorResponse;
        parent::__construct($id, $module, $config);
    }

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
     *     @SWG\Parameter(name="dt_start", in="formData", type="string", 
     *     required=false, description="Время начала"),
     *     @SWG\Parameter(name="dt_end", in="formData", type="string",
     *     required=true, description="Время окончания"),
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
     *     @SWG\Parameter(name="dt_start", in="formData", type="string", 
     *     required=false, description="Время начала в формате Y-m-d H:i:s"),
     *     @SWG\Parameter(name="dt_end", in="formData", type="string",
     *     required=false, description="Время окончания в формате Y-m-d H:i:s"),
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

    /**
     * @SWG\Get(
     *     path="/meetings/{id}/{date}",
     *     tags={"Собрания"},
     *     summary="Получение списка доступных собраний.",
     *     description="Получение списка собраний, для сотрудника на указанную. дату",
     *     @SWG\Parameter(name="id", in="path", description="id сотрудника",
     *     required=true, type="integer"),
     *     @SWG\Parameter(name="date", in="path", description="дата проведения собраний в формате Y-m-d",
     *     required=true, type="string"),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Список доступных собраний",
     *         @SWG\Schema(ref = "#/definitions/Meeting")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    /**
     * 
     * Сформировать список собраний для конкретного пользователя
     *  
     * @param int $id идентификатор пользователя
     * @param string $date дата проведения собрания формата Y-m-d
     * @return \yii\web\Response
     */
    public function actionGenerate(int $id, string $date): \yii\web\Response
    {
        $employee = Employee::findOne($id);

        if (is_null($employee)) {
            return $this->asJson(
                $this->errorResponse->formErrorResponse(
                    'Не удалось найти сотрудника с указанным ID'
                )
            );
        }

        $dateRegExpFormat = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

        if (!preg_match($dateRegExpFormat, $date)) {
            return
                $this->asJson(
                    $this->errorResponse->formErrorResponse(
                        'Дата проведения собрания должна быть формата Y-m-d'
                    )
                );
        }

        $timetableModel = new Timetable();
        $timetableRecords = ArrayHelper::getColumn(
            $timetableModel
                ->findRecordsForEmployee($id)
                ->all(),
            'meeting_id'
        );

        $meetingModel = new Meeting();
        $meetings = $meetingModel
            ->findAvailableMeetings($timetableRecords, $date);

        return $this->asJson($meetings);
    }
}
