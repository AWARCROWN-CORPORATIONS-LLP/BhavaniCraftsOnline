<x-mail::message>
# New Login Detected

Hello {{ $user->name }},

We noticed a new login to your Bhavani Crafts account.

**When:** {{ $loginTime }}
**IP Address:** {{ $ipAddress }}

If this was you, you can safely ignore this message. If you did not log in, please secure your account immediately by resetting your password.

<x-mail::button :url="url('/password/reset')">
Secure My Account
</x-mail::button>

Thanks,<br>
The {{ config('app.name') }} Security Team
</x-mail::message>
