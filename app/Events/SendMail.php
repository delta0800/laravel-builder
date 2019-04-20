<?php

namespace App\Events;

use App\DownloadRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class SendMail
{
    use Dispatchable, SerializesModels;

    public $downloadRequest;

    /**
     * Create a new event instance.
     *
     * @param DownloadRequest $downloadRequest
     */
    public function __construct(DownloadRequest $downloadRequest)
    {
        $this->downloadRequest = $downloadRequest;
    }
}
