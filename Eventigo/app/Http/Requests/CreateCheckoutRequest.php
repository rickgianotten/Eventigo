<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckoutRequest extends FormRequest
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
            'tickets' => ['array', 'required'],
            'tickets.*.ticket_id' => ['integer', 'required','exists:tickets,id'],
            'tickets.*.ticket_quantity' => ['integer', 'required', 'min:0']
        ];
    }
}
