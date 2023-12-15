<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

use Swagger\Annotations as SWG;

/**
 * This is the model class for table "employees".
 * 
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string|null $patronymic
 * @property string $login
 * @property string|null $dt_creation
 *
 * @property Timetable[] $timetables
 */

/**
 * @SWG\Definition(
 *     definition="Employee",
 *     type="object",
 * )
 * @SWG\Property(property="id", type="integer", description="ID сотрудника")
 * @SWG\Property(property="firstname", type="string", description="Имя")
 * @SWG\Property(property="lastname", type="string", description="Отчество")
 * @SWG\Property(property="patronymic", type="string", description="Фамилия")
 * @SWG\Property(property="login", type="string", description="Логин")
 * @SWG\Property(property="dt_creation", type="date-time", description="Время создания")
 */
class Employee extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'login'], 'required'],
            [['dt_creation'], 'safe'],
            [['firstname', 'lastname', 'patronymic', 'login'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'login' => 'Login',
            'dt_creation' => 'Dt Creation',
        ];
    }

    /**
     * Gets query for [[Timetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetables()
    {
        return $this->hasMany(Timetable::class, ['employee_id' => 'id']);
    }
}
