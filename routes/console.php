<?php

use Illuminate\Foundation\Inspiring;
use App\Models\Lesson\Lesson;
use App\Models\Settings;
use App\Events\StagingSyncFinished;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


/**
 * Пересчитать бонусы по всем занятиям
 */
Artisan::command('calculate-bonus', function () {
    $lessons = Lesson::all();
    $bar = $this->output->createProgressBar(count($lessons));
    foreach($lessons as $lesson)  {
        Lesson::whereId($lesson->id)->update([
            'bonus' => $lesson->calculateBonus()
        ]);
        $bar->advance();
    }
    $bar->finish();
});

/**
 * Завершить синхронизацию staging
 */
Artisan::command('staging-sync:finish', function () {
    Settings::set('is_syncing_staging', 0);
    event(new StagingSyncFinished);
});
