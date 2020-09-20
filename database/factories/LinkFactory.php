<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;


    /**
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        $minifiedLink = substr($this->faker->unique()->text(10), 0, random_int(3,6));

        return [
            'original'          => $this->faker->unique()->url,
            'minified'          => strtolower($minifiedLink),
            'transitions_count' => 0,
            'length'            => strlen($minifiedLink)
        ];
    }
}
