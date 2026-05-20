<?php

namespace Weblab\CURL;

use Weblab\CURL\Tests\TestCase;

/**
 * Class RequestTest
 * @author Weblab.nl - Eelco Verbeek
 */
class RequestTest extends TestCase {

    private $defaultSettings = [
        CURLOPT_DEFAULT_PROTOCOL => 'http',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_HEADER => true,
        CURLOPT_CONNECTTIMEOUT => 10
    ];

    public function testGet() {
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['run'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('run');

        $request->get('url', ['userId' => 6]);

        $expectedSettings = $this->defaultSettings;

        $expectedSettings[CURLOPT_HTTPGET]          = true;
        $expectedSettings[CURLOPT_CUSTOMREQUEST]    = 'GET';
        $expectedSettings[CURLOPT_URL]              = 'url?userId=6';

        $this->assertEquals($expectedSettings, $this->getSettings($request));
    }

    public function testPost() {
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['run'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('run');

        $request->post('url', ['userId' => 6]);

        $expectedSettings = $this->defaultSettings;
        $expectedSettings[CURLOPT_POST]             = true;
        $expectedSettings[CURLOPT_CUSTOMREQUEST]    = 'POST';
        $expectedSettings[CURLOPT_URL]              = 'url';
        $expectedSettings[CURLOPT_POSTFIELDS]       = 'userId=6';

        $this->assertEquals($expectedSettings, $this->getSettings($request));
    }

    public function testPut() {
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['run'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('run');

        $request->put('url', ['userId' => 6]);

        $expectedSettings = $this->defaultSettings;
        $expectedSettings[CURLOPT_POST]             = true;
        $expectedSettings[CURLOPT_CUSTOMREQUEST]    = 'PUT';
        $expectedSettings[CURLOPT_URL]              = 'url';
        $expectedSettings[CURLOPT_POSTFIELDS]       = 'userId=6';

        $this->assertEquals($expectedSettings, $this->getSettings($request));
    }

    public function testPatch() {
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['run'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('run');

        $request->patch('url', ['userId' => 6]);

        $expectedSettings = $this->defaultSettings;
        $expectedSettings[CURLOPT_POST]             = true;
        $expectedSettings[CURLOPT_CUSTOMREQUEST]    = 'PATCH';
        $expectedSettings[CURLOPT_URL]              = 'url';
        $expectedSettings[CURLOPT_POSTFIELDS]       = 'userId=6';

        $this->assertEquals($expectedSettings, $this->getSettings($request));
    }

    public function testDelete() {
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['run'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('run');

        $request->delete('url', ['userId' => 6]);

        $expectedSettings = $this->defaultSettings;
        $expectedSettings[CURLOPT_CUSTOMREQUEST]    = 'DELETE';
        $expectedSettings[CURLOPT_URL]              = 'url?userId=6';

        $this->assertEquals($expectedSettings, $this->getSettings($request));
    }

    private function getSettings(Request $request): array {
        $property = new \ReflectionProperty($request, 'settings');
        $property->setAccessible(true);

        return $property->getValue($request);
    }

}
