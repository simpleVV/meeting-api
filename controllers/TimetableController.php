<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\rest\Serializer;
use yii\web\Response;

use app\models\Timetable;
use app\components\ErrorResponseInterface;
use app\components\requests\timetable\TimetableAddRequest;
use app\components\requests\timetable\TimetableGenerateRequest;

/**
 * Class TimetableController
 */
class TimetableController extends ActiveController
{
    public $serializer = [
        'class' => Serializer::class,
    ];

    public $modelClass = Timetable::class;
    public $enableCsrfValidation = false;
    private $errorResponse;

    public function __construct(
        $id,
        $module,
        ErrorResponseInterface $errorResponse,
        $config = []
    ) {
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
     * Добавить пользователя на собрание
     *
     * @return Response
     */
    public function actionAdd(): Response
    {
        $request = new TimetableAddRequest;

        $request->setRequestParameters();

        $errors = $request->validateRequest();

        if (!empty($errors)) {

            $errorData = $this->errorResponse->formErrorResponse($errors);

            return $this->asJson($errorData);
        }

        return $this->asJson(
            $request->executeReqiest()
        );
    }

    /**
     * @SWG\Post(
     *     path="/generate-timetable",
     *     tags={"Расписание"},
     *     summary="Получение списка доступных собраний.",
     *     description="Получение списка собраний, для сотрудника на указанную. дату",
     *     @SWG\Parameter(name="employee_id", in="formData", description="id сотрудника",
     *     required=true, type="integer"),
     *     @SWG\Parameter(name="date", in="formData", description="дата проведения собраний в формате Y-m-d",
     *     required=true, type="string"),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Список доступных собраний",
     *         @SWG\Schema(ref = "#/definitions/Timetable")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    /**
     * Сформировать список собраний для конкретного пользователя
     *  
     * @return Response
     */
    public function actionGenerate(): Response
    {
        $request = new TimetableGenerateRequest;

        $request->setRequestParameters();

        $errors = $request->validateRequest();

        if (!empty($errors)) {

            $errorData = $this->errorResponse->formErrorResponse($errors);

            return $this->asJson($errorData);
        }

        return $this->asJson(
            $request->executeReqiest()
        );
    }
}
