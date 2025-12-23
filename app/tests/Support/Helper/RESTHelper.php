<?php

namespace App\Tests\Support\Helper;

use Codeception\Module\REST;

use function json_decode;

class RESTHelper extends Rest
{
    public function assertJsonResponse(array $expectedResponse)
    {
        $actualResponse = json_decode($this->grabResponse(), true);

        $this->assertIsArray($actualResponse, 'The Response is not in JSON format');

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
