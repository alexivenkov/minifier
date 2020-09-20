<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\DB;

class LinkService
{
    private const CHARS_AMOUNT = 36;

    /**
     * @param Link $link
     */
    public function incrementTransitions(Link $link): void
    {
        $link->transitions_count += 1;
        $link->save();
    }

    /**
     * @param int $length
     *
     * @return int
     */
    public function getAvailableLimit(int $length): int
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
            $limits[$length] = $this->getAvailableLimit($length);
        }

        return $limits;
    }

    /**
     * @param string $originalLink
     *
     * @return Link
     * @throws \Exception
     */
    public function generateLink(string $originalLink): Link
    {
        if ($link = Link::where('original', $originalLink)->first()) {
            return $link;
        }

        $length = $this->getAvailableLength();

        try {
            DB::beginTransaction();

            $minifiedLink = DB::select("select nextval('minified_sequence')");
            $minifiedLink = base_convert(array_shift($minifiedLink)->nextval, 10, self::CHARS_AMOUNT);

            if (strlen($minifiedLink) < 3) {
                $minifiedLink = str_pad($minifiedLink, 3, 0, STR_PAD_LEFT);
            }

            $link = Link::create([
                'original'          => $originalLink,
                'minified'          => $minifiedLink,
                'transitions_count' => 0,
                'length'            => $length
            ]);

            DB::commit();

            return $link;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function getAvailableLength(): int
    {
        $availableLimits = $this->getAllAvailableLimits();

        foreach ($availableLimits as $length => $limit) {
            if ($limit > 0) {
                return $length;
            }
        }

        throw new \Exception('Links generation limit has been reached');
    }
}
