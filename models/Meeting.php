<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "meetings".
 *
 * @property int $id
 * @property string $title
 * @property string|null $date_creation
 * @property string|null $meeting_date
 * @property string|null $start_time
 * @property string|null $end_time
 *
 * @property Timetable[] $timetables
 */

/**
 * @SWG\Definition(
 *     definition="Meeting",
 *     type="object",
 * )
 * @SWG\Property(property="id", type="integer", description="ID собрания")
 * @SWG\Property(property="title", type="string", description="Название")
 * @SWG\Property(property="meeting_date", type="date", description="Дата проведения встречи")
 * @SWG\Property(property="start_time", type="time", description="Время начала собрания")
 * @SWG\Property(property="end_time", type="time", description="Время окончания собрания")
 * @SWG\Property(property="date_creation", type="date-time", description="Время создания")
 */
class Meeting extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'meeting_date', 'start_time', 'end_time'], 'required'],
            [['date_creation', 'start_time', 'end_time'], 'safe'],
            [['meeting_date'], 'date', 'format' => 'php: Y-m-d'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:H:i:s'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'date_creation' => 'Дата создания',
            'meeting_date' => 'Дата проведения встречи',
            'start_time' => 'Время начала собрания',
            'end_time' => 'Время окончания собрания',
        ];
    }

    /**
     * Gets query for [[Timetables]].
     *
     * @return ActiveQuery
     */
    public function getTimetables(): ActiveQuery
    {
        return $this->hasMany(Timetable::class, ['meeting_id' => 'id']);
    }
}
