<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

Class MailgunEmailValidation {
    public function mailgunValidate($email_address)
    {
        try {
            $params = array(
                "address" => $email_address,
            );

            $url = 'https://api.mailgun.net/v4/address/validate';
            $api_key = env('MAILGUN_PRIVATE_API');

            if($api_key) {
                $response = Http::withBasicAuth('api', $api_key)->get($url, $params);
                if($response->status() === 200 && $response['result'] == "deliverable") {
                    return true;
                } else {
                    return false;
                }

            } else {
                return 'false';
            }
        } catch (\Throwable $e) {
            return false;
        }
    }
}
