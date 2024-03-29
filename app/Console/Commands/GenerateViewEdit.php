<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateViewEdit extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'view:edit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new edit view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * @var string
     */
    private $modelClass;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/HTML/Views/edit.stub');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $this->modelClass = $this->option('model') ?: User::class; // temporary

        $table = $this->option('table');

        $columns = collect($table->tableFields);

        $acceptFile = $columns->containsStrict('inputType', 'file');

        $replace = [
            'DummySubTitle' => str_plural(class_basename($this->modelClass)),
            'DummyPageTitle' => 'Edit ' . (class_basename($this->modelClass)),
            'DummyModelObject' => strtolower(class_basename($this->modelClass)),
            'DummyEditRoute' => "route('".strtolower(str_plural(class_basename($this->modelClass))).".update', $"
                .strtolower(class_basename($this->modelClass)).")",
            'DummyDirectoryName' => strtolower(str_plural(class_basename($this->modelClass))),
            'DummyAcceptsFiles' => $acceptFile ? "->acceptsFiles()" : ''
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = $this->option('path');

        return $path . '/resources/views/' . $this->argument('name') . '/edit.blade.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Model class'],
            ['table', 't', InputOption::VALUE_REQUIRED, 'Table columns'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
            ['path', null, InputOption::VALUE_NONE, 'Create the crud path'],
        ];
    }
}
