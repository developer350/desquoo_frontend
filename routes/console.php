<?php

use App\Console\Commands\OrderPaymentCheck;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();
Schedule::command(OrderPaymentCheck::class)->everyThreeMinutes();

