<?php

namespace App\Core;

use App\Table;
use App\TableMany;
use Illuminate\Support\Str;

class ModelRelationManyStubHandler
{
    /**
     * Column Name
     *
     * @var ColumnSchema
     */
    public $tableMany;

    public $table;

    /**
     * Column constructor
     *
     * InputStubHandler constructor.
     *
     * @param $table
     */
    public function __construct($table)
    {
        $this->tableMany = $table;
        $this->table = Table::find($table->foreign_table);
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
        $model = str_replace('_', '',Str::title(str_singular(array_get($this->table, 'name'))));
        $modelClass = 'App\\'.$model;

        return [
            'DUMMYFOREIGNMODELCLASS' => $modelClass,
            'DUMMYFOREIGNKEY' => array_get($this->tableMany, 'foreign_key'),
            'DUMMYFOREIGNMODELVARIABLE' => str_replace('_', '',array_get($this->table, 'name')),
        ];
    }

    /**
     * @return string|bool
     */
    protected function getStubPath()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Relation/ManyToMany.stub"));
    }
}
