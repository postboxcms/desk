<?php

namespace PostboxCMS\Desk\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use PostboxCMS\Desk\Console\Models\Entities;
use Schema;

class AddEntityCommand extends Command
{

    use Concerns\InteractsWithDockerComposeServices;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:add-entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CMS entity';

    /**
     * Process entity for database entry
     */
    protected function processEntry($entry)
    {
        $entry['slug'] = isset($entry['slug']) && $entry['slug'] !== null ? $entry['slug'] : strtolower($entry['name']);
        $entry['icon'] = isset($entry['icon']) && $entry['icon'] !== null && $entry['icon'] !== "" ? $entry['icon'] : 'fa-square';
        $entry['model'] = isset($entry['model']) && $entry['model'] !== null ? $this->parseInput($entry['model']) : $this->parseInput($entry['name']);

        return $entry;
    }

    /**
     * Create table using Schema object
     */
    public function generateSchema($model)
    {
        try {
            // Generate table
            Schema::connection('mysql')->create(strtolower($model), function ($schema) {
                $schema->id();
                $schema->uuid('uuid')->index()->default(DB::raw('(uuid())'))->index();
                $schema->timestamps();
            });
            // Generate model
            $stub = file_get_contents(__DIR__ . "/../../stubs/entity-model.stub");
            $entityModel = str_replace(["{{model}}", "{{table}}"], [$model, strtolower($model)], $stub);

            file_put_contents($this->laravel->basePath('app/Models/' . $model . '.php'), $entityModel);
            return true;
        } catch (\Exception $e) {
            return $this->error('DB Error: ' . $e->getMessage());
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'name' => $this->textFieldPrompt('Enter the name of the entity', '', true),
            'description' => $this->textFieldPrompt('Enter the description of the entity', '', true),
            'model' => $this->textFieldPrompt('Enter model for your entity', '', true),
            'slug' => $this->textFieldPrompt('Enter slug for your entity'),
            'icon' => $this->textFieldPrompt('Enter fontawesome icon for your entity (for example: `fa-code`)'),
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return 1;
        }

        try {
            $entity = $this->processEntry($data);
            Entities::create($entity);
            $this->generateSchema($entity['model']);
            $this->output->writeln('<fg=green>➜</> <options=bold><fg=green>SUCCESS:</> Entity created successfully</>');
        } catch (\Exception $exception) {
            $this->error('DB operation failed: ' . $exception->getMessage());
            return 1;
        }

        return 0;
    }
}
