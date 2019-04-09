<?php

namespace App\Core;

use Illuminate\Support\Str;

class SidebarNavStubHandler
{
    /**
     * Column Name
     *
     * @var ColumnSchema
     */
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
        $this->table = $table;
    }

    /**
     * @return string|null
     */
    public function getInput()
    {
        if(!$this->table) {
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
        $model = str_replace(
            '_', '',
            Str::title(str_plural($this->table->name))
        );

        return [
            'DummyModelVariable' => strtolower(class_basename($model)),
            'DummyRouteName' => class_basename($model),
        ];
    }


    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function getStubHtml()
    {
        return file_get_contents(app_path("Core/Stub/HTML/Show/_sideNav.stub"));
    }

}
