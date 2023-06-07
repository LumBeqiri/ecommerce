<x-mail::message>
# Hello {{ $name}}

<x-mail::panel>
Thank you for your purchase!
</x-mail::panel>

<x-mail::button :url="''">
Button Text
</x-mail::button>

Please find attached the details of your order<br>
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
