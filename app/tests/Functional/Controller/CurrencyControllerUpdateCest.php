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

    public function testFailWrongParametrNumCode()
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
        $data = [
            'numCode' => '',
            'charCode' => 'e1231',
            'name' => 'super-euro'
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function testFailWrongParametrCharCode()
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
        $data = [
            'numCode' => '123',
            'charCode' => '',
            'name' => 'super-euro'
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function testFailWrongParametrName()
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
        $data = [
            'numCode' => '123',
            'charCode' => 'e1231',
            'name' => ''
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function testFailNotUniqueNumCode()
    {
        // arrange
        $this->actor->haveInRepository(
            Currency::class,
            [
                'numCode' => 123,
                'charCode' => 'e1231',
                'name' => 'euro'
            ]
        );
        $currencyId = $this->actor->haveInRepository(
            Currency::class,
            [
                'numCode' => 923,
                'charCode' => 'e9231',
                'name' => 'euro'
            ]
        );
        $data = [
            'numCode' => '123',
            'charCode' => 'e9231',
            'name' => 'super-euro'
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR);
    }

    public function testFailNotUniqueCharCode()
    {
        // arrange
        $this->actor->haveInRepository(
            Currency::class,
            [
                'numCode' => 123,
                'charCode' => 'e1231',
                'name' => 'euro'
            ]
        );
        $currencyId = $this->actor->haveInRepository(
            Currency::class,
            [
                'numCode' => 923,
                'charCode' => 'e9231',
                'name' => 'euro'
            ]
        );
        $data = [
            'numCode' => '923',
            'charCode' => 'e1231',
            'name' => 'super-euro'
        ];

        // act
        $data = $this->sendRequest(
            placeholders: ['{id}' => $currencyId],
            bodyParameters: $data
        );

        // assert
        $this->actor->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR);
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
