<?php

namespace app\core;

/**
 * Класс содержит методы обработки запросов, пока что обрабатываем только json
 *
 * Class Request
 * @package app\core
 */
class Request
{
    public static function getRequest(){
        if(empty($_SERVER['CONTENT_TYPE'])){
            throw new \Exception('Тип не поддерживается');
        }
        $input = file_get_contents('php://input');
        $requestParser = new RequestParser($_SERVER['CONTENT_TYPE'], $input);
        return $requestParser->process();
    }
}