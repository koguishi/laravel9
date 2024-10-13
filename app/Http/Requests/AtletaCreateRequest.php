<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtletaCreateRequest extends FormRequest
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
        // forma de deixar o limite dinamico
        // $date100YearsAgo = now()->subYears(100)->format('Y-m-d');
        return [
            'nome' => [
                'required',
                'min:3',
                'max:100',
            ],
            'dtNascimento' => [
                'required',
                'date',
                // forma de deixar o limite dinamico
                // 'after_or_equal:' . $date100YearsAgo,
                'after_or_equal:1900-01-01',
                'before:today',
            ]
        ];
    }
}
