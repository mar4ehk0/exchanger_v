<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Currency;
use App\Tests\Functional\AbstractEndpointClass;
use App\Tests\Functional\Endpoint;
use Codeception\Util\HttpCode;

use function dd;

class CurrencyControllerGetCest extends AbstractEndpointClass
{
    protected function getEndpoint(): Endpoint
    {
        return new Endpoint('GET', '/currencies/{id}');
    }

    public function testCan()
    {
        // arrange
        $currencyId = $this->actor->haveInRepository(
            Currency::class,
            [
                'numCode' => 123,
                'charCode' => 'e1231',
                'name' => 'euro'
            ]
        );

        // act
        $data = $this->sendRequest(placeholders: ['{id}' => $currencyId]);

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::OK);
        $this->actor->assertEquals('123', $data['numCode']);
        $this->actor->assertEquals('e1231', $data['charCode']);
        $this->actor->assertEquals('euro', $data['name']);
        $this->actor->assertEquals($currencyId, $data['id']);
    }
}
