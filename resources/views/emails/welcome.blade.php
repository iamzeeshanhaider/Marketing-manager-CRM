<x-mail::message>
# Dear {{ $details['name'] }},

<br>
We are excited to inform you that your account has been successfully created!

<br>
Below are your login credentials:

<x-mail::panel>
    Email: {{ $details['email'] }} <br>
    Password: {{ $details['default_password'] }}
</x-mail::panel>


Please keep your login credentials safe and do not share them with anyone.

To access your account, click on the button below:

<x-mail::button :url="''">
Login
</x-mail::button>

If you have any questions or need assistance, please don't hesitate to contact our support team.

Thank you for joining our community!

Best regards, <br>
{{ $details['company_name'] ?? config('app.name') }} <br>
{{ $details['company_email'] }}
</x-mail::message>
