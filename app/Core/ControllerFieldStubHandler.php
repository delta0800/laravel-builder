<?php

namespace App\Core;

class ControllerFieldStubHandler
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

        if($this->columnSchema->inputType == "checkbox") {
            $stub = $this->getCheckboxStub();
        } elseif ($this->columnSchema->inputType == "password") {
            $stub = $this->getPasswordStub();
        } elseif ($this->columnSchema->inputType == "file") {
            $stub = $this->getFileStub();
        } else {
            $stub = null;
        }

        return str_replace(
            array_keys($this->getReplaceElements()), array_values($this->getReplaceElements()),
            $stub
        );
    }

    /**
     * @return array
     */
    public function getReplaceElements()
    {
        return [
            'DUMMYFIELDNAME' => $this->columnSchema->name,
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubPath($path = null)
    {
        return app_path("Core/Stub/HTML/Views/{$path}.stub");
    }

    /**
     * @return string|bool
     */
    public function getPasswordStub()
    {
        return $this->getStubHtml($this->getStubPath('_password'));
    }

    /**
     * @return string|bool
     */
    public function getFileStub()
    {
        return $this->getStubHtml($this->getStubPath('_file'));
    }

    /**
     * @return string|bool
     */
    public function getCheckBoxStub()
    {
        return $this->getStubHtml($this->getStubPath('_checkbox'));
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubHtml($path)
    {
        return file_get_contents($path);
    }

}
