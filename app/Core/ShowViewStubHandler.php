<?php

namespace App\Core;

class ShowViewStubHandler
{
    /**
     * Column Name
     *
     * @var ColumnSchema
     */
    public $columnSchema;

    public $model;

    /**
     * Column constructor
     *
     * InputStubHandler constructor.
     *
     * @param \App\Core\ColumnSchema $columnSchema
     * @param $model
     */
    public function __construct(ColumnSchema $columnSchema, $model)
    {
        $this->columnSchema = $columnSchema;
        $this->model = $model;
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
            $this->getStubHtml()
        );
    }

    /**
     * @return array
     */
    public function getReplaceElements()
    {
        return [
            'InputName' => $this->columnSchema->name,
            'Placeholder' => $this->columnSchema->label,
            'ModelVariable' => strtolower(class_basename($this->model)),
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubHtml()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Show/_showView.stub"));
    }
}
