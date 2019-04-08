<?php

namespace App\Console\Commands;

use App\Core\DatatableFieldStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;

class GenerateCrudDataTable extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:data:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new data table class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $modelClass = null;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/DataTable.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\DataTables';
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $this->modelClass = $this->option('model');

        $replace = [];
        $replace = $this->buildModelReplacements($replace);
        $replace = $this->buildRouteReplacements($replace);
        $replace = $this->buildFieldsReplacement($replace);
        $replace = $this->buildViewReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        if($this->modelClass) {
            $modelClass = $this->modelClass;

            return array_merge($replace, [
                'DummyFullModelClass' => $modelClass,
                'DummyModelClassVariable' => strtolower(class_basename($modelClass)),
                'DummyModelClass' => class_basename($modelClass),
            ]);
        }

        return $replace;
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildRouteReplacements(array $replace)
    {
        $routeName = strtolower(str_plural(class_basename($this->modelClass)));

        return array_merge($replace, [
            'DummyRouteName' => $routeName,
        ]);
    }

    /**
     * Build the view replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildFieldsReplacement(array $replace)
    {
        $table = $this->option('table');

        $columns = collect($table->tableFields)->filter(function ($column) {
            return $column->show_on == true;
        });

        return array_merge($replace, [
            'DummyFields' => $this->buildInputs((new TableSchema(
                $table->name, $columns
            ))->getColumns())
        ]);
    }

    /**
     * @param array $replace
     *
     * @return array
     */
    protected function buildViewReplacements(array $replace)
    {
        $pluralVar = strtolower(str_plural(class_basename($this->modelClass)));

        return array_merge($replace, [
            'DummyActionView' => "{$pluralVar}.dtAction",
        ]);
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        if (!$columns) {
            return null;
        }

        $htmlStr = '[
        ';
        $columns->each(function ($column) use(&$htmlStr) {
            $htmlStr .= (new DatatableFieldStubHandler($column))
                ->getInput();
        });

        return $htmlStr.'
        ]';
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
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
            ['table', null, InputOption::VALUE_NONE, 'table columns']
        ];
    }
}
