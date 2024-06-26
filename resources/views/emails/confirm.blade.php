@component('mail::message')
# Hello {{$user->name}}

You changed your email, we need to verify the new address. Please verify your new email using the button below:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
