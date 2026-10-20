<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('github:sync')->dailyAt('04:00');
