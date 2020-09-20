<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Http\Response;
use Tests\TestCase;

class LinkMinifierTest extends TestCase
{
    /**
     * @test
     */
    public function it_successfully_redirect_via_existing_link(): void
    {
        $link = Link::factory()->create([
            'original' => 'http://example.com',
            'minified' => 'aaa'
        ]);

        $this->get(route('transition', [$link->minified]))
            ->assertStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->assertRedirect($link->original);

        $this->assertEquals(1, $link->refresh()->transitions_count);
    }

    /**
     * @test
     */
    public function it_successfully_generate_new_link(): void
    {
        $this->postJson(route('generate', [
            'link' => 'http://example.com'
        ]))
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'minified_link',
                    'original_link'
                ]
            ])
            ->assertJsonFragment([
                'original_link' => 'http://example.com'
            ]);
    }

    /**
     * @test
     */
    public function it_can_return_existed_link(): void
    {
        Link::factory()->create([
            'original' => 'http://example.com'
        ]);

        $this->postJson(route('generate', [
            'link' => 'http://example.com'
        ]))->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'minified_link',
                    'original_link'
                ]
            ])
            ->assertJsonFragment([
                'original_link' => 'http://example.com'
            ]);
    }

    /**
     * @dataProvider wrongParametersDataProvider
     *
     * @test
     *
     * @param array $request
     */
    public function it_cannot_generate_link_with_wrong_parameters(array $request): void
    {
        $this->postJson(route('generate', $request))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonFragment([
                'message' => 'The given data was invalid.'
            ]);
    }

    /**
     * @return array
     */
    public function wrongParametersDataProvider(): array
    {
        return [
            'missed link' => [
                []
            ],
            'invalid url' => [
                ['link' => 'invalid url']
            ]
        ];
    }

    /**
     * @test
     */
    public function it_can_retrieve_link_transitions_count(): void
    {
        Link::factory()->create([
            'minified'          => 'aaa',
            'transitions_count' => 321
        ]);

        $this->getJson(route('transitions', ['aaa']))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'transitions' => 321
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_get_available_links_limit(): void
    {
        // two links of each available length
        Link::factory()->create([
            'minified' => '000',
            'length'   => 3
        ]);
        Link::factory()->create([
            'minified' => '001',
            'length'   => 3
        ]);

        Link::factory()->create([
            'minified' => '0000',
            'length'   => 4
        ]);
        Link::factory()->create([
            'minified' => '0001',
            'length'   => 4
        ]);

        Link::factory()->create([
            'minified' => '00000',
            'length'   => 5
        ]);
        Link::factory()->create([
            'minified' => '00001',
            'length'   => 5
        ]);

        Link::factory()->create([
            'minified' => '000000',
            'length'   => 6
        ]);
        Link::factory()->create([
            'minified' => '000001',
            'length'   => 6
        ]);

        $this->getJson(route('limits'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    3 => (36 ** 3) - 2,
                    4 => (36 ** 4) - 2,
                    5 => (36 ** 5) - 2,
                    6 => (36 ** 6) - 2
                ]
            ]);
    }
}
