<?php

namespace App\Http;

use App\Data\ProductCollection;
use GuzzleHttp\Psr7\Response;
use http\Env;

class Client
{
    private const URL = 'https://global-api.3dbinpacking.com/';
    private const PACKING_PATH = 'packer/packIntoMany';
    private null|\GuzzleHttp\Client $client;
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getPacking(array $items): Response
    {

        $userName = $_ENV['USERNAME'];
        $token = $_ENV['TOKEN'];

        $data = [
            "username" => $userName,
            "api_key" => $token,
            ];

        $body = json_encode(array_merge($data, $items));

        $reponse =  $this->client->request('post', self::URL . self::PACKING_PATH, [
            'body' => $body
        ]);

        return $reponse;

    }


}
