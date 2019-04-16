<?php

namespace App\Listeners;

use App\Events\GenerateCurd;
use App\Events\GenerateZipFile;
use App\Table;
use Illuminate\Support\Facades\Artisan;

class SendGeneratedCurd
{
    /**
     * Create a new event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  generateCurd  $event
     * @return void
     */
    public function handle(GenerateCurd $event)
    {
        $tableId = json_decode($event->downloadRequest->table_id);

        $composer = json_decode($event->downloadRequest->composer, true);

        $path = storage_path('app/generated/'.$event->downloadRequest->project_id.'/'.$event->downloadRequest->id);

        if ($composer) {
            $readFile = file_get_contents(($path.'/composer.json'));
            $data = (json_decode($readFile));

            $data->require = collect($data->require)->merge($composer);

            $writeFile = json_encode($data, JSON_PRETTY_PRINT);

            file_put_contents(($path.'/composer.json'), str_replace("\/", "/", $writeFile));
        }

        foreach ($tableId as $id) {
            Artisan::call('generate:crud', [
                'table' => $id,
                '--path' => $path,
            ]);
        }

        $tables = Table::whereIn('id', $tableId)->get();

        Artisan::call('generate:sidebar', [
            'name' => 'nav',
            '--tables' => $tables,
            '--force' => true,
            '--path' => $path,
        ]);

        event(new GenerateZipFile($event->downloadRequest));
    }
}
