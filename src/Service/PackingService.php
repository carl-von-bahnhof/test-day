<?php

namespace App\Service;

use App\Data\PackagingData;
use App\Data\ProductCollection;
use App\Entity\Packaging;
use App\Entity\PackagingUsage;
use App\Exception\PackagingNotFoundException;
use App\Http\Client;
use App\Http\PackingRequest;
use App\Http\PackingResponse;
use App\Mapper\CollectionToHashMapper;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Exception\BadResponseException;

class PackingService
{
    public function __construct(private EntityManager $entityManager, private Client $client, private PackageCalculationService $packageCalculationService)
    {
    }

    public function getPacking(ProductCollection $data): Packaging
    {
        $hashedProductString = CollectionToHashMapper::getHashFromCollection($data);

        $cachedPackaging = $this->entityManager->getRepository('App\Entity\PackagingUsage')->findOneBy(['fittingProductsHash' => $hashedProductString]);

        if ($cachedPackaging instanceof PackagingUsage) {
            return $this->entityManager->getRepository('App\Entity\Packaging')->find($cachedPackaging->getPackingId());
        }

        $existingPackages = $this->entityManager->getRepository('App\Entity\Packaging')->findAll();


        $usedPackaging = $this->findPackaging($data, $existingPackages);

        if ($usedPackaging === null) {
            throw new PackagingNotFoundException('We did not found recommended packaging.');
        }

        $this->cacheResult($usedPackaging->getId(), $hashedProductString);

        return $usedPackaging;


    }


    public function findPackaging(ProductCollection $data, array $existingPackages): ?Packaging
    {
        $request = new PackingRequest($data, $existingPackages);
        try {
            $response = $this->client->getPacking($request->getMappedArray());

            $packingData = (new PackingResponse($response))->map();

            $usedPackaging = null;
            /**
             * @var Packaging $packaging
             */
            foreach ($existingPackages as $packaging) {
                if ($packaging->getId() === $packingData->getUsedPackagingId()) {
                    $usedPackaging = $packaging;
                }
            }
        } catch (BadResponseException $exception) {
            $usedPackaging = $this->packageCalculationService->calculate($data, $existingPackages);
        }
        return $usedPackaging;
    }

    public function cacheResult(int $packagingId, string $hashedProductString): void
    {
        $packingUsage = new PackagingUsage($packagingId, $hashedProductString);

        $this->entityManager->persist($packingUsage);
        $this->entityManager->flush();
    }

}
