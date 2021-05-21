<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\BackstagePassItem;
use GildedRose\ConjuredItem;
use GildedRose\GildedRose;
use GildedRose\Item;
use GildedRose\LegendaryItem;
use GildedRose\QualityDecreasingItem;
use GildedRose\QualityIncreasingItem;
use GildedRose\RegularItem;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    const ITEM_NAME_BACKSTAGEPASS = 'Backstage passes to a TAFKAL80ETC concert';
    const ITEM_NAME_BRIE = 'Aged Brie';
    const ITEM_NAME_SULFURAS = 'Sulfuras, Hand of Ragnaros';
    const ITEM_REGULAR = 'foo';
    const ITEM_CONJURED = 'Conjured Mana Cake';

    public function testFoo(): void
    {
        // Setup
        $items = [new Item(self::ITEM_REGULAR, 0, 0)];
        $gildedRose = new GildedRose($items);

        // Run code
        $gildedRose->updateQuality();

        // Check
        $this->assertSame(self::ITEM_REGULAR, $items[0]->name);
    }

    // Regular items : quality and sell_in decrease by one
    public function testRegularItem(): void
    {
        $items = [new RegularItem(self::ITEM_REGULAR, 10, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(19, $items[0]->quality);
    }

    // Conjured items : quality decreases by 2 and sell_in decrase by one
    public function testConjuredItem(): void
    {
        $items = [new ConjuredItem(self::ITEM_CONJURED, 10, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(18, $items[0]->quality);
    }

    // Normal Item : quality decreases x2 after sell date passed
    public function testRegularItemAfterSellInPassed(): void
    {
        $items = [new RegularItem(self::ITEM_REGULAR, -1, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(-2, $items[0]->sell_in);
        $this->assertSame(18, $items[0]->quality);
    }

    // Conjured Item : quality decreases x4 after sell date passed
    public function testConjuredItemAfterSellInPassed(): void
    {
        $items = [new ConjuredItem(self::ITEM_CONJURED, -1, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(-2, $items[0]->sell_in);
        $this->assertSame(16, $items[0]->quality);
    }

    // Any Item with decreasing quality : quality cannot go below 0
    public function testQualityNotBelowZero(): void
    {
        $items = [
                    new RegularItem(self::ITEM_REGULAR, 10, 0),
                    new RegularItem(self::ITEM_REGULAR, -2, 0),
                    new RegularItem(self::ITEM_REGULAR, 0, 1),
                    new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 0, 0),
                    new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, -1, 0),
                    new LegendaryItem(self::ITEM_NAME_SULFURAS, 12, 0),
                    new ConjuredItem(self::ITEM_CONJURED, 10, 0),
                    new ConjuredItem(self::ITEM_CONJURED, 6, 1),
                    new ConjuredItem(self::ITEM_CONJURED, -1, 3)
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        foreach($items as $item) {
            $this->assertSame(0, $item->quality);
        }
    }

    // Items with increasing quality : quality cannot go over 50
    public function testQualityNotOver50(): void
    {
        $items = [
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 8, 50),
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 1, 48),
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 4, 49),
            new QualityIncreasingItem(self::ITEM_NAME_BRIE, 6, 50)
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        foreach($items as $item) {
            $this->assertSame(50, $item->quality);
        }
    }

    // Aged Brie Item : quality increases by 1 each day and sell-in decreases by 1
    public function testItemQualityIncreasesByOne(): void
    {
        $items = [new QualityIncreasingItem(self::ITEM_NAME_BRIE, 7, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(6, $items[0]->sell_in);
        $this->assertSame(21, $items[0]->quality);
    }

    // Legendary Item (Sulfuras) : no change in sell_in nor in quality
    public function testLegendaryItem(): void
    {
        $items = [new LegendaryItem(self::ITEM_NAME_SULFURAS, 10, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(10, $items[0]->sell_in);
        $this->assertSame(20, $items[0]->quality);
    }

    // Backstage pass, sell-in more than 10 days : quality increases by 1
    public function testBackstagePassMoreThanTenDays(): void
    {
        $items = [new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 11, 20)];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(10, $items[0]->sell_in);
        $this->assertSame(21, $items[0]->quality);
    }

    // Backstage pass, sell-in 10 or less : quality increases by 2
    public function testBackstagePassTenDaysOrLess(): void
    {
        $items = [
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 10, 20),
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 18, 12),
            ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(22, $items[0]->quality);
        $this->assertSame(17, $items[1]->sell_in);
        $this->assertSame(13, $items[1]->quality);
    }
    // Backstage pass, sell-in 5 days or less : quality increases by 3
    public function testBackstagePassFiveDaysOrLess(): void
    {
        $items = [
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 5, 20),
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 1, 12)
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(4, $items[0]->sell_in);
        $this->assertSame(23, $items[0]->quality);
        $this->assertSame(0, $items[1]->sell_in);
        $this->assertSame(15, $items[1]->quality);
    }
    // Backstage pass, sell-in has passed (0 or below) : quality is 0
    public function testBackstagePassSellDateHasPassed(): void
    {
        $items = [
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, 0, 20),
            new BackstagePassItem(self::ITEM_NAME_BACKSTAGEPASS, -1, 12)
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(-1, $items[0]->sell_in);
        $this->assertSame(0, $items[0]->quality);
        $this->assertSame(-2, $items[1]->sell_in);
        $this->assertSame(0, $items[1]->quality);
    }
}
