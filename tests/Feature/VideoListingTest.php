<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Video;
use App\Models\User;

class VideoListingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_shows_list_of_videos(): void
    {
        Video::factory()->count(5)->create();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('GET', route('videos.index'));

        // dd(json_decode($resp->getContent(), true));

        $resp->assertJson(function(AssertableJson $json) {
            $json->where('total', 5)
                 ->has('data', 5)
                ->etc();
        });
    }

    public function test_shows_first_n_videos(): void
    {
        Video::factory(12)->create();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('GET', route('videos.index'));

        $resp->assertJson(function(AssertableJson $json) {
            $json->where('total', 12)
                ->has('data', config('app.pagination'))
                ->has('data.0', function ($video) {
                    $video->where('is_published', '1')
                        ->etc();
                })
                ->etc();
        });
    }

    public function test_shows_only_published_videos(): void
    {
        Video::factory(2)->unPublished()->create();
        Video::factory(5)->create();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('GET', route('videos.index'));

        $resp->assertJson(function(AssertableJson $json) {
            $json->where('total', 5)
                ->has('data', 5)
                ->has('data.0', function ($video) {
                    $video->where('is_published', '1')
                        ->etc();
                })
                ->etc();
        });
    }

    public function test_does_not_allow_guests(): void
    {
        $this->json('GET', route('videos.index'))
             ->assertStatus(401);
    }
}
