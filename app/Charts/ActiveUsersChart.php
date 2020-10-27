<?php

declare(strict_types = 1);

namespace App\Charts;

use Carbon\Carbon;
use App\User;
use App\Activity;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ActiveUsersChart extends BaseChart
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

        $activities = Activity::with(['company'])
                        ->selectRaw('DATE_FORMAT(created_at, "%d-%M") as date, DATE_FORMAT(created_at, "%M") as month, created_by, COUNT(*) as count')
                        ->groupByRaw('created_by')
                        ->where('created_at', '>', Carbon::now()->subMonth())
                        ->orderBy('count', 'desc')
                        ->get();    

        return Chartisan::build()
            ->labels($activities->pluck('company.email')->all())
            ->dataset('Active users', $activities->pluck('count')->all());
    }
}