<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AtletaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->criadoEm);
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'dtNascimento' => $this->dtNascimento->format('Y-m-d'),
            'created_at' => $this->criadoEm,
        ];        
    }
}
