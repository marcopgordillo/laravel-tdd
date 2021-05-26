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

    private string $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->url = 'https://youtube.com/watch?v=1sTux4ys3iE';
    }

    /** @test */
    public function it_creates_a_new_video(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST',route('videos.store'), [
            'url' => $this->url,
            'title' => 'test title',
        ])->assertStatus(201);

        $this->assertDatabaseHas('videos', [
            'url' => $this->url,
            'title' => 'test title',
            'description' => '',
        ]);
    }

    /** @test */
    public function it_returns_video_in_response(): void
    {
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $this->url,
            'title' => 'test title',
        ]);

        $resp->assertJson(function (AssertableJson $json) {
            $json->where('id', 1)
                ->where('url', $this->url)
                ->where('type', 'youtube')
                ->etc();
        });
    }

    /** @test */
    public function it_returns_an_unpublished_video(): void
    {
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $this->url,
            'title' => 'test title',
        ]);

        // dd(json_decode($resp->getContent(), true));

        $resp->assertJson(function (AssertableJson $json) {
            $json->where('is_published', 0)
                ->etc();
        });
    }

    /** @test */
    public function it_adds_description_if_sent(): void
    {
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('videos.store'), [
            'url' => $this->url,
            'title' => 'test title',
            'description' => 'test',
        ]);

        // dd(json_decode($resp->getContent(), true));

        $resp->assertJson(function (AssertableJson $json) {
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
        User::factory(5)->create();
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST',route('videos.store'), [
            'url' => $this->url,
            'title' => 'test title',
        ]);

        $this->assertDatabaseHas('videos', [
            'url' => $this->url,
            'title' => 'test title',
            'user_id' => $user->id,
        ]);

    }
}
