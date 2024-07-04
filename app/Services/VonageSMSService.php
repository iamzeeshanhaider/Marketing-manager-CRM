<?php

namespace App\Services;

use App\Enums\CampaignTypes;
use App\Http\Resources\LeadConversationResource;
use App\Models\Lead;
use Vonage\Laravel\Facade\Vonage;
use Vonage\SMS\Message\SMS;

class VonageSMSService
{
    public function verifyPhoneNumber($phoneNumber)
    {
        if (app()->environment('production')) {
            // Use the Number Insight Advanced API to check the phone number
            $insight = Vonage::insights()->advanced($phoneNumber);
            if ($insight->toArray()['status'] == 0 && $insight->toArray()['valid_number'] == 'valid') {
                return $phoneNumber;
            }
        }

        return config('vonage.default_number');
    }

    public function sendSMS(Lead $lead, $data, $type = 'campaign')
    {
        if (!$phone_number = $this->verifyPhoneNumber($lead->tel)) {
            return [
                'success' => false,
                'message' => 'Lead Phone Number is invalid and unable to receive SMS at the moment'
            ];
        }

        try {
            $brand_name = str_limit($lead->company->name ?? config('app.name'), 11);

            $text = new SMS($phone_number, $brand_name, $data['content']);
            $res = Vonage::sms()->send($text);

            $response = $res->current();

            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'An error occurred'
                ];
            }

            // Save response
            if ($type <> 'campaign') {
                $lead->conversations()->create(LeadConversationResource::sanitizeResponse(CampaignTypes::SMS, $response, $data));
            }

            // Update company Vonage balance
            $balance = $response->getRemainingBalance();
            $lead->company->setting->updateRemainingBalance($balance);

            return [
                'success' => true,
                'message' => 'Message Sent Successfully'
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function sendTestSMS($phoneNumber, $content)
    {
        if (!$phone_number = $this->verifyPhoneNumber($phoneNumber)) {
            return [
                'success' => false,
                'message' => 'Lead Phone Number is invalid and unable to receive SMS at the moment'
            ];
        }

        try {
            $brand_name = str_limit(config('app.name'), 11);

            $text = new SMS($phone_number, $brand_name, $content);
            $res = Vonage::sms()->send($text);

            $response = $res->current();

            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'An error occurred'
                ];
            }

            return ($response);
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
