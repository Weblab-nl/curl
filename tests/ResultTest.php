<?php

namespace Weblab\CURL;

use Weblab\CURL\Tests\TestCase;

/**
 * Class ResultTest
 * @author Weblab.nl - Eelco Verbeek
 */
class ResultTest extends TestCase {

    /**
     * @dataProvider resultData
     */
    public function testResult($body, $status, $headers) {
        $result = new Result($body, $status, $headers);

        $this->assertEquals(json_decode($body), $result->getResult());
        $this->assertEquals(200, $result->getStatus());
        $this->assertEquals('test', $result->getHeader('random-header'));
    }

    public function resultData() {
        return [
            [
                '{"user":"user2"}',
                200,
                'Content-Type: application/json' . PHP_EOL .
                'Random-Header: test'
            ]
        ];
    }

}