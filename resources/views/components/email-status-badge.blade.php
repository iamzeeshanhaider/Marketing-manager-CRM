@props([
    'status' => null,
    'color_code' => [
        'Sent'          => '#00ff00',
        'Pending'       => '#ff6347',
        'Opened'        => '#ff4500',
        'Clicked'       => '#1e90ff',
        'Bounced'       => '#ff6347',
        'Unsubscribed'  => '#9400d3',
        'Spam'          => '#ffff00',
        'Invalid'       => '#ff0000',
        'Deferred'      => '#ffa500',
        'Blocked'       => '#8b0000',
        'Error'         => '#800080',
        'Scheduled'     => '#00ced1',
        'Queued'        => '#228b22',
        'Expired'       => '#a9a9a9',
        'Deleted'       => '#696969',
        'Unconfirmed'   => '#ff69b4',
        'Active'        => '#008000',
        'InActive'      => '#808080',
    ],
])

<div class="badge" style="background-color: {{ $color_code[$status] }}; color: {{ calculateContrastColor($status) }}">
    {{ $status }}
</div>
