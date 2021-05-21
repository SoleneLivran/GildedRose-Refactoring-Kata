<?php

declare(strict_types=1);

namespace GildedRose;

class RegularItem extends Item
{
    const SELL_IN_DECREASE_REGULAR = 1;
    const QUALITY_CHANGE_REGULAR = 1;
    const QUALITY_CHANGE_DOUBLE = self::QUALITY_CHANGE_REGULAR * 2;
    const QUALITY_MIN = 0;
    const QUALITY_MAX = 50;
    // TODO : forbid creating an item with quality > 50 or < 1 (in constructor)
    const SELL_IN_PASSED = 0;
    const QUALITY_CHANGE_AFTER_SELL_IN_MODIFIER = 2;

    public function decreaseItemSellIn(Item $item): void
    {
        $item->sell_in = $item->sell_in - self::SELL_IN_DECREASE_REGULAR;
    }

    protected function defineChangeValue(): int
    {
        return self::QUALITY_CHANGE_REGULAR;
    }

    public function updateItemQuality(): void
    {
        $changeValue = $this->defineChangeValue();

        if ($this->sell_in <= self::SELL_IN_PASSED) {
            $changeValue = $changeValue * self::QUALITY_CHANGE_AFTER_SELL_IN_MODIFIER;
        }

        $this->quality >= $changeValue ? $this->quality = $this->quality - $changeValue : $this->quality = self::QUALITY_MIN;
    }
}