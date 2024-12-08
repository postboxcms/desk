<?php

namespace PostboxCMS\Desk\Console;

use Artisan;
use Illuminate\Console\Command;

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
        $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Setting up CMS essentials, please wait ...</>');

        try {
            // migrate database
            Artisan::call('migrate:refresh');
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database migration complete!</>');

            // setup basic content types
            Artisan::call('db:seed');
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database seeding complete!</>');

            // setup passport authentication
            Artisan::call('passport:install');
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Authentication setup complete!</>');

            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> Your CMS is ready !!</>');
        } catch (\Exception $e) {
            $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: ' . $e->getMessage() . '</>');
        }

    }
}
