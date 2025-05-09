<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientActivity
{
    use Dispatchable, SerializesModels;

    public $type;
    public $data;
    public $user;

    public function __construct($type, $data, $user = null)
    {
        $this->type = $type;
        $this->data = $data;
        $this->user = $user;
    }
}
