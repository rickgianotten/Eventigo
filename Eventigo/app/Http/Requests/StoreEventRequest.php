<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::exists('categories', 'slug')],

            'short_description' => ['required', 'string', 'max:120'],
            'long_description' => ['nullable', 'string', 'max:500'],

            'location' => ['required', 'string'],
            'city' => ['required','string'],
            'street' => ['required', 'string'],

            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after:start_date'],

            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required', 'array:name,email,role'],
            'participants.*.name' => ['required', 'string', 'max:255'],
            'participants.*.email' => ['required', 'email', 'max:255'],
            'participants.*.role' => ['required', Rule::in(['artist','speaker','exhibitor','vendor' ])],

            'tickets' => ['nullable', 'array'],
            'tickets.*' => ['required', 'array:type,price,quantity,description'],
            'tickets.*.type' => ['required', Rule::in(['Regular','VIP'])],
            'tickets.*.price' => ['required', 'decimal:2', 'min:0'],
            'tickets.*.quantity' => ['required', 'integer', 'min:1'],
            'tickets.*.description' => ['nullable', 'string', 'max:120'],

            'free_event' => ['nullable', 'accepted'],

            'max_amount_of_visitors' => ['nullable', 'integer', 'min:1'],

            'image_upload' => ['nullable', 'image', 'required_without:event_image'],
            'event_image' => ['nullable', 'image', 'required_without:image_upload'],
        ];
    }
}
