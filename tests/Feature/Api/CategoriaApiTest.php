<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoriaApiTest extends TestCase
{
    protected $endpoint = '/api/categorias';

    public function test_list_empty_categorias()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }    

    public function test_list_categorias()
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

        $this->assertEquals(30, $arrContent->meta->total);
        $this->assertEquals(1, $arrContent->meta->current_page);
        $this->assertEquals(2, $arrContent->meta->last_page);
        $this->assertEquals(1, $arrContent->meta->first_page);
        $this->assertEquals(15, $arrContent->meta->per_page);
        $this->assertEquals(1, $arrContent->meta->to);
        $this->assertEquals(15, $arrContent->meta->from);
    }

    public function test_paginated_categorias()
    {
        Categoria::factory()->count(25)->create();

        $response = $this->getJson("$this->endpoint?page=2");

        $response->assertStatus(200);
        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(25, $response['meta']['total']);
        $response->assertJsonCount(10, 'data');

        $arrContent = json_decode($response->getContent());
        $this->assertCount(10, $arrContent->data);

        $this->assertEquals(25, $arrContent->meta->total);
        $this->assertEquals(2, $arrContent->meta->current_page);
        $this->assertEquals(2, $arrContent->meta->last_page);
        $this->assertEquals(16, $arrContent->meta->first_page);
        $this->assertEquals(15, $arrContent->meta->per_page);
        $this->assertEquals(16, $arrContent->meta->to);
        $this->assertEquals(25, $arrContent->meta->from);
    }

    public function test_http_not_found()
    {
        $response = $this->getJson("$this->endpoint/fake_value");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_read_categoria()
    {
        $category = Categoria::factory()->create();

        $response = $this->getJson("$this->endpoint/{$category->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'descricao',
                'ativo',
                // 'created_at',
            ],
        ]);
        $this->assertEquals($category->id, $response['data']['id']);
    }

    public function test_create_validate_nome_required()
    {
        $data = [];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'nome',
            ],
        ]);
    }

    public function test_create_validate_nome_min()
    {
        $data = [
            'nome' => 'AB',
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'nome',
            ],
        ]);
    }


    public function test_create_validate_nome_max()
    {
        $data = [
            'nome' => str_repeat('Abcde', 10) . ' ' . str_repeat('Fghij', 10),
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'nome',
            ],
        ]);
    }


    public function test_create_validate_description_min()
    {
        $data = [
            'nome' => 'valid name',
            'descricao' => 'AB',
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'descricao',
            ],
        ]);
    }

    public function test_create_validate_description_max()
    {
        $data = [
            'nome' => 'valid name',
            'descricao' =>
                str_repeat('Abcde', 10) . ' ' . // 51
                str_repeat('Qwert', 20) . ' ' . // 101
                str_repeat('Zxcvb', 20) . ' ' . // 101 => 253
                '456'
            ,
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'descricao',
            ],
        ]);

        dump($response['errors']);
    }



    public function test_create()
    {
        $data = [
            'nome' => 'New Category',
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'descricao',
                'ativo',
                // 'created_at',
            ],
        ]);

        $response = $this->postJson($this->endpoint, [
            'nome' => 'New Cat',
            'descricao' => 'new desc',
            'ativo' => false,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('New Cat', $response['data']['nome']);
        $this->assertEquals('new desc', $response['data']['descricao']);
        $this->assertEquals(false, $response['data']['ativo']);
        $this->assertDatabaseHas('categorias', [
            'id' => $response['data']['id'],
            'ativo' => false,
        ]);
    }

    public function test_not_found_update()
    {
        $data = [
            'nome' => 'New name',
        ];

        $response = $this->putJson("{$this->endpoint}/fake_id", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_validations_update()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$categoria->id}", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'nome',
            ],
        ]);
    }

    public function test_update()
    {
        $categoria = Categoria::factory()->create();

        $data = [
            'nome' => 'Name Updated',
        ];

        $response = $this->putJson("{$this->endpoint}/{$categoria->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'descricao',
                'ativo',
                // 'created_at',
            ],
        ]);
        $this->assertDatabaseHas('categorias', [
            'nome' => 'Name Updated',
        ]);
    }

    public function test_not_found_delete()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$categoria->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categorias', [
            'id' => $categoria->id,
        ]);
    }
}
