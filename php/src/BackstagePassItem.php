<?php

declare(strict_types=1);

namespace GildedRose;

class BackstagePassItem extends QualityIncreasingItem
{
    const BACKSTAGEPASS_SELL_IN_APPROACHING = 10;
    const BACKSTAGEPASS_SELL_IN_LAST_MINUTE = 5;
    const BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE = 3;

    public function increaseItemQuality(int $changeValue): void
    {
        if ($this->sell_in <= self::SELL_IN_PASSED) {
            $this->quality = self::QUALITY_MIN;
        } else if ($this->sell_in <= self::BACKSTAGEPASS_SELL_IN_LAST_MINUTE) {
            // urgh
            $changeValue = self::BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE;
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
        } else if ($this->sell_in <= self::BACKSTAGEPASS_SELL_IN_APPROACHING) {
            // urgh
            $changeValue = 2;
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
        } else {
            // urgh
            $changeValue = 1;
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
        }
    }

}