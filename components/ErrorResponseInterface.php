<?php

namespace app\components;

interface ErrorResponseInterface
{
    /**
     * Сформировать массив с сообщением об ошибке
     * 
     * @param string $message сообщение об ошибке
     * @return array
     */
    public function formErrorResponse(string $message): array;
}
