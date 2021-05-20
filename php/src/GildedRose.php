<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    const ITEM_NAME_BACKSTAGEPASS = 'Backstage passes to a TAFKAL80ETC concert';
    const ITEM_NAME_BRIE = 'Aged Brie';
    const ITEM_NAME_SULFURAS = 'Sulfuras, Hand of Ragnaros';
    const ITEM_CONJURED = 'Conjured Mana Cake';


    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name != self::ITEM_NAME_SULFURAS) {
                switch ($item->name) {
                    case self::ITEM_NAME_BRIE:
                    case self::ITEM_NAME_BACKSTAGEPASS:
                        $this->increasableQualityItemUpdate($item);
                        break;
                    case self::ITEM_CONJURED:
                        $this->conjuredItemUpdate($item);
                        break;
                    default:
                        $this->regularItemUpdate($item);
                }
                $this->decreaseItemSellIn($item);
            }
        }
    }

    private function increasableQualityItemUpdate(Item $item): void
    {
        if ($item->quality < 50) { // if quality is allowed to go up
            if ($item->name == self::ITEM_NAME_BACKSTAGEPASS) {
                // specific logic for "backstage passes"
                $this->backstagePassUpdate($item);
            } else {
                $item->quality = $item->quality + 1;
            }
        }
    }

    private function regularItemUpdate(Item $item): void
    {
        if ($item->sell_in > 0) { // before sell date is passed
            if ($item->quality >= 1) {
                $item->quality = $item->quality - 1;
            }
        } else { // after sell date is passed
            if ($item->quality >= 2) {
                $item->quality = $item->quality - 2;
            } else {
                $item->quality = 0;
            }
        }
    }

    private function backstagePassUpdate(Item $item): void
    {
        if ($item->sell_in <= 0) {
            $item->quality = 0;
        } else if ($item->sell_in < 6) {
            $item->quality <= 47 ? $item->quality = $item->quality + 3 : $item->quality = 50;
        } else if ($item->sell_in < 11) {
            $item->quality <= 48 ? $item->quality = $item->quality + 2 : $item->quality = 50;
        } else {
            $item->quality = $item->quality + 1;
        }
    }

    private function conjuredItemUpdate(Item $item): void
    {
        if ($item->sell_in > 0) { // before sell date is passed
            if ($item->quality >= 2) {
                $item->quality = $item->quality - 2;
            } else {
                $item->quality = 0;
            }
        } else { // after sell date is passed
            if ($item->quality >= 4) {
                $item->quality = $item->quality - 4;
            } else {
                $item->quality = 0;
            }
        }
    }

    private function decreaseItemSellIn(Item $item): void
    {
        $item->sell_in = $item->sell_in - 1;
    }
}
