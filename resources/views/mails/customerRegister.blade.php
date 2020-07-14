@component('mail::message')
{{-- # Hello {{ $email->name }} --}}

You've successfully registered to Piggy Bank Loyalty, enjoy your earnings :)


@component('mail::panel')
If you need help please contact us at: <br />
Email: piggybankloyalty@gmail.com<br />
Whatsapp: 17845303260 <br />

Our app is available on android and ios platforms <br />
<a href="https://apps.apple.com/us/app/piggy-bank-svg/id1502265988">
    <img src="{{ env('APP_URL') }}/img/store/appstore.png" />
</a>
<a href="https://play.google.com/store/apps/details?id=com.piggy">
    <img src="{{ env('APP_URL') }}/img/store/playstore.png" />
</a>
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
