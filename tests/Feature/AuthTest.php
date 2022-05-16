<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    
    /** @test */
    public function required_fields_for_registration()
    {
        $this->json('POST', 'api/create', ['Accept' => 'application/json'])
        ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    /** @test */
    public function repeat_password()
    {
        $data = [
            "name" => "Thanos",
            "email" => "thanos@marvel.com",
            "password" => "eusouinevitavel"
        ];

        $this->json('POST', 'api/create', $data, ['Accept' => 'application/json'])
        ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."]
                ]
            ]);
    }

    /** @test */
    public function successful_registration()
    {
        $data = [
            "name" => "Thanos",
            "email" => "thanos@marvel.com",
            "password" => "eusouinevitavel",
            "password_confirmation" => "eusouinevitavel",
            
            
        ];

        $this->json('POST', 'api/create', $data, ['Accept' => 'application/json'])
        ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                "access_token",
                "message"
            ]);
    }
}
