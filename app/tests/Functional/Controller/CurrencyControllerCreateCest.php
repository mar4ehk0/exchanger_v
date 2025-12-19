<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Currency;
use App\Tests\Functional\AbstractEndpointClass;
use App\Tests\Functional\Endpoint;
use App\Tests\Support\FunctionalTester;
use Codeception\Example;
use Codeception\Util\HttpCode;

class CurrencyControllerCreateCest extends AbstractEndpointClass
{
    protected function getEndpoint(): Endpoint
    {
        return new Endpoint('POST', '/currencies');
    }

    /**
     * @dataProvider dataProviderFailWrongParametr
     */
    public function testFailWrongParametr(FunctionalTester $I, Example $example): void
    {
        // arrange
        $data = [
            'numCode' => $example['numCode'],
            'charCode' => $example['charCode'],
            'name' => $example['name'],
        ];

        // act
        $data = $this->sendRequest(bodyParameters: $data);

        // assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    private function dataProviderFailWrongParametr(): array
    {
        return [
            [
                'numCode' => '',
                'charCode' => 'e',
                'name' => 'euro',
            ],
            [
                'numCode' => '123',
                'charCode' => '',
                'name' => 'euro',
            ],
            [
                'numCode' => '123',
                'charCode' => 'e',
                'name' => '',
            ],
        ];
    }

    public function testFailNotUniqueNumCode(FunctionalTester $I)
    {
        // arrange
        $I->haveInRepository(
            Currency::class,
            [
                'numCode' => '123',
                'charCode' => 'e1231',
                'name' => 'euro'
            ]
        );
        $data = [
            'numCode' => '123',
            'charCode' => 'e',
            'name' => 'euro',
        ];

        // act
        $this->sendRequest(bodyParameters: $data);

        // assert
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR);
    }

    public function testCan(FunctionalTester $I)
    {
        // arrange
        $data = [
            'numCode' => '123',
            'charCode' => 'e',
            'name' => 'euro',
        ];

        // act
        $data = $this->sendRequest(bodyParameters: $data);

        // assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->assertEquals('euro', $data['name']);
        $I->assertEquals(123, $data['num_code']);
        $I->assertEquals('e', $data['char_code']);

        $I->seeInRepository(
            Currency::class,
            [
                'numCode' => '123',
                'charCode' => 'e',
                'name' => 'euro',
            ]
        );
    }



}
