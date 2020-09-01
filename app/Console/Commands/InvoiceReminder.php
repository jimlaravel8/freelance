<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Platform\Models\Business;

class InvoiceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return mixed
     */
    public function handle()
    {
        return $business_id = DB::table('business_payment_transactions')->where('paid', 0)->get('user_id');
        $business = [];
        foreach ($business_id as $bus) {
            $business[] = $bus->user_id;
        }

        $businesses = Business::whereIn('id', $business)->get('email');


        foreach ($businesses as $key => $value) {

        }
    }
}
