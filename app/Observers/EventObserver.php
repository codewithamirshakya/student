<?php

namespace App\Observers;

use App\Events\EventCreated;
use App\Models\Event;

class EventObserver
{
    public function creating(Event $event): void
    {
        if (auth()->check()) {
            $event->user_id ??= auth()->user()->id;
        }
    }
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        EventCreated::dispatch($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
