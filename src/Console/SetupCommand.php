<?php

namespace PostboxCMS\Desk\Console;

use Artisan;
use Illuminate\Console\Command;
use Number;

#[AsCommand(name: 'cms:setup')]
class SetupCommand extends Command
{
    use Concerns\InteractsWithDockerComposeServices;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup CMS essentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] == 'production') {
            $confirm = $this->confirmPrompt('Application in production mode. Are you sure you want to continue?', false, 'Yes', 'No');
            if(!$confirm) {
                return false;
            }
        }

        $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Setting up CMS essentials, please wait ...</>');

        try {
            // migrate database
            Artisan::call('migrate',['--no-interaction' => true]);
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database migration complete!</>');

            // setup basic content types
            Artisan::call('db:seed',['--no-interaction' => true]);
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database seeding complete!</>');

            // setup passport authentication
            $framework = app()->version();
            if((float) $framework >= 11.0) {
                Artisan::call('install:api',['--no-interaction' => true, '--passport' => true]);
            } else {
                Artisan::call('passport:install',['--no-interaction' => true]);
            }

            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Authentication setup complete!</>');

            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> Your CMS is ready !!</>');
        } catch (\Exception $e) {
            $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: ' . $e->getMessage() . '</>');
        }

    }
}
