<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmdmn:create_superuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating initial superuser after instantiation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CreateNewUser $user)
    {
        if (User::all()->count() > 0) {
            return Command::INVALID;
        }

        if (config('keycloak-web.client_secret')) {
            $this->info("You can only create a user via Keyclock");

            return Command::INVALID;
        }

        try {
            $email = $this->ask('Enter your email');

            $user->create([
                'name' => 'Admin',
                'email' => $email,
                'password' => $this->secret('Enter password'),
                'password_confirmation' => $this->secret(
                    'Enter password conformation'
                )
            ]);

            $this->info("Superuser [$email] successfully created");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
