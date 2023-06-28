<?php

namespace App;

use App\Exception\NoInputDataException;
use App\Mapper\ProductMapper;
use App\Service\PackingService;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Dotenv\Dotenv;

class Application
{

    public function __construct(private PackingService $packingService, private ProductMapper $productMapper)
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../.env');
    }

    public function run(RequestInterface $request): ResponseInterface
    {

        $productCollection = $this->productMapper->mapProductRequest($request);

        if ($productCollection->isEmpty()) {
            throw new NoInputDataException('No data provided');
        }

        try {
            $foundPackaging = $this->packingService->getPacking($productCollection);

            return new Response('200', [], json_encode(['package_to_use' => $foundPackaging->getId()]));
        } catch (\Exception $e) {
            return new Response('400', [], json_encode(['error' => $e->getMessage()]));
        }
    }

}
