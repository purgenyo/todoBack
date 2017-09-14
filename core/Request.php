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
        $bodyParser = new BodyParser($_SERVER['CONTENT_TYPE'], $input);
        return $bodyParser->process();
    }

    public static function getToken(){

        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return false;
    }

}