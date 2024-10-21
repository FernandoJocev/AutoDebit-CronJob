<?php

namespace App\Console\Commands;

use App\Models\Tax;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoDebitTax extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autodebit_tax:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $checkDue = Tax::where("payment_due", Carbon::now()->format("Y-m-d"))->get();

        // Tax::find(1, function ($q) {
        //     $q->update([
        //         "total_tax" => $q->total_pajak - ($q->total_pajak * (20 / 100)),
        //     ]);

        //     return $q;
        // });

        if (count($checkDue) > 0) {
            DB::transaction(function () {
                Tax::where("payment_due", Carbon::now()->format("Y-m-d"))->update([
                    "total_pajak" => DB::raw("total_pajak - (total_pajak * (20 / 100)) "),
                    "payment_due" => Carbon::now()->addYear(1),
                    "last_payment_date" => Carbon::now(),
                ]);
            });
        }
    }
}
