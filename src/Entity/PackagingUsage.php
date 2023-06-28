<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PackagingUsage
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    //TODO should be foreign key to Packaging

    #[ORM\Column(type: Types::INTEGER)]
    private int $packingId;

    #[ORM\Column(type: Types::STRING)]
    private string $fittingProductsHash;

    public function __construct(int $packingId, string $fittingProductsHash)
    {
        $this->packingId = $packingId;
        $this->fittingProductsHash = $fittingProductsHash;
    }

    public function getPackingId(): int
    {
        return $this->packingId;
    }

    public function getFittingProductsHash(): string
    {
        return $this->fittingProductsHash;
    }


}
