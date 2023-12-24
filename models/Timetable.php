<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "timetable".
 *
 * @property int $id
 * @property int $meeting_id
 * @property int $employee_id
 * @property string|null $dt_creation
 *
 * @property Employees $employee
 * @property Meetings $meeting
 */

/**
 * @SWG\Definition(
 *     definition="Timetable",
 *     type="object",
 * )
 * @SWG\Property(property="id", type="integer", description="ID записи в расписании")
 * @SWG\Property(property="meeting_id", type="integer", description="ID собрания")
 * @SWG\Property(property="employee_id", type="integer", description="ID сотрудника")
 * @SWG\Property(property="date_creation", type="date-time", description="Время создания")
 */
class Timetable extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meeting_id', 'employee_id'], 'required'],
            [['meeting_id', 'employee_id'], 'integer'],
            [['date_creation'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::class, 'targetAttribute' => ['meeting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meeting_id' => 'Meeting ID',
            'employee_id' => 'Employee ID',
            'date_creation' => 'Дата создания',
        ];
    }


    /**
     * Найти все записи собраний для указанного пользователя
     *   
     * @param int $id
     * @param string $date
     * @return ActiveQuery
     */
    public function findMeetingsForEmployee(int $employeeId, string $date): ActiveQuery
    {
        $query = self::find()
            ->select([
                'meetings.id',
                'meetings.title',
                'meetings.start_time',
                'meetings.end_time',
            ])
            ->leftJoin('meetings', 'meeting_id = meetings.id')
            ->where([
                'employee_id' => $employeeId

            ])->andWhere(
                new Expression(
                    'DATE(meetings.meeting_date) = :current_date',
                    [':current_date' => date($date)]
                )
            );
        return $query;
    }

    /**
     * Получить массив доступных собраний
     * 
     * @param int $employeeId ID сотрудника
     * @param string $date дата проведения собраний  
     * @return array
     */
    public function findAvailableMeetings(int $employeeId, string $date): array
    {
        $availableMeetings = [];
        $meetings = $this
            ->findMeetingsForEmployee($employeeId, $date)
            ->asArray()
            ->all();

        if (empty($meetings)) {
            return [];
        }

        $meetingsWithIntersections = $this
            ->findMeetingIntersections($meetings);

        foreach ($meetingsWithIntersections as $meetingWithIntersections) {

            if (empty($meetingWithIntersections['intersections'])) {
                unset($meetingWithIntersections['intersections']);
                array_push($availableMeetings, $meetingWithIntersections);
            }

            if (
                !empty($meetingWithIntersections['intersections'])
                && !in_array($meetingWithIntersections, $availableMeetings)
                && empty(array_intersect($meetingWithIntersections['intersections'], array_column($availableMeetings, "id")))
            ) {
                unset($meetingWithIntersections['intersections']);
                array_push($availableMeetings, $meetingWithIntersections);
            }
        }

        return $availableMeetings;
    }

    /**
     * Найти пересечения по вермении между собраниями  
     * 
     * @param array $data массив встреч без пересечений
     * @return array|null массив встреч с пересечениями
     */
    private function findMeetingIntersections(array $data): array
    {
        $meetings = $data;

        $count = count($meetings);

        for ($i = 0, $count; $i < $count; ++$i) {

            $meetings[$i]['intersections'] = [];

            for ($j = $count - 1, $count; $j >= 0; --$j) {
                if ($this->checkTimeIntersections($meetings[$i], $meetings[$j])) {
                    array_push($meetings[$i]['intersections'], $meetings[$j]["id"]);
                }
            }
        }

        uasort($meetings, function ($a, $b) {
            return (int) (count($a['intersections']) > count($b['intersections']));
        });

        return $meetings;
    }

    /**
     * Проверить пересечение диапазонов времени 
     * 
     * @param array $firstEvent массив данных первой встречи
     * @param array $secondEvent массив данных второй встречи
     * @return bool
     */
    private function checkTimeIntersections(array $firstEvent, array $secondEvent): bool
    {
        if (
            strtotime($firstEvent['start_time']) < strtotime($secondEvent['end_time'])
            && strtotime($firstEvent['end_time']) > strtotime($secondEvent['start_time'])
            && strtotime($firstEvent['start_time']) !== strtotime($secondEvent['start_time'])
        ) {
            return true;
        }
        return false;
    }


    /**
     * Gets query for [[Employee]].
     *
     * @return ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }

    /**
     * Gets query for [[Meeting]].
     *
     * @return ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::class, ['id' => 'meeting_id']);
    }
}
