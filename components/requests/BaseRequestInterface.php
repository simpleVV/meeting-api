<?php

namespace app\components\requests;

/**
 * Interface ErrorResponse
 */
interface BaseRequestInterface
{
    /**
     * Устанавливает параметры запроса.
     * 
     * @return void
     */
    public function setRequestParameters(): void;

    /**
     * Проверка запроса на валидность.
     * 
     * @return array массив ошибок
     */
    public function validateRequest(): array;

    /**
     * Выполнить запрос
     * 
     * @return mixed результат запроса
     */
    public function executeReqiest();
}
