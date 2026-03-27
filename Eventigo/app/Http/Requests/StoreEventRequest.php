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
        return[
            // 'action' => ['required', 'in:concept,publish'],

            'title'             => ['required_unless:action,concept', 'nullable', 'string', 'max:255'],
            'category'          => ['required_unless:action,concept', 'nullable', Rule::exists('categories', 'slug')],

            'short_description' => ['required_unless:action,concept', 'nullable', 'string', 'max:120'],
            'long_description'  => ['nullable', 'string', 'max:500'],

            'location'          => ['required_unless:action,concept', 'nullable', 'string'],
            'city'              => ['required_unless:action,concept', 'nullable', 'string'],
            'street'            => ['required_unless:action,concept', 'nullable', 'string'],

            'start_date'        => ['required_unless:action,concept', 'nullable', 'date', 'after:today'],
            'end_date'          => ['required_unless:action,concept', 'nullable', 'date', 'after:start_date'],

            'start_time'        => ['required_unless:action,concept', 'nullable', 'date_format:H:i'],
            'end_time'          => ['required_unless:action,concept', 'nullable', 'date_format:H:i'],

            'participants'      => ['required_unless:action,concept', 'nullable', 'array', 'min:1'],
            'participants.*'    => ['nullable', 'array:name,email,role'],
            'participants.*.name'  => ['required_unless:action,concept', 'nullable', 'string', 'max:255'],
            'participants.*.email' => ['required_unless:action,concept', 'nullable', 'email', 'max:255'],
            'participants.*.role'  => ['required_unless:action,concept', 'nullable', Rule::in(['artist', 'speaker', 'exhibitor', 'vendor'])],

            'tickets'           => ['required_unless:free_event,true,1,yes,on', 'nullable', 'array', 'min:1'],
            'tickets.*'         => ['nullable', 'array:type,price,quantity,description'],
            'tickets.*.type'        => ['required_unless:action,concept', 'nullable', Rule::in(['Regular', 'VIP'])],
            'tickets.*.price'       => ['required_unless:action,concept', 'nullable', 'decimal:2', 'min:0'],
            'tickets.*.quantity'    => ['required_unless:action,concept', 'nullable', 'integer', 'min:1'],
            'tickets.*.description' => ['nullable', 'string', 'max:120'],

            'free_event'            => ['nullable'],

            'max_amount_of_visitors' => ['nullable', 'integer', 'min:1', 'required_if:free_event,true,1,yes,on'],

            'image_upload' => ['nullable', 'image', 'required_unless:action,concept,event_image,null'],
            'event_image'  => ['nullable', 'string', 'required_unless:action,concept,image_upload,null'],
        ];
    }
}
