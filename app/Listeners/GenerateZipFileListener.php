<?php

namespace App\Listeners;

use App\Events\GenerateZipFile;
use App\User;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class GenerateZipFileListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerateZipFile  $event
     * @return void
     */
    public function handle(GenerateZipFile $event)
    {
        $path = storage_path('app/generated/' . $event->downloadRequest->project_id . '/' . $event->downloadRequest->id);
        $zip = new ZipArchive();
        $zipFile = $event->downloadRequest->id.".zip";

        if ($zip->open(storage_path() . '/app/public/' .$zipFile, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        $event->downloadRequest->fill(['filename' => $zipFile])->save();

        User::find(1)->notify(
            new \App\Notifications\ZipFileNotification(
                $event->downloadRequest
            )
        );
    }
}
