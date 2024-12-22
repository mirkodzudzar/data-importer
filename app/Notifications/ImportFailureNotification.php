<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportFailureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected int $importId, protected string $fileName)
    {
        $this->importId = $importId;
        $this->fileName = $fileName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__("Import Failed Notification"))
            ->line(__("The import process for the file ':file' has failed.", ['file' => $this->fileName]))
            ->line(__("Import ID: ").$this->importId)
            ->action(__("View All Imports"), route('imports.index'))
            ->line(__("Please check the logs for more details."));
    }
}
