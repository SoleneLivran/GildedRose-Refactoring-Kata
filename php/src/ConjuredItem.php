<?php

declare(strict_types=1);

namespace GildedRose;

class ConjuredItem extends QualityDecreasingItem
{
    public function decreaseItemQuality(int $changeValue): void
    {
        $changeValue = $changeValue * 2;
        // urgh

        if ($this->sell_in <= self::SELL_IN_PASSED) {
            $changeValue = $changeValue * self::QUALITY_CHANGE_AFTER_SELL_IN_MODIFIER;
        }

        $this->quality >= $changeValue ? $this->quality = $this->quality - $changeValue : $this->quality = self::QUALITY_MIN;
    }
}