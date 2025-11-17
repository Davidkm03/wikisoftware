<?php

namespace App\Listeners;

use App\Events\DocumentPublished;
use App\Mail\DocumentPublishedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendDocumentPublishedNotification implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(DocumentPublished $event): void
    {
        $document = $event->document;

        // Get users to notify (admins and editors, excluding the author)
        $usersToNotify = User::whereIn('role', ['admin', 'editor'])
                            ->where('id', '!=', $document->user_id)
                            ->get();

        foreach ($usersToNotify as $user) {
            Mail::to($user->email)->send(new DocumentPublishedMail($document, $user));
        }
    }

    /**
     * Determine if the listener should be queued.
     */
    public function shouldQueue(DocumentPublished $event): bool
    {
        // Only queue if the document is published
        return $event->document->status === 'published';
    }
}
