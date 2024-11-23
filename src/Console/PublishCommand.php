<?php

namespace PostboxCMS\Desk\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'desk:publish')]
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desk:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the PostboxCMS Desk Docker files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'desk-docker']);
        $this->call('vendor:publish', ['--tag' => 'desk-database']);

        file_put_contents(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    './vendor/postboxcms/desk/runtimes/8.4',
                    './vendor/postboxcms/desk/runtimes/8.3',
                    './vendor/postboxcms/desk/runtimes/8.2',
                    './vendor/postboxcms/desk/runtimes/8.1',
                    './vendor/postboxcms/desk/runtimes/8.0',
                    './vendor/postboxcms/desk/database/mysql',
                    './vendor/postboxcms/desk/database/pgsql'
                ],
                [
                    './docker/8.4',
                    './docker/8.3',
                    './docker/8.2',
                    './docker/8.1',
                    './docker/8.0',
                    './docker/mysql',
                    './docker/pgsql'
                ],
                file_get_contents($this->laravel->basePath('docker-compose.yml'))
            )
        );
    }
}
