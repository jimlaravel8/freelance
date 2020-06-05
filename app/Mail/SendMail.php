<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->locale = (isset($email->locale)) ? $email->locale : config('default.language');
        $this->app_name = (isset($email->app_name)) ? $email->app_name : config('default.app_name');
        $this->app_url = (isset($email->app_url)) ? $email->app_url : config('default.app_url');
        $this->from_name = (isset($email->from_name)) ? $email->from_name : config('default.mail_from_name');
        $this->from_email = (isset($email->from_email)) ? $email->from_email : config('default.mail_from_address');
        $this->to_name = $email->to_name;
        $this->to_email = $email->to_email;
        $this->subject = $email->subject;
        $this->body_top = (isset($email->body_top)) ? $email->body_top : null;
        $this->cta_label = (isset($email->cta_label)) ? $email->cta_label : null;
        $this->cta_url = (isset($email->cta_url)) ? $email->cta_url : null;
        $this->cta_color = (isset($email->cta_color)) ? $email->cta_color : 'primary';
        $this->body_bottom = (isset($email->body_bottom)) ? $email->body_bottom : null;
        $this->md_enabled = (isset($email->md_enabled)) ? $email->md_enabled : false;
        $this->md_enabled_2 = (isset($email->md_enabled_2)) ? $email->md_enabled_2 : "deil";
        $this->points = (isset($email->points)) ? $email->points : null;
        $this->customer_name = (isset($email->customer_name)) ? $email->customer_name : null;
        $this->business_name = (isset($email->business_name)) ? $email->business_name : null;
        $this->purchase_amount = (isset($email->purchase_amount)) ? $email->purchase_amount : null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.default')
          ->locale($this->locale)
          ->from($this->from_email, $this->from_name)
          ->to($this->to_email, $this->to_name)
          ->subject('[' . $this->app_name . '] ' . $this->subject)
          ->with([
              'app_name' => $this->app_name,
              'app_url' => $this->app_url,
              'from_name' => $this->from_name,
              'from_email' => $this->from_email,
              'to_name' => $this->to_name,
              'to_email' => $this->to_email,
              'subject' => $this->subject,
              'body_top' => $this->body_top,
              'cta_label' => $this->cta_label,
              'cta_url' => $this->cta_url,
              'cta_color' => $this->cta_color,
              'body_bottom' => $this->body_bottom,
              'md_enabled' => $this->md_enabled,
              'md_enabled_2' => $this->md_enabled,
              'points' => $this->points,
              'customer_name' => $this->customer_name,
              'business_name' => $this->business_name,
              'purchase_amount' => $this->purchase_amount
          ]);
    }
}
