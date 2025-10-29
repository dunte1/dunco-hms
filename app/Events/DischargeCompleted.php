<?php

namespace App\Events;

use App\Models\IpdAdmission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DischargeCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $admission;

    /**
     * Create a new event instance.
     */
    public function __construct(IpdAdmission $admission)
    {
        $this->admission = $admission;
    }
}

