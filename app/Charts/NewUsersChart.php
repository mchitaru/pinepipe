<?php

declare(strict_types = 1);

namespace App\Charts;

use Carbon\Carbon;
use App\User;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class NewUsersChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        // $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        //                 ->groupBy('date')
        //                 ->where('created_at', '>', Carbon::now()->subMonth())
        //                 ->get();    

        $users = User::selectRaw('DATE_FORMAT(created_at,"%M-%y") as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->where('email_verified_at', '!=', null)
                        ->where('created_at', '>', Carbon::now()->subYear())
                        ->orderBy('created_at', 'asc')
                        ->get();    

        return Chartisan::build()
            ->labels($users->pluck('date')->all())
            ->dataset('Users', $users->pluck('count')->all());
    }
}