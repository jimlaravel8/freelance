<?php
namespace App\Console\Commands;

use App\User;
use Platform\Models\History;
use Platform\Models\Business;
use App\Mail\SendMail;
use App\Mail\SendBusinessMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Console\Command;
class SendMonthlyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendmails:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly emails to the businesses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 1- Monthly earnings
     * 2- Monthly spendings
     * @return mixed
     */
    public function handle()
    {
        $month = Carbon::now()->subMonth()->format('M');
        $month_numeric = Carbon::now()->subMonth()->month;
        $year = Carbon::now()->year;

       $user = User::where([
           'role' => 3,
           'active' => 1
       ])->get();

       $user->map(function($user) use ($month, $year, $month_numeric) {
            # Get business credentials
           $business = Business::where('created_by', $user->id)->first();
           $invoice_no = \DB::table('business_payment_transactions')
           ->whereYear('month', $year)
           ->whereMonth('month', $month_numeric)
           ->first();
           $invoice_no = $invoice_no ? $invoice_no->invoice_no : 'PGB-#0000?';
            # Get transactions count
           $total_transactions = History::where('business_id', $business->id)
           ->whereMonth('created_at', $month_numeric)
           ->whereYear('created_at', $year)
           ->count();

            # Get total earnings
           $earnings = History::where('business_id', $business->id)
           ->whereMonth('created_at', $month_numeric)
           ->whereYear('created_at', $year)
           ->where('points', '>', 0);
           $total_earnings = $earnings->sum('points');
           $total_earnings_count = $earnings->count();

            # Get total spendings
           $spendings = History::where('business_id', $business->id)
           ->whereMonth('created_at', $month_numeric)
           ->whereYear('created_at', $year)
           ->where('points', '<=', 0);
           $total_spendings = $spendings->sum('points') * (int) (-1);
           $total_spendings_count = $spendings->count();

           # Get amount due.
           $amount_due = ((int) $total_earnings_count) * ((float) $user->transaction_fee);

           # Get due date.
           $due_date = Carbon::now()->/*addMonth()->*/addDays('6')/*->format('Y-m-d')*/->format('F 7th, Y');
           $transaction_month = Carbon::parse("$month-$year")->format('F, Y');
           # Prepare email
           $email = new \stdClass();
           $email->to_name = $user->first_name;
           $email->to_email = $user->email;
           $email->subject = 'Billing for ' . $transaction_month;
           $email->month = $transaction_month;
           $email->cta_label = "You can get a complete list of your transactions from your profile";
           $email->cta_url = "https://piggybanksvg.com/settings/business/subscription";
           $email->cta_color = "success";

           $email->total_earnings = $total_earnings;
           $email->total_earnings_count = $total_earnings_count;

           $email->total_spendings = $total_spendings;
           $email->total_spendings_count = $total_spendings_count;

           $email->total_transactions = $total_transactions;
           $email->amount_due = $business->currency . ' ' .  $amount_due . ' / USD ' . number_format((float) $amount_due / (float) (2.67), 3);
           $email->due_date = $amount_due != 0 ? $due_date : "-";
           $email->invoice_no = $invoice_no ?? "PGB-#---";

           if($email->to_email == 'cemil@akko.cc') {
             Mail::send(new SendBusinessMail($email));
           }
       });

    }
}
