<x-mail::message>
# Welcome to Bhavani Crafts!

Hi {{ $user->name }},

Thank you for joining our community of artisans and collectors. We are excited to have you with us!

Please verify your email address to activate your account and start exploring our collections.

<x-mail::button :url="$verificationUrl">
Verify My Email
</x-mail::button>

If you didn't create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
