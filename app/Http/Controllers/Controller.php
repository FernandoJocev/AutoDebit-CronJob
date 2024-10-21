<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $tax = Tax::where("payment_due", Carbon::now()->format("Y-m-d"))->update([
            "total_pajak" => DB::raw("total_pajak - (total_pajak * (20 / 100)) "),
            "payment_due" => Carbon::now()->addYear(1),
            "last_payment_date" => Carbon::now(),
        ]);

        return $tax;
    }
}
