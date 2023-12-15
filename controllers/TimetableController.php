<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

use app\models\Timetable;
use app\models\Meeting;
use app\models\Employee;
use app\components\ErrorResponseInterface;

/**
 * Class TimetableController
 */
class TimetableController extends ActiveController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];

    public $modelClass = Timetable::class;

    public $enableCsrfValidation = false;
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

        unset($actions['view']);
        unset($actions['create']);

        return $actions;
    }

    /**
     * @SWG\Get(
     *     path="/timetable",
     *     tags={"Расписание"},
     *     summary="Получение расписания собраний.",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Расписание собраний",
     *         @SWG\Schema(ref = "#/definitions/Timetable")
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
     * @SWG\Post(
     *     path="/timetable",
     *     tags={"Расписание"},
     *     summary="Создание записи в расписании",
     *     @SWG\Parameter(name="meeting_id", in="formData", type="integer",
     *     required=true, description="ID собрания"),
     *     @SWG\Parameter(name="employee_id", in="formData", type="integer", 
     *     required=true, description="ID сотрудника"),
     *     @SWG\Response(
     *         response=201,
     *         description="Успешное создание",
     *         @SWG\Schema(ref="#/definitions/Timetable")
     *     )
     * )
     */
    /**
     * Добавить пользователя на встречу
     *
     * @return \yii\web\Response
     */
    public function actionAdd(): \yii\web\Response
    {
        $request = Yii::$app->request;

        $meetingId = $request->post('meeting_id');
        $meeting = Meeting::findOne($meetingId);

        $employeeId = $request->post('employee_id');
        $employee = Employee::findOne($employeeId);

        if (is_null($meeting)) {
            return $this->asJson(
                $this->errorResponse->formErrorResponse(
                    'Не удалось найти встречу с указанным ID'
                )
            );
        }

        if (is_null($employee)) {
            return $this->asJson(
                $this->errorResponse->formErrorResponse(
                    'Не удалось найти сотрудника с указанным ID'
                )
            );
        }

        $timetableEntry = Timetable::findOne([
            'meeting_id' => $meetingId,
            'employee_id' => $employeeId
        ]);

        if (!is_null($timetableEntry)) {
            return $this->asJson(
                $this->errorResponse->formErrorResponse(
                    'Данный сотрудник уже записан на указанное собрание'
                )
            );
        }

        $timetableItem = new Timetable();

        $timetableItem->attributes =
            [
                'meeting_id' => $meetingId,
                'employee_id' => $employeeId
            ];

        if ($timetableItem->save()) {
            return $this->asJson($timetableItem);
        }
    }
}
