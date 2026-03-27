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

            'title'             => ['required_unless:action,concept', 'nullable', 'string', 'max:255'],
            'category'          => ['required_unless:action,concept', 'nullable', Rule::exists('categories', 'slug')],

            'short_description' => ['required_unless:action,concept', 'nullable', 'string', 'max:120'],
            'long_description'  => ['nullable', 'string', 'max:500'],

            'location'          => ['required_unless:action,concept', 'nullable', 'string'],
            'city'              => ['required_unless:action,concept', 'nullable', 'string'],
            'street'            => ['required_unless:action,concept', 'nullable', 'string'],

            'start_date'        => ['required_unless:action,concept', 'nullable', 'date', 'after_or_equal:today'],
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

            'image_upload' => ['nullable', 'image', Rule::when($this->input('action') !== 'concept', ['required_without:event_image'])],
            'event_image'  => ['nullable', 'string', Rule::when($this->input('action') !== 'concept', ['required_without:image_upload'])],
        ];
    }
    public function messages(): array
    {
    return [
            'title.required_unless'             => 'The title field is required.',
            'category.required_unless'          => 'The category field is required.',
            'short_description.required_unless' => 'The short description field is required.',
            'location.required_unless'          => 'The location field is required.',
            'city.required_unless'              => 'The city field is required.',
            'street.required_unless'            => 'The street field is required.',
            'start_date.required_unless'        => 'The start date field is required.',
            'end_date.required_unless'          => 'The end date field is required.',
            'start_time.required_unless'        => 'The start time field is required.',
            'end_time.required_unless'          => 'The end time field is required.',

            'participants.required_unless'          => 'At least one participant is required.',
            'participants.*.name.required_unless'   => 'The participant name field is required.',
            'participants.*.email.required_unless'  => 'The participant email field is required.',
            'participants.*.role.required_unless'   => 'The participant role field is required.',

            'tickets.required_unless'               => 'At least one ticket is required.',
            'tickets.*.type.required_unless'        => 'The ticket type field is required.',
            'tickets.*.price.required_unless'       => 'The ticket price field is required.',
            'tickets.*.quantity.required_unless'    => 'The ticket quantity field is required.',

            'max_amount_of_visitors.required_if' => 'The max amount of visitors field is required',

            'image_upload.required_unless'  => 'A cover image is required.',
            'event_image.required_unless'   => 'A cover image is required.',
        ];
    }
}
