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
        if(empty($_SERVER['CONTENT_TYPE'])){
            throw new \Exception('Тип не поддерживается');
        }
        $input = file_get_contents('php://input');
        return self::parseRequest($_SERVER['CONTENT_TYPE'], $input);
    }

    public static function parseRequest( $content_type, $input ){
        if(preg_match('/(application\/json)/', $content_type)!==false){
            $inputJSON = json_decode($input, true);
            if(empty($inputJSON)){
                http_response_code(400);
                throw new \Exception('Ошибка в теле запроса');
            }
            return $inputJSON;
        } else {
            http_response_code(400);
            throw new \Exception('Тип не поддерживается');
        }
    }
}