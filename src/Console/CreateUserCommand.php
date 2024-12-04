<?php

namespace PostboxCMS\Desk\Console;

use Illuminate\Console\Command;

#[AsCommand(name: 'desk:adduser')]
class CreateUserCommand extends Command
{
    use Concerns\InteractsWithDockerComposeServices;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desk:adduser';

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
        $name = $this->_textFieldPrompt('Please enter your full name', 'Admin');
        $email = $this->_textFieldPrompt('Please enter your email address', 'admin@admin.com');
        $password = $this->_passwordFieldPrompt('Please enter user password');

        try {
            $data = [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ];
            $user = \App\Models\User::create($data);
            $user->createToken(env('APP_NAME').' Token')->accessToken;
    
            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> User created successfully</>');
        } catch(\Exception $e) {
            \App\Models\User::where('email',$email)->delete();

            $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: '.$e->getMessage().'</>');
        }

    }
}