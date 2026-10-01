<?php

namespace PostboxCMS\Desk\Console;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $signature = 'cms:setup 
                            {--refresh : Refresh the database migrations}
                            {--standalone : Run setup without Docker}';

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
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] == 'production') {
            $confirm = $this->confirmPrompt('Application in production mode. Are you sure you want to continue?', false, 'Yes', 'No');
            if (!$confirm) {
                return false;
            }
        }

        $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Setting up CMS essentials, please wait ...</>');

        try {
            $this->waitForDatabaseConnection();

            // set the default parameters
            $parameters = ['--no-interaction' => true];

            // migrate database
            if ($this->option('refresh')) {
                // if standalone, add force option
                if ($this->option('standalone')) {
                    $parameters = array_merge($parameters, ['--force' => true]);
                }
                Artisan::call('migrate:refresh', $parameters);
            } else {
                // if standalone, add force option
                if ($this->option('standalone')) {
                    $parameters = array_merge($parameters, ['--force' => true]);
                }
                Artisan::call('migrate', $parameters);
            }

            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database migration complete!</>');

            // setup basic content types
            Artisan::call('db:seed', $parameters);
            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Database seeding complete!</>');

            // setup passport authentication
            $framework = app()->version();
            if ((float) $framework >= 11.0) {
                $parameters = array_merge($parameters, ['--passport' => true]);
                Artisan::call('install:api', $parameters);
            } else {
                $parameters = array_merge($parameters, ['--uuids' => true]);
                Artisan::call('passport:install', $parameters);
            }

            $this->output->writeln('<fg=yellow>➜</> <options=bold><fg=yellow>INFO:</> Authentication setup complete!</>');

            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> Your CMS is ready !!</>');
        } catch (\Exception $e) {
            $this->output->writeln('<fg=red>➜</> <options=bold><fg=red>ERROR</>: ' . $e->getMessage() . '</>');
        }

    }

    protected function waitForDatabaseConnection(int $timeoutSeconds = 30): void
    {
        $start = microtime(true);

        while (microtime(true) - $start < $timeoutSeconds) {
            try {
                DB::connection()->getPdo();

                return;
            } catch (\Throwable $e) {
                usleep(500000);
            }
        }

        throw new \RuntimeException('Database connection was not available within ' . $timeoutSeconds . ' seconds.');
    }
}
