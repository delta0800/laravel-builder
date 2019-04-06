<?php

namespace App\Core;

use Illuminate\Support\Str;

class ControllerRelationshipStubHandler
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
     * @param \App\Core\ColumnSchema $columnSchema
     */
    public function __construct(ColumnSchema $columnSchema)
    {
        $this->columnSchema = $columnSchema;
    }

    /**
     * @return string|null
     */
    public function getInput()
    {
        if($this->columnSchema->isPrimaryKey) {
            return null;
        }

        return str_replace(
            array_keys($this->getReplaceElements()), array_values($this->getReplaceElements()),
            $this->getStubPath()
        );
    }

    /**
     * @return array
     */
    public function getReplaceElements()
    {
        $model = str_replace('_', '',Str::title(str_singular($this->columnSchema->table)));
        $modelClass = 'App\\'.$model;

        return [
            'DUMMYFOREIGNMODELCLASS' => class_basename($modelClass),
            'DUMMYFOREIGNKEY' => $this->columnSchema->name,
            'DUMMYFOREIGNMODELVARIABLE' => strtolower($model),
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubPath()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Views/Relationship.stub"));
    }
}
