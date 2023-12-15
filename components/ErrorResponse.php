<?php

namespace app\components;

use Yii;

class ErrorResponse implements ErrorResponseInterface
{

    public function formErrorResponse(string $message): array
    {
        Yii::$app->response->statusCode = 400;

        return ['error' => $message];
    }
}
