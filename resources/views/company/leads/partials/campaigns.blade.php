<x-table.datatables :headings="['name', 'email status', 'type', 'action']" title="Lead Campaign">

    @foreach ($campaigns as $index => $campaign)
        <tr>
            <td>
                {{ $campaign->name }}
            </td>
            <td>
                <x-email-status-badge :status="$campaign->email_status" />
            </td>
            <td>
                {{ $campaign->type }}
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <x-table.action :index="$index" :items="[
                        [
                            'name' => 'Preview',
                            'icon' => 'ft-eye',
                            'route' => route('campaign.show', $campaign->id),
                            'type' => 'link',
                        ],
                    ]" />
                </div>
            </td>
        </tr>
    @endforeach
</x-table.datatables>
