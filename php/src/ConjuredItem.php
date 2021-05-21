<?php

declare(strict_types=1);

namespace GildedRose;

class ConjuredItem extends RegularItem
{
    protected function defineChangeValue(): int
    {
        return self::QUALITY_CHANGE_DOUBLE;
    }
}