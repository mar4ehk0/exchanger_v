<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Currency;
use App\Tests\Functional\AbstractEndpointClass;
use App\Tests\Functional\Endpoint;
use Codeception\Util\HttpCode;

use function dd;

class CurrencyControllerUpdateCest extends AbstractEndpointClass
{
    protected function getEndpoint(): Endpoint
    {
        return new Endpoint('PUT', '/currencies/{id}');
    }

    public function testCan()
    {
        // arrange
        $currencyId = $this->actor->haveInRepository(
            Currency::class,
            [
                'id' => '1000',
                'numCode' => 123,
                'charCode' => 'e1231',
                'name' => 'euro'
            ]
        );
        $data = [
            'numCode' => '123',
            'charCode' => 'e1231',
            'name' => 'super-euro'
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::OK);
        $this->actor->assertEquals('123', $data['num_code']);
        $this->actor->assertEquals('e1231', $data['char_code']);
        $this->actor->assertEquals('super-euro', $data['name']);
        $this->actor->assertEquals($currencyId, $data['id']);
    }



}
