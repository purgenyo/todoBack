<?php

namespace app\core;

/**
 * Класс содержит методы обработки запросов
 *
 * Class Request
 * @package app\core
 */
class Request
{
    public static function getRequest(){
        if($_SERVER['CONTENT_TYPE']=='application/json'){
            $inputJSON = file_get_contents('php://input');
            $inputJSON = json_decode($inputJSON, true);
            if(empty($inputJSON)){
                http_response_code(400);
                throw new \Exception('Ошибка в теле запроса');
            }
            return $inputJSON;
        }
    }
}