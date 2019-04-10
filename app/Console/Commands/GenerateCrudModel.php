<?php

namespace App\Console\Commands;

use App\Core\ModelRelationHasStubHandler;
use App\Core\ModelRelationBelongStubHandler;
use App\Core\TableSchema;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class GenerateCrudModel extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/Model.stub');
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
        $replace = $this->buildTableReplacements($replace);
        $replace = $this->buildFieldsReplacement($replace);

        return (str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        ));
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildTableReplacements(array $replace)
    {
        $table = $this->option('table');

        return array_merge($replace, [
            'DummyTable' => $table->name,
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

        $fillableFields = $columns->whereNotIn('name',
            ['password', 'email_verified_at', 'remember_token', 'updated_at', 'created_at']
        );

        $hiddenFields = $columns->whereIn('name',
            ['password', 'email_verified_at', 'remember_token']
        );

        $foreignColumns = $columns->filter(function ($value) {
            return $value->table != null;
        });

        return (array_merge($replace, [
            'DummyFillableFields' => $this->buildInputs((new TableSchema(
                $table, $fillableFields
            ))->getColumns()),
            'DummyHiddenFields' => $this->buildInputs((new TableSchema(
                $table, $hiddenFields
            ))->getColumns()),
            'DummyRelationship' => $this->buildRelationship((new TableSchema(
                $table, $foreignColumns
            ))->getColumns()),
            'DummyRelationHasMany' => $this->buildRelationshipHasMany((new TableSchema(
                $table, $columns
            ))),
        ]));
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        $html = '';

        if ($columns) {
            $columns->each(function ($column) use(&$html) {
                if($column->isPrimaryKey) {
                    return null;
                }

                $html .= "'".$column->name."', ";
            });
        }

        return $html;
    }

    protected function buildRelationship(Collection $columns)
    {
        $html = '';

        if ($columns) {
            $columns->each(function ($column) use (&$html) {
                $html .= (new ModelRelationBelongStubHandler($column))->getInput();
            });
        }

        return $html;
    }

    protected function buildRelationshipHasMany($table)
    {
        $html = [];

        if ($table) {
            $html = ($table->primaryTables ? array_map(function ($table) {
                return (new ModelRelationHasStubHandler($table))->getInput();
            }, $table->primaryTables) : []);
        }

        return (implode($html));
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
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/Models/'.str_replace('\\', '/', $name).'.php';
    }
}
