<?php

namespace App\Console\Commands;

use App\Core\ControllerFieldStubHandler;
use App\Core\ControllerForeignStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Collection;

class GenerateCrudController extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/Controller.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
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

        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        $replace = $this->buildModelReplacements($replace);
        $replace = $this->buildRouteReplacements($replace);
        $replace = $this->buildFieldsReplacement($replace);
        $replace = $this->buildViewReplacements($replace);

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

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
            $formRequestName = class_basename($modelClass) . 'Request';
            $dataTableName = "App\\Http\DataTables\\".class_basename($modelClass) . 'DataTables';

            return array_merge($replace, [
                'DummyFullModelClass' => $modelClass,
                'DummyModelClassVariable' => strtolower(class_basename($modelClass)),
                'DummyModelClass' => class_basename($modelClass),
                'DummyRequestName' => $formRequestName,
                'DummyFullDataTableClass' => $dataTableName,
                'DummyDataTableClass' => class_basename($dataTableName),
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
     * @param array $replace
     *
     * @return array
     */
    protected function buildViewReplacements(array $replace)
    {
        $pluralVar = strtolower(str_plural(class_basename($this->modelClass)));

        $listView = "{$pluralVar}.list";
        $actionView = "{$pluralVar}.dtAction";
        $createView = "{$pluralVar}.create";
        $showView = "{$pluralVar}.show";
        $updateView = "{$pluralVar}.update";

        return array_merge($replace, [
            'DummyIndexView' => $listView,
            'DummyActionView' => $actionView,
            'DummyCreateView' => $createView,
            'DummyShowView' => $showView,
            'DummyUpdateView' => $updateView,
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

        $columns = collect($table->tableFields);

        $filtered = $columns->whereIn('inputType', ['checkbox', 'file', 'password']);

        $foreignColumns = $columns->filter(function ($value, $key) {
            return $value->table != null;
        });

        return array_merge($replace, [
            'DummyHasFields' => $this->buildInputs((new TableSchema(
                $table->name, $filtered
            ))->getColumns()),

            'DummyForeignTables' => $this->buildForeign((new TableSchema(
                $table->name, $columns
            ))->getColumns()),

            'DummyForeignVariable' => $this->buildForeignVariable((new TableSchema(
                $table->name, $foreignColumns
            ))->getColumns()),
        ]);
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        $html = '';

        $columns->each(function ($column) use(&$html) {
            $html .= (new ControllerFieldStubHandler($column))->getInput();
        });

        return $html;
    }

    protected function buildForeign(Collection $columns)
    {
        $html = '';

        $columns->each(function ($column) use(&$html) {
            $html .= (new ControllerForeignStubHandler($column))->getInput();
        });

        return $html;
    }

    protected function buildForeignVariable(Collection $columns)
    {
        $html = '';

        $columns->each(function ($column) use(&$html) {
            $html .= "'".$column->table."', ";
        });

        return rtrim($html, ', ');
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
            ['table', 't', InputOption::VALUE_REQUIRED, 'table columns'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
        ];
    }
}
