<?php

declare(strict_types=1);

namespace GildedRose;


class QualityIncreasingItem extends RegularItem
{
    public function updateItemQuality(): void
    {
        $changeValue = $this->defineChangeValue();

        $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
    }
}