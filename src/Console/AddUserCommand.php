<?php

namespace PostboxCMS\Desk\Console;

use PostboxCMS\Desk\Console\Models\User;
use Illuminate\Console\Command;

#[AsCommand(name: 'cms:adduser')]
class AddUserCommand extends Command
{
    use Concerns\InteractsWithDockerComposeServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:adduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user using Desk CLI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // prompts to enter user information
        $name = $this->textFieldPrompt('Please enter your full name', 'Admin');
        $email = $this->textFieldPrompt('Please enter your email address', 'admin@admin.com');
        $password = $this->passwordFieldPrompt('Please enter user password');

        try {
            $data = [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ];
            $user = User::create($data);
            
            try {
                $user->createToken(env('APP_NAME') . ' Token');
            } catch (\Exception $e) {
                User::where('email', $email)->delete();
                $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: ' . $e->getMessage() . '</>');
                return;
            }

            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> User created successfully</>');
        } catch (\Exception $e) {
            $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: ' . $e->getMessage() . '</>');
        }

    }
}