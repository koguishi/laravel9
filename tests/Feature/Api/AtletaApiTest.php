<?php

namespace Tests\Feature\Api;

use App\Models\Atleta;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class AtletaApiTest extends TestCase
{
    protected $endpoint = '/api/atletas';

    public function test_list_empty_atletas()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_atletas()
    {
        Atleta::factory()->count(30)->create();

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

    // public function test_paginated_atletas()
    // {
    //     Atleta::factory()->count(25)->create();

    //     $response = $this->getJson("$this->endpoint?page=2");

    //     $response->assertStatus(200);
    //     $this->assertEquals(2, $response['meta']['current_page']);
    //     $this->assertEquals(25, $response['meta']['total']);
    //     $response->assertJsonCount(10, 'data');

    //     $arrContent = json_decode($response->getContent());
    //     $this->assertCount(10, $arrContent->data);

    //     $this->assertEquals(25, $arrContent->meta->total);
    //     $this->assertEquals(2, $arrContent->meta->current_page);
    //     $this->assertEquals(2, $arrContent->meta->last_page);
    //     $this->assertEquals(16, $arrContent->meta->first_page);
    //     $this->assertEquals(15, $arrContent->meta->per_page);
    //     $this->assertEquals(16, $arrContent->meta->to);
    //     $this->assertEquals(25, $arrContent->meta->from);
    // }

    public function test_http_not_found()
    {
        $response = $this->getJson("$this->endpoint/fake_value");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_read_atleta()
    {
        $atleta = Atleta::factory()->create();

        $response = $this->getJson("$this->endpoint/{$atleta->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'dtNascimento',
                'created_at',
            ],
        ]);
        $this->assertEquals($atleta->id, $response['data']['id']);
    }

    // public function test_create_validate_nome_required()
    // {
    //     $data = [];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'nome',
    //         ],
    //     ]);
    // }

    // public function test_create_validate_nome_min()
    // {
    //     $data = [
    //         'nome' => 'AB',
    //     ];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'nome',
    //         ],
    //     ]);
    // }


    // public function test_create_validate_nome_max()
    // {
    //     $data = [
    //         'nome' => Str::random(101),
    //     ];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'nome',
    //         ],
    //     ]);
    // }


    // public function test_create_validate_description_min()
    // {
    //     $data = [
    //         'nome' => 'valid name',
    //         'descricao' => 'AB',
    //     ];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'descricao',
    //         ],
    //     ]);
    // }

    // public function test_create_validate_description_max()
    // {
    //     $data = [
    //         'nome' => 'valid name',
    //         'descricao' => Str::random(256),
    //     ];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'descricao',
    //         ],
    //     ]);
    // }

    // public function test_create_validate_ativo()
    // {
    //     $data = [
    //         'nome' => 'valid name',
    //         'ativo' => 'string'
    //     ];

    //     $response = $this->postJson($this->endpoint, $data);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'ativo',
    //         ],
    //     ]);
    // }

    public function test_create()
    {
        $data = [
            'nome' => 'Novo Atleta',
            'dtNascimento' => '2001-11-12',
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'dtNascimento',
                'created_at',
            ],
        ]);
        $this->assertEquals($data['nome'], $response['data']['nome']);
        $this->assertEquals($data['dtNascimento'], $response['data']['dtNascimento']);
        $this->assertDatabaseHas('atletas', [
            'id' => $response['data']['id'],
        ]);
    }

    // public function test_not_found_update()
    // {
    //     $data = [
    //         'nome' => 'New name',
    //     ];

    //     $response = $this->putJson("{$this->endpoint}/fake_id", $data);

    //     $response->assertStatus(Response::HTTP_NOT_FOUND);
    // }

    // public function test_validations_update()
    // {
    //     $atleta = Atleta::factory()->create();

    //     $response = $this->putJson("{$this->endpoint}/{$atleta->id}", []);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'nome',
    //         ],
    //     ]);
    // }

    // public function test_update()
    // {
    //     $atleta = Atleta::factory()->create();

    //     $data = [
    //         'nome' => 'Name Updated',
    //     ];

    //     $response = $this->putJson("{$this->endpoint}/{$atleta->id}", $data);

    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertJsonStructure([
    //         'data' => [
    //             'id',
    //             'nome',
    //             'descricao',
    //             'ativo',
    //             // 'created_at',
    //         ],
    //     ]);
    //     $this->assertDatabaseHas('atletas', [
    //         'nome' => 'Name Updated',
    //     ]);
    // }

    // public function test_not_found_delete()
    // {
    //     $response = $this->deleteJson("{$this->endpoint}/fake_id");

    //     $response->assertStatus(Response::HTTP_NOT_FOUND);
    // }

    // public function test_delete()
    // {
    //     $atleta = Atleta::factory()->create();

    //     $response = $this->deleteJson("{$this->endpoint}/{$atleta->id}");

    //     $response->assertStatus(Response::HTTP_NO_CONTENT);
    //     $this->assertSoftDeleted('atletas', [
    //         'id' => $atleta->id,
    //     ]);
    // }
}
