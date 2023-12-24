<?php

namespace app\components\requests\timetable;

use Yii;

use app\components\requests\BaseRequestInterface;
use app\models\Employee;
use app\models\Timetable;

/**
 * Class TimetableGenerateRequest
 */
class TimetableGenerateRequest implements BaseRequestInterface
{
    private string $employeeId;
    private string $date;
    private array $errors = [];

    /**
     * @inheritdoc
     */
    public function setRequestParameters(): void
    {
        $request = Yii::$app->request;

        $this->employeeId = $request->post('employee_id');
        $this->date = $request->post('date');
    }

    /**
     * @inheritdoc
     */
    public function validateRequest(): array
    {
        $employee = Employee::findOne($this->employeeId);

        if (is_null($employee)) {
            $this->errors['employee'] = 'Не удалось найти сотрудника с указанным ID';
        }

        $dateRegExpFormat = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

        if (!preg_match($dateRegExpFormat, $this->date)) {
            $this->errors['data'] = 'Дата проведения собрания должна быть формата Y-m-d';
        }

        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function executeReqiest(): array
    {
        $timetableModel = new Timetable();

        $meetings = $timetableModel
            ->findAvailableMeetings($this->employeeId, $this->date);

        return $meetings;
    }
}
