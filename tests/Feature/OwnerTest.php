<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list_owner(): void
    {
        $response = $this->get('/owners');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_create_owner(): void
    {
        $data = [
            'name' =>  'Caio',
            'cpf' => '123.456.789-12',
            'age' => 22,
            'gender' =>  'masculino'
        ];

        $response = $this->post('/owners', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'id' => 1,
            'name' =>  'Caio',
            'cpf' => '123.456.789-12',
            'age' => 22,
            'gender' =>  'masculino'  
        ]);
    }
}
