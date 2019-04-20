<?php

namespace App\Notifications;

use App\DownloadRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ZipFileNotification extends Notification
{
    use Queueable, SerializesModels;

    protected $downloadRequest;

    /**
     * Create a new notification instance.
     *
     * @param DownloadRequest $downloadRequest
     */
    public function __construct(DownloadRequest $downloadRequest)
    {
        $this->downloadRequest = $downloadRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(Storage::url($this->downloadRequest->filename));

        return (new MailMessage)
                    ->subject('Project ready to download')
                    ->line('Your project zip ready to Download.')
                    ->action('Download', $url)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->downloadRequest->id,
            'project_id'=> $this->downloadRequest->project_id,
            'table_id' => $this->downloadRequest->table_id,
            'filename' => $this->downloadRequest->filename,
            'version'=> $this->downloadRequest->version,
        ];
    }
}