@component('mail::message')

# We have an email for you

An email with the subject **{{ $expiringEmail->subject }}** is now availabe for you to read via the button below.

Please note that this email **expires in {{ $expiringEmail->expires_at->diff(now())->days }} days** (at
{{ $expiringEmail->expires_at->format('Y-m-d H:i') }}).

@component('mail::button', ['url' => $expiringEmail->url()])
View Email
@endcomponent

@component('mail::panel')
**Why do I need to click a link to read this email?**

To ensure the data in the email is kept safe, we do not include sensitive information in our regular emails.
This way we can ensure that no sensitive information is left in inboxes of email providers or recipients and thus limit
the risk of data leaks.
If you question the legitimacy of this email, feel free to reach out and verify that this email was indeed sent by us.
@endcomponent

Best regards,

{{ config('app.name') }}

@endcomponent
