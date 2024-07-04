<?php

namespace App\Services;

use App\Enums\CampaignTypes;
use App\Http\Resources\LeadConversationResource;
use Vonage\Client;
use App\Models\Lead;
use Vonage\Laravel\Facade\Vonage;
use Vonage\SMS\Message\SMS;
use Vonage\Voice\NCCO\NCCO;

class VonageCallService
{
    protected $vonageClient;

    public function __construct(Client $vonageClient)
    {
        $this->vonageClient = $vonageClient;
    }

    public function startCall(Lead $lead)
    {
        // $to_phone_number = app()->environment('production') ? $lead->tel : config('vonage.default_number');

        $to_phone_number = '+447742948317';

        $from_phone_number = config('VONAGE_NUMBER');

        try {

            $outboundCall = new \Vonage\Voice\OutboundCall(
                new \Vonage\Voice\Endpoint\Phone('447742948317'),
                new \Vonage\Voice\Endpoint\Phone('447508079212')
            );
            $outboundCall
                ->setAnswerWebhook(
                    new \Vonage\Voice\Webhook(route('vonage.webhook', 'answer'))
                )
                ->setEventWebhook(
                    new \Vonage\Voice\Webhook(route('vonage.webhook', 'event'))
                );
            $response = Vonage::voice()->createOutboundCall($outboundCall);

            $response = $this->vonageClient->voice()->createOutboundCall($outboundCall);

            return [
                'success' => true,
                'message' => 'Call Started Gracefully',
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function answerCall()
    {
        $outboundCall = new \Vonage\Voice\OutboundCall(
            new \Vonage\Voice\Endpoint\Phone('14843331234'),
            new \Vonage\Voice\Endpoint\Phone('14843335555')
        );

        $outboundCall
            ->setAnswerWebhook(
                new \Vonage\Voice\Webhook(route('vonage.webhook', 'answer'))
            )
            ->setEventWebhook(
                new \Vonage\Voice\Webhook(route('vonage.webhook', 'event'))
            );

        $response = $this->vonageClient->voice()->createOutboundCall($outboundCall);

        dd($response);

        return [
            'success' => true,
            'message' => 'Call Started Gracefully',
        ];
    }

    // Building a call with NCCO Actions
    public function buildCall()
    {
        $ncco = new NCCO();
        $ncco->addAction(
            new \Vonage\Voice\NCCO\Action\Talk('This is a text to speech call from Vonage')
        );
        $ncco->addAction(
            new \Vonage\Voice\NCCO\Action\Stream('https://nexmo-community.github.io/ncco-examples/assets/voice_api_audio_streaming.mp3')
        );
        $ncco->addAction(
            new \Vonage\Voice\NCCO\Action\Talk('Goodbye')
        );

        return $ncco;
    }

    public function sendTestCall($phoneNumber, $content)
    {
        if (!$phone_number = $this->verifyPhoneNumber($phoneNumber)) {
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

    public function verifyPhoneNumber($phoneNumber)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneNumberObject = $phoneUtil->parse($phoneNumber, 'GB');
            if ($phoneUtil->isValidNumber($phoneNumberObject)) {
                return $phoneUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::E164);
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    public function getCallStatus($uuid)
    {
        $response = $this->vonageClient->voice()->get($uuid);

        return $response;
    }

    public function getCallRecording($uuid)
    {
        $response = $this->vonageClient->voice()->getRecording($uuid);

        return $response;
    }

    public function getCallRecordings()
    {
        $response = $this->vonageClient->voice()->listRecordings();

        return $response;
    }

    public function getCallDetails($uuid)
    {
        $response = $this->vonageClient->voice()->get($uuid);

        return $response;
    }

    public function getCallDetailsByConversation($conversation_uuid)
    {
        $response = $this->vonageClient->voice()->get($conversation_uuid);

        return $response;
    }
}


// Sample $response
// #conversationUuid: "CON-ae7282f5-ff91-4670-8b8d-76e851ce418b"
// #detail: null
// #direction: "outbound"
// #duration: null
// #endTime: null
// #from: "447939683379"
// #network: null
// #price: null
// #rate: null
// #status: "started"
// #startTime: null
// #timestamp: DateTimeImmutable @1690719844 {#1491 ▼
// date: 2023-07-30 12:24:04.0 +00:00
// }
// #to: "447939683379"
// #uuid: "e4a9b616-e0f7-4df0-b960-c2ced1f14678"
