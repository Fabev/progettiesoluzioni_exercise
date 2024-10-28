<?php

namespace App\Http\Requests;

use App\Enumerations\FamilyRoles;
use Illuminate\Foundation\Http\FormRequest;

class JoinFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'citizen_id' => 'required|exists:citizens,id',
            'role' => 'required|in:' . implode(',', array_column(FamilyRoles::cases(), 'value')),
        ];
    }
}
