<?php

use App\Console\Commands\AutoCheckIn;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// DAFTARKAN AUTO CHECK-IN SCHEDULE
// Schedule::call(function () {
//     Log::info('this is from scheduler');
// })->everyFiveSeconds();

Schedule::command(AutoCheckIn::class)->dailyAt('08:45')
             ->timezone('Asia/Jakarta')
             ->withoutOverlapping();;
