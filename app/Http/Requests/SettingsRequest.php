<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['Admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'MAILGUN_DOMAIN' => 'required|regex:/^\S+$/',
            'MAILGUN_SECRET' => 'required|regex:/^\S+$/',
            'MAIL_MAILER' => 'required|regex:/^\S+$/',
            'MAIL_HOST' => 'required|regex:/^\S+$/',
            'MAIL_PORT' => 'required|regex:/^\S+$/',
            'MAIL_USERNAME' => 'required|regex:/^\S+$/',
            'MAIL_PASSWORD' => 'required|regex:/^\S+$/',
            'TWILIO_ACCOUNT_SID' => 'required|regex:/^\S+$/',
            'TWILIO_AUTH_TOKEN' => 'required|regex:/^\S+$/',
            'TWILIO_SMS_FROM' => 'required|regex:/^\S+$/',
            'VONAGE_KEY' => 'required|regex:/^\S+$/',
            'VONAGE_SECRET' => 'required|regex:/^\S+$/',
            'VONAGE_NUMBER' => 'required|regex:/^\S+$/',
            'MAIL_FROM_ADDRESS' => 'required|regex:/^\S+$/',
            'LEADS_SAMPLE' => 'nullable',
        ];
    }
}
