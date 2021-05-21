<?php

declare(strict_types=1);

namespace GildedRose;

class BackstagePassItem extends QualityIncreasingItem
{
    const BACKSTAGEPASS_SELL_IN_APPROACHING = 10;
    const BACKSTAGEPASS_SELL_IN_LAST_MINUTE = 5;
    const BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE = 3;

    public function updateItemQuality(): void
    {
        // 0 or less = passed
        if ($this->sell_in <= self::SELL_IN_PASSED) {
            $this->quality = self::QUALITY_MIN;
            // 5 or less = last minute
        } else if ($this->sell_in <= self::BACKSTAGEPASS_SELL_IN_LAST_MINUTE) {
            $changeValue = self::BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE;
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
            // 10 or less = approaching
        } else if ($this->sell_in <= self::BACKSTAGEPASS_SELL_IN_APPROACHING) {
            $changeValue = self::QUALITY_CHANGE_DOUBLE;
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
            // more than 10 : regular quality
        } else {
            $changeValue = $this->defineChangeValue();
            $this->quality <= self::QUALITY_MAX - $changeValue ? $this->quality = $this->quality + $changeValue : $this->quality = self::QUALITY_MAX;
        }
    }
}