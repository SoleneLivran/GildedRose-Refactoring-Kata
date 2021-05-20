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
    const QUALITY_MIN = 0;
    const QUALITY_MAX = 50;
    const SELL_IN_PASSED = 0;
    const QUALITY_CHANGE_REGULAR = 1;
    const QUALITY_CHANGE_DOUBLE = self::QUALITY_CHANGE_REGULAR * 2;
    const QUALITY_CHANGE_QUADRUPLE = self::QUALITY_CHANGE_DOUBLE * 2;
    const BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE = 3;



    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name == self::ITEM_NAME_SULFURAS) {
                continue;
            }
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

    private function increasableQualityItemUpdate(Item $item): void
    {
        if ($item->quality < self::QUALITY_MAX) { // if quality is allowed to go up
            if ($item->name == self::ITEM_NAME_BACKSTAGEPASS) {
                // specific logic for "backstage passes"
                $this->backstagePassUpdate($item);
            } else {
                $item->quality = $item->quality + self::QUALITY_CHANGE_REGULAR;
            }
        }
    }

    private function regularItemUpdate(Item $item): void
    {
        if ($item->sell_in > self::SELL_IN_PASSED) { // before sell date is passed
            $this->decreaseQuality($item, self::QUALITY_CHANGE_REGULAR);
        } else { // after sell date is passed
            $this->decreaseQuality($item, self::QUALITY_CHANGE_DOUBLE);
        }
    }

    private function backstagePassUpdate(Item $item): void
    {
        if ($item->sell_in <= self::SELL_IN_PASSED) {
            $item->quality = self::QUALITY_MIN;
        } else if ($item->sell_in < 6) {
            $item->quality <= self::QUALITY_MAX - self::BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE ? $item->quality = $item->quality + self::BACKSTAGEPASS_QUALITY_LAST_MINUTE_INCREASE : $item->quality = self::QUALITY_MAX;
        } else if ($item->sell_in < 11) {
            $item->quality <= self::QUALITY_MAX - self::QUALITY_CHANGE_DOUBLE ? $item->quality = $item->quality + self::QUALITY_CHANGE_DOUBLE : $item->quality = self::QUALITY_MAX;
        } else {
            $item->quality = $item->quality + self::QUALITY_CHANGE_REGULAR;
        }
    }

    private function conjuredItemUpdate(Item $item): void
    {
        if ($item->sell_in > self::SELL_IN_PASSED) { // before sell date is passed
            $this->decreaseQuality($item, self::QUALITY_CHANGE_DOUBLE);
        } else { // after sell date is passed
            $this->decreaseQuality($item, self::QUALITY_CHANGE_QUADRUPLE);
        }
    }

    private function decreaseItemSellIn(Item $item): void
    {
        $item->sell_in = $item->sell_in - 1;
    }

    private function increaseQuality(Item $item, int $changeValue): void
    {
    }

    private function decreaseQuality(Item $item, int $changeValue): void
    {
        $item->quality >= $changeValue ? $item->quality = $item->quality - $changeValue : $item->quality = self::QUALITY_MIN;
    }
}
