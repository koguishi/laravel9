<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // public function toArray($request)
    // {
    //     return parent::toArray($request);
    // }
    public function toArray($request)
    {
        if (is_array($this->resource)) {
            return [
                'id' => $this['id'],
                'nome' => $this['nome'],
                'descricao' => $this['descricao'],
                'ativo' => $this['ativo'],
                // 'created_at' => Carbon::make($this->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            // 'created_at' => Carbon::make($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
