<?php

namespace App\Services;

use App\Exceptions\LinkException;
use App\Models\Link;
use Illuminate\Support\Facades\DB;

class LinkService
{
    /**
     * @var LimitsCalculator
     */
    private $limitsCalculator;

    public function __construct(LimitsCalculator $limitsCalculator)
    {
        $this->limitsCalculator = $limitsCalculator;
    }

    /**
     * @param Link $link
     */
    public function incrementTransitions(Link $link): void
    {
        $link->transitions_count += 1;
        $link->save();
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
            $minifiedLink = base_convert(array_shift($minifiedLink)->nextval, 10, LimitsCalculator::CHARS_AMOUNT);

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
        $availableLimits = $this->limitsCalculator->getAllAvailableLimits();

        foreach ($availableLimits as $length => $limit) {
            if ($limit > 0) {
                return $length;
            }
        }

        throw LinkException::limitExceeded();
    }
}
