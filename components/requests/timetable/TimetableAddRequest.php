<?php

namespace app\components\requests\timetable;

use Yii;

use app\components\requests\BaseRequestInterface;
use app\models\Employee;
use app\models\Meeting;
use app\models\Timetable;

/**
 * Class TimetableAddRequest
 */
class TimetableAddRequest implements BaseRequestInterface
{
    private string $meetingId;
    private string $employeeId;
    private array $errors = [];

    /**
     * @inheritdoc
     */
    public function setRequestParameters(): void
    {
        $request = Yii::$app->request;

        $this->meetingId = $request->post('meeting_id');
        $this->employeeId = $request->post('employee_id');
    }

    /**
     * @inheritdoc
     */
    public function validateRequest(): array
    {
        $meeting = Meeting::findOne($this->meetingId);
        $employee = Employee::findOne($this->employeeId);

        if (is_null($meeting)) {
            $this->errors['meeting'] = 'Не удалось найти встречу с указанным ID';
        }

        if (is_null($employee)) {
            $this->errors['employee'] = 'Не удалось найти сотрудника с указанным; ID';
        }

        $timetableEntry = Timetable::findOne([
            'meeting_id' => $this->meetingId,
            'employee_id' => $this->employeeId
        ]);

        if (!is_null($timetableEntry)) {
            $this->errors['timetable'] = 'Данный сотрудник уже записан на указанное собрание';
        }

        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function executeReqiest(): Timetable
    {
        $timetableModel = new Timetable();

        $timetableModel->attributes =
            [
                'meeting_id' => $this->meetingId,
                'employee_id' => $this->employeeId
            ];

        if ($timetableModel->save()) {
            return $timetableModel;
        }
    }
}
