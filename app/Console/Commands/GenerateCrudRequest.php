<?php

namespace App\Console\Commands;

use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class GenerateCrudRequest extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/Request.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildFieldsReplacement($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
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
            return $column->use_on_form == true;
        });

        $validateFields = $columns->where('allow_null', false);

        return array_merge($replace, [
            'DummyFillableFields' => $this->buildInputs((new TableSchema(
                $table, $validateFields
            ))->getColumns())
        ]);
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        if (! $columns) {
            return null;
        }
        $html = '';

        $columns->each(function ($column) use(&$html) {
            if ($column->isPrimaryKey) {
                return null;
            }

            if ($column->type == 'boolean') {
                $validation = '';
            } else {
                $validation = "'required',";
            }
            $validation .= $this->setValidation($column);

            $html .= "\n\t\t\t'".$column->name."' => [".$validation."],";
        });

        return $html;
    }

    protected function setValidation($column)
    {
        if ($column->inputType == 'email') {
            return "'email', ";
        }

        if ($column->inputType == 'file' ) {
            return "'file', ";
        }

        if ($column->inputType == 'image' ) {
            return "'image', ";
        }

        if ($column->inputType == 'date' || $column->inputType == 'dateTime') {
            return "'date', ";
        }

        if ($column->inputType == 'number') {
            return "'numeric', ";
        }

        if ($column->inputType == 'checkbox') {
            return "'boolean', ";
        }

        if ($column->table != null) {
            return "'exists:".$column->table.",".$column->foreign_key."', ";
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['table', 't', InputOption::VALUE_REQUIRED, 'Table'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
            ['path', null, InputOption::VALUE_NONE, 'Create the crud path'],
        ]);
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

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $path.'/app/'.str_replace("\\" , "/", $name).'.php';
    }
}
