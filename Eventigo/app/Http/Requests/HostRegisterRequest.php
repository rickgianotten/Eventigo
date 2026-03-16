<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class HostRegisterRequest extends FormRequest
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
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required','email','unique:users,email'],
            'user.password' => ['required','confirmed', Password::min(6)],

            'company.name' => ['required',],
            'company.email' => ['required','email', 'unique:companies,email'],
            'company.pricing_plan' => ['required', Rule::in(['free', 'premium_monthly', 'premium_yearly' ])],

            'terms_accepted' => ['accepted'],
        ];
    }

    protected function prepareForValidation()
    {
        $user = $this->input('user');
        $user['email'] = Str::lower($user['email']);

        $company = $this->input('company');
        $company['email'] = Str::lower($company['email']);

        $this->merge([
            'user' => $user,
            'company' => $company
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $validatedValues =  parent::validated();

        $validatedValues['user']['role'] = 'host';
        $validatedValues['user']['terms_accepted'] = $validatedValues['terms_accepted'];

        $validatedValues['company']['slug'] = Str::slug( $validatedValues['company']['name']);

        return $validatedValues;
    }
}
