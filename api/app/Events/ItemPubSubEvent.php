<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ItemPubSubEvent implements ShouldBroadcast
{
    public $item;
    public $status;

    /**
     * Setup event message
     *
     * @param Item $item
     * @param string $status
     */
    public function __construct(Item $item, string $status)
    {
        $this->item = $item;
        $this->status = $status;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return 'ItemPubSub';
    }
    
    /**
     * The event's broadcast name.
    *
    * @return string
    */
    public function broadcastAs()
    {
        return 'ItemPubSubEvent';
    }
}
