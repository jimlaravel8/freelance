@component('mail::layout')

  @slot('header')
    @component('mail::header', ['url' => $app_url])
      {{ $app_name }}
    @endcomponent
  @endslot

{{ $body_top }}

<?php if ($cta_url !== null) { ?>

@component('mail::button', ['url' => $cta_url, 'color' => $cta_color])
  {{ $cta_label }}
@endcomponent

<?php } ?>
<?php if(!$md_enabled) { ?>
{{ $body_bottom }}
<?php } ?>

<?php if($md_enabled == "points_issued"){ ?>
  Congrats **{{ $customer_name }}**! You've got **{{ $points }}** point(s) from **{{ $business_name }}**
<?php } ?>

<?php if($md_enabled == "points_redeemed"){ ?>
  Congrats **{{ $customer_name }}**! You successfully redeemed **{{ $points }}** points from **{{ $business_name }}**.
  {{-- Congrats **{{ $customer_name }}**! You successfully redeemed **{{ $points }}** points from **XCD{{round($purchase_amount/2.67, 3)}}** discount at **{{ $business_name }}**. --}}
<?php } ?>

  @slot('footer')
    @component('mail::footer')

<?php if ($cta_url !== null) { ?>

      {{ trans('mail.email_trouble_clicking_button', ['cta_label' => $cta_label]) }}
      {{ $cta_url }}

<?php } ?>

      {!! trans('mail.email_ip_address', ['ip_address' => request()->ip()]) !!}

    @endcomponent
  @endslot
@endcomponent
