<?php

namespace App\Core;

class DatatableFieldStubHandler
{
    /**
     * Column Name
     *
     * @var ColumnSchema
     */
    public $columnSchema;
    /**
     * @var array
     */
    private $ignoreField;

    /**
     * Column constructor
     *
     * InputStubHandler constructor.
     *
     * @param \App\Core\ColumnSchema $columnSchema
     * @param array $ignoreField
     */
    public function __construct(ColumnSchema $columnSchema, $ignoreField = [])
    {
        $this->columnSchema = $columnSchema;

        $this->ignoreField = array_merge([
            'password', 'email_verified_at', 'remember_token', 'update_at',
        ]);
    }

    /**
     * @return string|null
     */
    public function getInput()
    {
        if($this->columnSchema->isPrimaryKey) {
            return null;
        }

        if (in_array($this->columnSchema->name, $this->ignoreField)) {
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
            'DUMMYFIELDDATA' => $this->columnSchema->name,
            'DUMMYFIELDNAME' => $this->columnSchema->name,
            'DUMMYFIELDTITLE' => ucwords(str_replace('_', ' ', $this->columnSchema->name)),
        ];
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubHtml($path = null)
    {
        $path = $path ?: app_path('Core/Stub/HTML/Views/_field_index.stub');

        return file_get_contents($path);
    }
}
