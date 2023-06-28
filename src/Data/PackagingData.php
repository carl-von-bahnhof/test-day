<?php

namespace App\Data;

class PackagingData
{
    public function __construct(private int $usedPackagingId)
    {
    }

    public function getUsedPackagingId(): int
    {
        return $this->usedPackagingId;
    }
}
