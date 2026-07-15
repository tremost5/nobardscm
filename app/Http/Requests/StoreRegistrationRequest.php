<?php

namespace App\Http\Requests;

use App\Support\WhatsappNumber;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'whatsapp_number' => WhatsappNumber::normalize((string) $this->input('whatsapp_number')),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'church_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => [
                'required',
                'string',
                'regex:/^62\d{8,13}$/',
                Rule::unique('registrations', 'whatsapp_number'),
            ],
            'bring_snack' => ['nullable', 'boolean'],
            'website' => ['nullable', 'prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus diawali 62 dan berisi 10 sampai 15 digit angka.',
            'whatsapp_number.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
        ];
    }
}
