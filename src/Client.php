<?php
namespace AvangateClient;

class Client extends \GuzzleHttp\Client
{
    public function __construct(array $setup)
    {
        $date = date('Y-m-d H:i:s');
        $accept = (array_key_exists('headers.accept', $setup) ? $setup['headers.accept'] : 'application/json');

        $code = $setup['code'];
        $key = $setup['key'];
        $hash = hash_hmac('md5', strlen($code) . $code . strlen($date) . $date, $key);

        $headers = [
            'X-Avangate-Authentication' => 'code="' . $code . '" date="' . $date . '" hash="' . $hash . '"',
            'Accept' => $accept
        ];

        $setup['headers'] = array_key_exists('headers', $setup) ? array_merge($setup['headers'], $headers) : $headers;
        unset($setup['code'], $setup['key'], $setup['version']);

        parent::__construct($setup);
    }
}
