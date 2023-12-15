<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "meetings".
 *
 * @property int $id
 * @property string $title
 * @property string|null $dt_creation
 * @property string|null $dt_start
 * @property string|null $dt_end
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
 * @SWG\Property(property="dt_start", type="date-time", description="Время начала собрания")
 * @SWG\Property(property="dt_end", type="date-time", description="Время окончания собрания")
 * @SWG\Property(property="dt_creation", type="date-time", description="Время создания")
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
            [['title', 'dt_start', 'dt_end'], 'required'],
            [['dt_creation', 'dt_start', 'dt_end'], 'safe'],
            [['dt_start', 'dt_end'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['title'], 'string', 'max' => 60],
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
            'dt_creation' => 'Dt Creation',
            'dt_start' => 'Время начала собрания',
            'dt_end' => 'Время окончания собрания',
        ];
    }

    /**
     * Найти записи всех собраний на текущий день.
     *
     * @return \yii\db\ActiveQuery
     */
    public function findMeetingsForCurrentDate($date): \yii\db\ActiveQuery
    {
        $query = self::find();

        return $query->where(
            new Expression(
                'DATE(dt_start) = :current_date',
                [':current_date' => date($date)]
            )
        );
    }

    /**
     * Получить массив доступных собраний
     * 
     * @param string $date дата проведения собраний  
     * @param array $timetable расписание собраний
     * @return array|null
     */
    public function findAvailableMeetings(array $timetable, string $date): array|null
    {
        $availableMeetings = array();
        $meetings = $this
            ->findMeetingsForCurrentDate($date)
            ->all();

        if (!empty($meetings)) {
            $meetingWithIntersections = $this
                ->findMeetingIntersections($meetings);

            foreach ($meetingWithIntersections as $meeting) {
                if (
                    !ArrayHelper::isIn($meeting['id'], $timetable)
                    && !ArrayHelper::isIn($meeting['intersections'], $timetable)
                ) {
                    if (empty($meeting["intersections"])) {
                        ArrayHelper::remove($meeting, 'intersections');
                        array_push($availableMeetings, $meeting);
                    }

                    if (
                        !empty($meeting["intersections"])
                        && !ArrayHelper::isIn($meeting, $availableMeetings)
                        && empty(array_intersect($meeting["intersections"], array_column($availableMeetings, "id")))
                    ) {
                        ArrayHelper::remove($meeting, 'intersections');
                        array_push($availableMeetings, $meeting);
                    }
                }
            }

            return $availableMeetings;
        }

        return null;
    }

    /**
     * Найти пересечения по вермении между собраниями  
     * 
     * @param array $data массив встреч без пересечений
     * @return array|null массив встреч с пересечениями
     */
    private function findMeetingIntersections(array $data): array
    {
        $meetings = ArrayHelper::toArray($data, [
            'app\models\Meeting' => [
                'id',
                'title',
                'dt_start',
                'dt_end',
                'intersections' => function () {
                    return [];
                }
            ]
        ]);

        for ($i = 0, $count = count($meetings); $i < $count; ++$i) {
            for ($j = $count - 1, $count = count($meetings); $j >= 0; --$j) {
                if (
                    $meetings[$i]["dt_start"] < $meetings[$j]["dt_end"]
                    && $meetings[$i]["dt_end"] > $meetings[$j]["dt_start"]
                    && $meetings[$i]["dt_start"] !== $meetings[$j]["dt_start"]
                ) {
                    array_push($meetings[$i]["intersections"], $meetings[$j]["id"]);
                }
            }
        }

        uasort($meetings, function ($a, $b) {
            return (int) (count($a['intersections']) > count($b['intersections']));
        });

        return $meetings;
    }

    /**
     * Gets query for [[Timetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetables()
    {
        return $this->hasMany(Timetable::class, ['meeting_id' => 'id']);
    }
}
