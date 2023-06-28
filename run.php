<?php

use App\Application;
use App\Http\Client;
use App\Mapper\ProductMapper;
use App\Service\PackingService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/** @var EntityManager $entityManager */
$entityManager = require __DIR__ . '/src/bootstrap.php';

$request = new Request('POST', new Uri('http://localhost/pack'), ['Content-Type' => 'application/json'], $argv[1]);

$client = new Client();
$calculationService = new \App\Service\PackageCalculationService();
$packingService = new PackingService($entityManager, $client, $calculationService);
$mapper = new ProductMapper();

$application = new Application($packingService, $mapper);
$response = $application->run($request);

echo "<<< In:\n" . Message::toString($request) . "\n\n";
echo ">>> Out:\n" . Message::toString($response) . "\n\n";
