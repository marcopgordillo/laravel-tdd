<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sends_user_token_on_correct_credentials()
    {
        $user = User::factory()->create();

        $this->json('POST', route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(200)
          ->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('token')
                 ->where('user_name', $user->name)
                 ->etc();
          });
    }

    public function test_validates_wrong_password()
    {
        $user = User::factory()->create();

        $this->json('POST', route('user.login'), [
            'email' => $user->email,
            'password' => 'random',
        ])->assertStatus(422)
          ->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('errors')
                 ->where('errors.email.0', __('auth.wrong_password'))
                 ->etc();
          });
    }
}
