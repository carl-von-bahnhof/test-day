<?php

namespace App\Http;

use App\Data\PackagingData;
use App\Entity\Packaging;
use App\Http\Exception\InvalidInputException;
use App\Http\Exception\InvalidResponseException;
use App\Http\Exception\NotPackedItemException;
use App\Http\Exception\TooManyPackagingsException;
use Psr\Http\Message\ResponseInterface;

class PackingResponse
{

    public function __construct(private ResponseInterface $response)
    {
    }

    public function map(): PackagingData
    {
        $decodedData = json_decode($this->response->getBody()->getContents(), true);

        if (!$decodedData || !$decodedData['response']) {
            throw new InvalidResponseException('Invalid response');
        }

        $this->handleErrors($decodedData['response']);

        $this->handleNotPackedItems($decodedData['response']);

        if ($decodedData['response']['bins_packed'] && count($decodedData['response']['bins_packed']) === 1) {
            $packaging = new PackagingData($decodedData['response']['bins_packed'][0]['bin_data']['id']);
            return $packaging;
        }

        throw new TooManyPackagingsException('There are too many available packaging options.');

    }


    public function handleErrors($response): void
    {
        if (array_key_exists('errors', $response) && count($response['errors']) > 0) {
            $errors = '';
            foreach ($response['errors'] as $error) {
                $errors .= $error['message'] . ', ';
            }
            throw new InvalidInputException('InvalidData:' . $errors);
        }
    }

    public function handleNotPackedItems($response): void
    {
        if (array_key_exists('not_packed_items', $response) && count($response['not_packed_items']) > 0) {
            throw new NotPackedItemException('There are item not packed!');
        }
    }

}
