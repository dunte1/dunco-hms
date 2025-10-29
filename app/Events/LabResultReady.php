<?php

namespace App\Events;

use App\Models\LabRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabResultReady
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $labRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(LabRequest $labRequest)
    {
        $this->labRequest = $labRequest;
    }
}

