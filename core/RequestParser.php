<?php
/**
 * Created by PhpStorm.
 * User: purgen
 * Date: 13.09.17
 * Time: 21:15
 */

namespace app\core;

/**
 * Парсер запроса
 *
 * Class RequestParser
 * @package app\core
 */
class RequestParser
{
    private $_processor = null;
    private $_body = null;

    function __construct( $content_type, $body )
    {
        $this->_processor = $this->_getProcessor($content_type);
        $this->_body = $body;
    }

    public function process(){
        if($this->getProcessorType()==='json'){
            return $this->processJson($this->_getBody());
        }
        throw new \Exception('Формат данных не поддерживается');
    }

    private function processJson( $bodyJSON ){
        $bodyJSON = json_decode($bodyJSON, true);
        if($bodyJSON===null){
            http_response_code(400);
            throw new \Exception('Ошибка в теле запроса');
        }
        return $bodyJSON;
    }

    private function _getProcessor($content_type){
        if(empty($this->_processor)){
            if(preg_match('/(application\/json)/', $content_type)!=0){
                $this->_processor = 'json';
            }
        }

        return $this->_processor;
    }

    private function _getBody(){
        return $this->_body;
    }

    public function getProcessorType(){
        return $this->_processor;
    }
}