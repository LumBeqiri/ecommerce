@component('mail::message')
# Hello {{$user->name}}

Your password was changed.
If this isn't you please contact support!


Thanks,<br>
{{ config('app.name') }}
@endcomponent
