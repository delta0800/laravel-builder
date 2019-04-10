<?php

namespace App\Core;

class MigrationForeignStubHandler
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
        return [
            'DUMMYFIELDNAME' => $this->columnSchema->name,
            'DUMMYFOREIGNTABLE' => $this->columnSchema->table,
            'DUMMYONDELETE' => $this->columnSchema->onDelete,
            'DUMMYONUPDATE' => $this->columnSchema->onUpdate,
        ];
    }

    /**
     * @return string|bool
     */
    protected function getStubHtml()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Show/_migrateForeign.stub"));
    }

}
