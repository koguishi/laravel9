<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtletaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => [
                'nullable',
            ],
            'dtNascimento' => [
                'nullable',
            ]
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(
            'nome',
            'min:3|max:100',
            function ($input) {
                return !empty($input->nome);
            }
        );
        $validator->sometimes(
            'dtNascimento',
            'date|after_or_equal:1900-01-01|before:today',
            function ($input) {
                return !empty($input->dtNascimento);
            }
        );
    }    
}
