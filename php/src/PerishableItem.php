<?php

declare(strict_types=1);

namespace GildedRose;

class PerishableItem extends Item
{
    const SELL_IN_DECREASE_REGULAR = 1;
    public const QUALITY_MIN = 0;
    public const SELL_IN_PASSED = 0;

    public function decreaseItemSellIn(Item $item): void
    {
        $item->sell_in = $item->sell_in - self::SELL_IN_DECREASE_REGULAR;
    }

    public function updateItemQuality(int $changeValue): void
    {}
}