<?php

namespace App\Core;

use Illuminate\Support\Str;

class ModelRelationHasStubHandler
{
    /**
     * Column Name
     *
     * @var ColumnSchema
     */
    public $columnSchema;

    /**
     * Column constructor
     *
     * InputStubHandler constructor.
     *
     * @param $table
     */
    public function __construct(Array $table)
    {
        $this->columnSchema = $table;
    }

    /**
     * @return string|null
     */
    public function getInput()
    {
        return (str_replace(
            array_keys($this->getReplaceElements()), array_values($this->getReplaceElements()),
            $this->getStubPath()
        ));
    }

    /**
     * @return array
     */
    public function getReplaceElements()
    {
        $model = str_replace('_', '',Str::title(str_singular(array_get($this->columnSchema, 'foreign_table'))));
        $modelClass = 'App\\'.$model;

        return [
            'DUMMYFOREIGNMODELCLASS' => class_basename($modelClass),
            'DUMMYFOREIGNKEY' => array_get($this->columnSchema, 'foreign_columns'),
            'DUMMYFOREIGNMODELVARIABLE' => str_replace('_', '',array_get($this->columnSchema, 'foreign_table')),
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubPath()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Views/RelationshipHas.stub"));
    }
}
