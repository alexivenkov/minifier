<?php

namespace App\Services;

use App\Models\Link;

class LimitsCalculator
{
    public const CHARS_AMOUNT = 36;

    /**
     * @param int $length
     *
     * @return int
     */
    private function calculate(int $length): int
    {
        $link = Link::where('length', $length)->orderBy('id', 'DESC')->first();
        $maxAmount = base_convert(str_pad('', $length, 'z'), self::CHARS_AMOUNT, 10);

        if (!$link) {
            return $maxAmount;
        }

        return $maxAmount - base_convert($link->minified, self::CHARS_AMOUNT, 10);
    }

    /**
     * @return array
     */
    public function getAllAvailableLimits(): array
    {
        $limits = [];

        for ($length = 3; $length <= 6; $length++) {
            $limits[$length] = $this->calculate($length);
        }

        return $limits;
    }
}
