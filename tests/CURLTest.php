<?php

namespace Weblab\CURL;

use Weblab\CURL\Tests\TestCase;

class CURLTest extends TestCase {

    public function testCallStatic() {
        $request = CURL::setURL('best.url.eu');

        $this->assertInstanceOf(Request::class, $request);
    }

}