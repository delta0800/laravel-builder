<?php

namespace App\Core;

class InputStubHandler
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

        if($this->columnSchema->isForeignKey || $this->columnSchema->inputType == "select") {
            $stub = $this->getSelectStub();
        } elseif ($this->columnSchema->inputType == "checkbox") {
            $stub = $this->getCheckboxStub();
        } elseif ($this->columnSchema->inputType == "password") {
            $stub = $this->getPasswordStub();
        } elseif ($this->columnSchema->inputType == "email") {
            $stub = $this->getEmailStub();
        } elseif ($this->columnSchema->inputType == "textarea") {
            $stub = $this->getTextareaStub();
        } elseif ($this->columnSchema->inputType == "date") {
            $stub = $this->getDateStub();
        } elseif ($this->columnSchema->inputType == "dateTime") {
            $stub = $this->getDateTimeStub();
        } elseif ($this->columnSchema->inputType == "file") {
            $stub = $this->getFileStub();
        } elseif ($this->columnSchema->inputType == "rich-textbox") {
            $stub = $this->getRichTextBoxStub();
        } else {
            $stub = $this->getTextStub();
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
            'InputName' => $this->columnSchema->name,
            'Placeholder' => $this->columnSchema->label,
            'Option' => $this->columnSchema->table
        ];
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public function getStubPath($template)
    {
        return app_path("Core/Stub/HTML/Form/{$template}.stub");
    }

    /**
     * @return string|bool
     */
    public function getTextStub()
    {
        return $this->getStubHtml($this->getStubPath('input'));
    }

    /**
     * @return string|bool
     */
    public function getSelectStub()
    {
        return $this->getStubHtml($this->getStubPath('select'));
    }

    /**
     * @return string|bool
     */
    public function getPasswordStub()
    {
        return $this->getStubHtml($this->getStubPath('password'));
    }

    /**
     * @return string|bool
     */
    public function getCheckboxStub()
    {
        return $this->getStubHtml($this->getStubPath('checkbox'));
    }

    /**
     * @return string|bool
     */
    public function getTextareaStub()
    {
        return $this->getStubHtml($this->getStubPath('textarea'));
    }

    /**
     * @return string|bool
     */
    public function getEmailStub()
    {
        return $this->getStubHtml($this->getStubPath('email'));
    }

    /**
     * @return string|bool
     */
    public function getDateStub()
    {
        return $this->getStubHtml($this->getStubPath('date'));
    }

    /**
     * @return string|bool
     */
    public function getDateTimeStub()
    {
        return $this->getStubHtml($this->getStubPath('dateTime'));
    }

    /**
     * @return string|bool
     */
    public function getFileStub()
    {
        return $this->getStubHtml($this->getStubPath('file'));
    }

    /**
     * @return string|bool
     */
    public function getRichTextBoxStub()
    {
        return $this->getStubHtml($this->getStubPath('rich-textbox'));
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
