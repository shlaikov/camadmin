<?php

namespace App\Broadcasting;

use App\Models\Diagram;
use App\Models\User;

class DiagramChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Process $diagram
     *
     * @return array|bool
     */
    public function join(User $user, Diagram $diagram)
    {
        return $user->currentTeam->id === $diagram->team_id;
    }
}
