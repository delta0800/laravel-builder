<?php

namespace App\Console\Commands;

use App\Core\ShowViewStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;

class GenerateViewShow extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'view:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new show view';

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
        return app_path('Core/Stub/HTML/Views/show.stub');
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
        $this->modelClass = $this->option('model');

        $table = $this->option('table');

        $columns = collect($table->tableFields);

        $replace = [
            'DummySubTitle' => str_plural(class_basename($this->modelClass)),
            'DummyPageTitle' => 'show ' . (class_basename($this->modelClass)),
            'DummyShowData' => $this->buildInputs((new TableSchema(
                $table, $columns
            ))->getColumns())
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        $model = $this->option('model');

        $htmlStr = '';
        $columns->each(function ($column) use(&$htmlStr, &$model) {
            $htmlStr .= (new ShowViewStubHandler($column, $model))
                ->getInput();
        });

        return $htmlStr;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return resource_path('views/' . $this->argument('name')) . '/show.blade.php';
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
        ];
    }
}
