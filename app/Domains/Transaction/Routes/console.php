<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('transactions:run-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();
