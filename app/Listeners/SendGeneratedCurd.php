<?php

namespace App\Listeners;

use App\Events\GenerateCurd;
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
        $tableId = $event->downloadRequest->table_id;

        $path = storage_path($event->downloadRequest->project_id.'/'.$event->downloadRequest->id);

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
    }
}
