<?php

namespace App\Http\Resources;

use App\Enums\CampaignTypes;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public static function sanitizeResponse(string $type, $data = null, $response = null, $request = null): array
    {
        $dataArray = [];
        if ($data && !is_array($data)) {
            $dataArray['campaign_id'] = $data->id;
        } else {
            $dataArray = [
                'subject' => $data['subject'] ?? '',
                'message' => $data['content'] ?? $response['content'] ?? $data['comment'],
                'agent_id' => auth()->user()->id,
            ];
        }
        switch ($type) {
            case CampaignTypes::SMS:
                $dataArray['type'] = CampaignTypes::SMS;
                $dataArray = [
                    'status' => $data->getStatus() === 0 ? 'Success' : 'Failed',
                    'price' => $data->getMessagePrice() ?? null,
                    'reference' => $data->getMessageId() ?? null,
                    'network' => $data->getNetwork() ?? null,
                ];
                break;

            case CampaignTypes::Email:
                $dataArray['type'] = CampaignTypes::Email;
                $dataArray['comment'] =  $data['content'] ?? $response['content'] ?? $data['comment'];
                unset($dataArray['message']);
                break;

            case CampaignTypes::Call:
                $dataArray['type'] = CampaignTypes::Call;
                break;
            case CampaignTypes::Comment:
                $dataArray['type'] = CampaignTypes::Comment;
                $dataArray['comment'] =  $data['content'] ?? $response['content'] ?? $data['comment'];
                unset($dataArray['message']);
                break;
            case CampaignTypes::Invoice:
                $dataArray['type'] = CampaignTypes::Invoice;
                $dataArray['comment'] =  $data['content'] ?? $response['content'] ?? $data['comment'];
                unset($dataArray['message']);
                break;
        }

        return $dataArray;
    }
}
