<?php

namespace App\Core;

use Illuminate\Support\Str;

class ControllerForeignStubHandler
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
        $modelClass = 'App\\'.str_replace('_', '',Str::title(str_singular($this->columnSchema->table)));

        return [
            'DUMMYFOREIGNMODELCLASS' => class_basename($modelClass),
            'DUMMYDISPLAYFIELD' => $this->columnSchema->display_field,
            'DUMMYFOREIGNKEY' => $this->columnSchema->foreign_key,
            'DUMMYFOREIGNMODELVARIABLE' => $this->columnSchema->table,
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubPath()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Views/foreign.stub"));
    }
}
