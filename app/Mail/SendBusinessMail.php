<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendBusinessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->to_name = $email->to_name;
        $this->to_email = $email->to_email;
        $this->subject = $email->subject;
        $this->total_transactions = $email->total_transactions;
        $this->total_earnings = $email->total_earnings;
        $this->total_spendings = $email->total_spendings;
        $this->total_earnings_count = $email->total_earnings_count;
        $this->total_spendings_count = $email->total_spendings_count;
        $this->amount_due = $email->amount_due;
        $this->due_date = $email->due_date;
        $this->month = $email->month;
        $this->invoice_no = $email->invoice_no;

        $this->locale = (isset($email->locale)) ? $email->locale : config('default.language');
        $this->app_name = (isset($email->app_name)) ? $email->app_name : config('default.app_name');
        $this->app_url = (isset($email->app_url)) ? $email->app_url : config('default.app_url');
        $this->from_name = (isset($email->from_name)) ? $email->from_name : config('default.mail_from_name');
        $this->from_email = (isset($email->from_email)) ? $email->from_email : config('default.mail_from_address');
        $this->body_top = (isset($email->body_top)) ? $email->body_top : null;
        $this->cta_label = (isset($email->cta_label)) ? $email->cta_label : null;
        $this->cta_url = (isset($email->cta_url)) ? $email->cta_url : null;
        $this->cta_color = (isset($email->cta_color)) ? $email->cta_color : 'primary';
        $this->body_bottom = (isset($email->body_bottom)) ? $email->body_bottom : null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.transactions')
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
              'total_spendings' => $this->total_spendings,
              'total_earnings' => $this->total_earnings,
              'total_spendings_count' => $this->total_spendings_count,
              'total_earnings_count' => $this->total_earnings_count,
              'total_transactions' => $this->total_transactions,
              'amount_due' => $this->amount_due,
              'due_date' => $this->due_date,
              'month' => $this->month,
              'invoice_no' => $this->invoice_no
          ]);
    }
}
