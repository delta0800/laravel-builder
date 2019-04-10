<?php

namespace App\Core;

class MigrationFieldStubHandler
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
        if(! $this->columnSchema) {
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
        if ($this->columnSchema->isForeignKey && $this->columnSchema->unsigned) {
            $type = 'unsignedInteger';
        } else if($this->columnSchema->autoincrement) {
            $type = 'increments';
        } else {
            $type = $this->columnSchema->type;
        }

        $length = '';
        if ($this->columnSchema->type == 'string' || $this->columnSchema->type == 'text') {
            $length = $this->columnSchema->length ? $this->columnSchema->length == '255' ? '': ', '.$this->columnSchema->length : '';
        }

        return [
            'DUMMYFIELDNAME' => $this->columnSchema->name,
            'DUMMYFIELDLENGTH' => $length,
            'DUMMYFIELDTYPE' => $type,
            'DUMMYDEFAULTFIELD' => $this->columnSchema->default == '' ? '' : '->default('.$this->columnSchema->default.')',
            'DUMMYNULLABLEFIELD' => $this->columnSchema->notnull == true ? '->nullable()' : ''
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubHtml()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Show/_migrateField.stub"));
    }

}
