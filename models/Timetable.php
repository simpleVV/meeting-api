<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


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
 * @SWG\Property(property="dt_creation", type="date-time", description="Время создания")
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
            [['dt_creation'], 'safe'],
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
            'dt_creation' => 'Dt Creation',
        ];
    }


    /**
     * Найти все записи собраний для указанного пользователя
     *   
     * @param int $id
     * @return \yii\db\ActiveQuery
     */
    public function findRecordsForEmployee($id): \yii\db\ActiveQuery
    {
        $query = self::find();

        return $query->where(['employee_id' => $id]);
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }

    /**
     * Gets query for [[Meeting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::class, ['id' => 'meeting_id']);
    }
}
