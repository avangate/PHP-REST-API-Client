<?php
namespace tests;

use AvangateClient\Client;
use GuzzleHttp\Exception\ClientException;

class ConnectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (MERCHANT_CODE === '*****') {
            $message = 'Set valid credentials (MERCHANT_CODE / MERCHANT_APIKEY) in phpunit.xml file.';
            static::fail($message . ' Check https://secure.avangate.com/cpanel/account_settings.php for details.');
        }
    }

    /**
     * When invalid credentials are given then throw exception.
     */
    public function testWhenInvalidCredentialsAreGivenThenThrowException()
    {
        $client = new Client([
            'code' => 'INVALID-MERCHANT-CODE',
            'key' => 'INVALID-MERCHANT-APIKEY',
            'base_uri' => 'https://api.avangate.com/3.0/'
        ]);

        try {
            $client->get('currencies/');
            static::fail('When you see it, an expected exception was not thrown.');

        } catch (ClientException $e) {
            static::assertEquals(401, $e->getResponse()->getStatusCode());
            $sentDetails = json_decode($e->getResponse()->getBody()->getContents());

            static::assertEquals('AUTHENTICATION_ERROR', $sentDetails->error_code);
            static::assertEquals('Authentication needed for this resource', $sentDetails->message);
        }
    }

    /**
     * When valid credentials are set then return valid details.
     */
    public function testWhenValidCredentialsAreSetThenReturnValidDetails()
    {
        $client = new Client([
            'code' => MERCHANT_CODE,
            'key' => MERCHANT_APIKEY,
            'base_uri' => 'https://api.avangate.com/3.0/'
        ]);

        $allowedCurrencies = json_decode($client->get('currencies/')->getBody()->getContents(), true);

        static::assertTrue(is_array($allowedCurrencies));
        static::assertTrue(count($allowedCurrencies) > 0);

        foreach ($allowedCurrencies as $currency) {
            static::assertEquals(strtolower($currency), $currency);
            static::assertEquals(3, strlen($currency));
        }
    }
}
