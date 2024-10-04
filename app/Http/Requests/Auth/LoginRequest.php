<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nombre_usuario' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function authenticate()
    {
        $credentials = $this->only('nombre_usuario', 'password');

        if (! auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'nombre_usuario' => __('auth.failed'),
            ]);
        }
    }
}
