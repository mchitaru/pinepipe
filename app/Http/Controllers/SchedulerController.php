<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function run()
    {
        \Artisan::call('schedule:run');
        return 'OK';
    }
}
