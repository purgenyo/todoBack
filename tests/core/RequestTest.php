<?php

class RequestTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider requestParamsProvider
     * @param $request_params
     */
    public function testRequestParser( $request_params ){
        $this->assertTrue(true);
    }

}