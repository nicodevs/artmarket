<?php

namespace App\Listeners;

use App\Search;
use App\Events\ImageSearched;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSearchEntry
{
    public $search;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * Handle the event.
     *
     * @param  ImageSearched  $event
     * @return void
     */
    public function handle(ImageSearched $event)
    {
        $this->search->create([
            'keyword' => $event->keyword,
            'results_count' => $event->query->count(),
            'user_id' => $event->user? $event->user->id : 0
        ]);
    }
}
