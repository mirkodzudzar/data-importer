<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Import;
use App\Events\ImportFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportFailureNotification;

class SendImportFailureNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImportFailed $event): void
    {
        $user = User::find($event->userId);

        $user->notify(new ImportFailureNotification($event->importId, $event->fileName));
    }
}
