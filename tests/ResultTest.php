<?php

namespace Weblab\CURL;

use PHPUnit\Framework\Attributes\DataProvider;
use Weblab\CURL\Tests\TestCase;

/**
 * Class ResultTest
 * @author Weblab.nl - Eelco Verbeek
 */
class ResultTest extends TestCase {

    #[DataProvider('resultData')]
    public function testResult($body, $status, $headers) {
        $result = new Result($body, $status, $headers);

        $this->assertEquals(json_decode($body), $result->getResult());
        $this->assertEquals(200, $result->getStatus());
        $this->assertEquals('test', $result->getHeader('random-header'));
    }

    public static function resultData() {
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
