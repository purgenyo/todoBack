<?php

class RequestTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Типы данных
     *
     * @dataProvider requestTypeProvider
     * @param $content_type
     * @param $processor_type
     */
    public function testParseContentType( $content_type, $processor_type ){
        $parser = new \app\core\RequestParser($content_type, '{}');
        $pType = $parser->getProcessorType();
        $this->assertTrue($processor_type==$pType);
    }

    public function requestTypeProvider(){
        return [
            ['application/json', 'json'],
            ['test/request,application/json,image/jpg,test/', 'json'],
            ['image/jpeg', null],
        ];
    }

    /**
     * Типы данных
     *
     * @dataProvider requestBodyProvider
     * @param $body
     * @param $decoder_array
     */
    public function testBodyProcess( $body, $decoder_array ){
        $parser = new \app\core\RequestParser('application/json', $body);
        try{
            $result = $parser->process();
            $this->assertTrue($result===$decoder_array);
        } catch (Exception $e) {
            $res = ($e instanceof Exception) && $decoder_array==null;
            $this->assertTrue(($e instanceof Exception) && $decoder_array==null);
        }
    }

    public function requestBodyProvider(){
        return [
            ['{}', []],
            ['{"text":"test_text"}', ['text'=>'test_text']],
            ['{"test": {"test: test"}', null],
            ['{"test": {"test": "test"}}', ['test'=>['test'=>'test']]],
            ['{"":"", "":""}', [''=>'']],
            ['{"":"", "":""', null],
            ['test', null],
        ];
    }
}