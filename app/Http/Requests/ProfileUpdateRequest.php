<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre_usuario' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Usuario::class)->ignore($this->user()->id),  // Asegurando que el nombre de usuario sea único
            ],
            'nombre' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:50'],
            // Si tienes otros campos, agrégalos aquí
        ];
    }
}
