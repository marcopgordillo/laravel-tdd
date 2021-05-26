<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_new_video(): void
    {
        $url = $this->faker->url();
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST',route('videos.store'), [
            'url' => $url,
            'title' => 'test title',
        ])->assertStatus(201);

        $this->assertDatabaseHas('videos', [
            'url' => $url,
            'title' => 'test title',
            'description' => '',
        ]);
    }

    /** @test */
    public function it_returns_video_in_response(): void
    {
        $url = $this->faker->url();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $url,
            'title' => 'test title',
        ]);

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('id', 1)
                ->where('url', $url)
                ->where('type', 'youtube')
                ->etc();
        });
    }

    /** @test */
    public function it_returns_an_unpublished_video(): void
    {
        $url = $this->faker->url();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $url,
            'title' => 'test title',
        ]);

        // dd(json_decode($resp->getContent(), true));

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('is_published', 0)
                ->etc();
        });
    }

    /** @test */
    public function it_adds_description_if_sent(): void
    {
        $url = $this->faker->url();
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $url,
            'title' => 'test title',
            'description' => 'test',
        ]);

        // dd(json_decode($resp->getContent(), true));

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('description', 'test')
                ->etc();
        });
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('videos.store'), [])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.url')
                    ->etc();
            });
    }

    public function test_adds_current_user_id_in_video(): void
    {
        $url = $this->faker->url;
        User::factory(5)->create();
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST',route('videos.store'), [
            'url' => $url,
            'title' => 'test title',
        ]);

        $this->assertDatabaseHas('videos', [
            'url' => $url,
            'title' => 'test title',
            'user_id' => $user->id,
        ]);

    }
}
