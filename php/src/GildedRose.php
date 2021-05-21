<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    const QUALITY_CHANGE_REGULAR = 1;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if (!$item instanceof PerishableItem) {
                continue;
            }
            if ($item instanceof QualityIncreasingItem) {
                $item->increaseItemQuality(self::QUALITY_CHANGE_REGULAR);
            }
            if ($item instanceof QualityDecreasingItem) {
                $item->decreaseItemQuality(self::QUALITY_CHANGE_REGULAR);
            }

            $item->decreaseItemSellIn($item);
        }
    }
}
