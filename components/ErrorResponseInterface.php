<?php

namespace app\components;

/**
 * Interface ErrorResponseInterface
 */
interface ErrorResponseInterface
{
    /**
     * Сформировать массив с сообщением об ошибке
     * 
     * @param array $message сообщение об ошибке
     * @return array
     */
    public function formErrorResponse(array $errors): array;
}
