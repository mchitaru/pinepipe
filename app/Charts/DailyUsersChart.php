<?php

declare(strict_types = 1);

namespace App\Charts;

use Carbon\Carbon;
use App\User;
use App\Activity;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class DailyUsersChart extends BaseChart
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

        // $activities = Activity::selectRaw('DATE_FORMAT(created_at,"%M") as date, created_at, COUNT(DISTINCT created_by) as count')
        //                 ->groupBy('date')
        //                 ->where('created_at', '>', Carbon::now()->subYear())
        //                 ->orderBy('created_at', 'asc')
        //                 ->get();    

        $activities = Activity::selectRaw('DATE_FORMAT(created_at, "%d-%M-%y") as date, DATE_FORMAT(created_at, "%M-%y") as month, COUNT(DISTINCT created_by) as count')
                        ->groupBy('date')
                        ->where('created_at', '>', Carbon::now()->subYear())
                        ->orderBy('created_at', 'asc')
                        ->get();    


        $grouped = $activities->groupBy(function ($item, $key) {
            return $item->month;
        });
        
        
        $dau = $grouped->map(function ($item, $key) {

            return $item->avg('count');
        });

        return Chartisan::build()
            ->labels($dau->keys()->all())
            ->dataset('Active users', $dau->values()->all());
    }
}