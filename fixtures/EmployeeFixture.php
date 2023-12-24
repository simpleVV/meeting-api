<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\models\Employee;

/**
 * Class EmployeeFixture
 */
class EmployeeFixture extends ActiveFixture
{
    public $modelClass = Employee::class;
}
