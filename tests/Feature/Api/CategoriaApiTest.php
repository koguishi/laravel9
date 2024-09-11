<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaApiTest extends TestCase
{
    protected $endpoint = '/api/categorias';

    public function test_list_all_categories()
    {
        Categoria::factory()->count(30)->create();

        $response = $this->getJson($this->endpoint);
       
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ],
        ]);
        $response->assertJsonCount(15, 'data');

        $arrContent = json_decode($response->getContent());
        $this->assertCount(15, $arrContent->data);

    }    
}
