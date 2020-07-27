@component('mail::layout')

  @slot('header')
    @component('mail::header', ['url' => $app_url])
      {{ $app_name }}
    @endcomponent
  @endslot

  [![](https://piggybanksvg.com/img/logo.png)](https://piggybanksvg.com/ "Piggy Bank Loyalty | Happy Earnings :)")
  # Hello {{$to_name}},

<p>Here are the transactions for the month of {{$month}}</p>
<div style="font-size: 14px">

|                       |                               |
|-----------------------|-------------------------------|
|**Total Transactions**:|`{{$total_transactions}}`      |
|**Points Issuance Sessions**:|`{{$total_earnings_count}}`      |
|**Points Redemption Sessions**:|`{{$total_spendings_count}}`      |
|**Points Issued**     :|`{{$total_earnings}}`          |
|**Points Redeemed**   :|`{{$total_spendings}}`         |
|**Due Date**          :|`{{$due_date}}`                |
|**Amount Due**        :|`{{$amount_due}}`              |
|**Invoice No**        :|`{{$invoice_no}}`              |

#### Payment Options

### Pay by Paypal:
 - Go to https://www.paypal.me/caribbeanadventures
 - Please send USD amount on invoice
 - Please include your invoice number(s)

### Pay by Check
 - Make check payable to: Piggy Bank Loyalty
  - Mail check to: Kingstown, St Vincent & the Grenadines
  - Alternatively, we can arrange pickup.

### Squeeze Cash
- Send XCD invoice amount to: SQ04269
 - Please include your invoice number(s)


### Payswif:
Send XCD invoice amount to: 17845303260
Please include your invoice number(s)
</div>
<?php if ($cta_url !== null) { ?>

@component('mail::button', ['url' => $cta_url, 'color' => $cta_color])
  {{ $cta_label }}
@endcomponent

<?php } ?>

{{ $body_bottom }}

  @slot('footer')
    @component('mail::footer')

<?php if ($cta_url !== null) { ?>

      {{ trans('mail.email_trouble_clicking_button', ['cta_label' => $cta_label]) }}
      {{ $cta_url }}

<?php } ?>

      {!! 'Copyright © 2020 | Caribbean Adventures Ltd., | All rights reserved.' !!}

    @endcomponent
  @endslot
@endcomponent
