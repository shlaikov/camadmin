<?php

namespace App\Broadcasting;

use App\Models\Process;
use App\Models\User;

class ProcessChannel
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return array|bool
     */
    public function join(User $user, Process $process)
    {
        return $user->currentTeam->id === $process->team_id;
    }
}
