<?php

declare(strict_types=1);

namespace GildedRose;


class QualityIncreasingItem extends PerishableItem
{
    const QUALITY_MAX = 50;

    public function increaseItemQuality(int $changeValue): void
    {
        $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
    }
}