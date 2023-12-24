<?php

namespace app\components;

use Yii;

/**
 * Class ErrorResponse
 */
class ErrorResponse implements ErrorResponseInterface
{
    /**
     * @inheritdoc
     */
    public function formErrorResponse(array $errors): array
    {
        Yii::$app->response->statusCode = 400;

        return ['errors' => $errors];
    }
}
