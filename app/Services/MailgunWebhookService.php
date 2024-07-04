<?php

namespace App\Services;

use App\Models\Lead;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailgunWebhookService
{
    public $eventType = '';
    public $eventData = '';

    /**
     * Handle incoming webhook request from Mailgun
     *
     * @param Request $request
     * @return void
     */
    public function handleWebhook(Request $request)
    {

        /*if (!$this->verifyWebhookSignature($request)) {
            abort(403);
        }*/

        $this->eventData = $eventData = $request->input('event-data');
        $this->eventType = $eventType = $eventData['event'];

        $this->handleDifferentTypesOfEvents($eventType, $eventData);
    }

    /**
     * Handle different types of events
     *
     * @param string $eventType
     * @param array $eventData
     * @return void
     */
    private function handleDifferentTypesOfEvents(string $eventType, array $eventData)
    {
        $messageId = $eventData['message']['headers']['message-id'];
        $recipient = $eventData['recipient'];

        switch ($eventType) {
            case 'delivered':
                return $this->handleDeliveredEvent($messageId, $recipient);
                break;
            case 'opened':
                return $this->handleOpenedEvent($messageId, $recipient);
                break;
            case 'clicked':
                return $this->handleClickedEvent($messageId, $recipient);
                break;
            case 'unsubscribed':
                return $this->handleUnsubscribedEvent($messageId, $recipient);
                break;
            case 'complained':
                return $this->handleComplainedEvent($messageId, $recipient);
                break;
            case 'failed':
                return $this->handleFailedEvent($messageId, $recipient);
                break;
            default:
                break;
        }

        return response()->json(['message' => 'Event received']);
    }

    /**
     * Verify webhook signature
     *
     * @param Request $request
     * @return boolean
     */
    private function verifyWebhookSignature(Request $request)
    {
        $timestamp = $request->input('timestamp');
        $token = $request->input('token');
        $signature = $request->input('signature');

        $computedSignature = hash_hmac(
            'sha256',
            $timestamp . $token,
            config('services.mailgun.private')
        );

        return hash_equals($signature, $computedSignature);
    }

    /**
     * Handle delivered event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleDeliveredEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        self::updateLead($recipient, 'delivered');

        return $this->storeDataInDB($response);
    }

    /**
     * Handle opened event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleOpenedEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        return $this->storeDataInDB($response);
    }

    /**
     * Handle clicked event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleClickedEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        return $this->storeDataInDB($response);
    }

    /**
     * Handle unsubscribed event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleUnsubscribedEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        return $this->storeDataInDB($response);
    }

    /**
     * Handle complained event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleComplainedEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        return $this->storeDataInDB($response);
    }

    /**
     * Handle failed event
     *
     * @param integer $messageId
     * @param string $recipient
     * @return void
     */
    private function handleFailedEvent(string $messageId, string $recipient)
    {
        $response = [
            'messageId' => $messageId,
            'recipient' => $recipient,
        ];

        self::updateLead($recipient, 'failed');

        return $this->storeDataInDB($response);
    }

    public function storeDataInDB($response)
    {
        DB::table('email_campaign_response_data')->insert([
            'message_id' => $response['messageId'],
            'recipient' => $response['recipient'],
            'event_type' => $this->eventType,
            'event_data' => json_encode($this->eventData),
            'created_at' => Carbon::now()
        ]);

        return true;
    }

    static function updateLead($recipient, $action = 'delivered')
    {
        $lead = Lead::where('email', $recipient)->first();

        if($action == 'delivered'){
            $lead->email_status = 'Active';
        }

        $lead->last_email = now();
        $lead->save();

        return true;
    }
}
