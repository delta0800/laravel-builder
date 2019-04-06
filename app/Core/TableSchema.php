<?php

namespace App\Core;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Illuminate\Support\Facades\DB;

class TableSchema
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $name;

    /**
     * List of columns
     *
     * @var \Illuminate\Support\Collection|null
     */
    protected $columns = null;

    /**
     * List of columns
     *
     * @var array|null
     */
    protected $foreignKeys = null;

    /**
     * List of columns
     *
     * @var array|null
     */
    protected $primaryKeys = null;

    /**
     * @var array
     */
    protected $excludeColumns = [
        'created_at',
        'updated_at',
    ];

    /**
     * Table constructor
     *
     * @param string $tableName
     * @param $columns
     */
    public function __construct($tableName, $columns)
    {
        $this->name = $tableName;

        $this->setAttributes($columns);
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * Fetch & fill all table & columns attribute
     * @param $columns
     */
    protected function setAttributes($columns)
    {
        $this->foreignKeys = collect($columns)->filter(function($column) {
            return  $column->key == 'foreign';
        });

        $this->primaryKeys = collect($columns)->filter(function($column) {
            return  $column->key == 'primary';
        });

        $this->fillColumns($columns);
    }

    /**
     * @param array $excludeColumns
     *
     * @return $this
     */
    public function setExcluedeColumns(array $excludeColumns = [])
    {
        $this->excludeColumns = $excludeColumns;

        return $this;
    }

    /**
     * Fill Columns data
     *
     * @param string|null $columns
     */
    protected function fillColumns($columns = null)
    {
        if( ! $columns) {
            // $this->getDoctrineSchemaManager()->listTableColumns($this->name);
        }

        $this->columns = collect($columns)->map(function($column) {
            return new ColumnSchema($column);
        });

        $this->foreignKeysInColumns();

        $this->primaryKeysInColumns();
    }

    /**
     * Set foreignKeys In related Columns.
     */
    protected function foreignKeysInColumns()
    {
        if($this->foreignKeys) {
            foreach ($this->foreignKeys as $foreignKey) {
                $this->columns->whereIn('name', $foreignKey->name)->each(function($value) use($foreignKey) {
                    $value->setForeignKey($foreignKey);
                });
            }
        }
    }

    /**
     * Set primaryKeys In related Columns.
     */
    protected function primaryKeysInColumns()
    {
        if($this->primaryKeys) {
            foreach ($this->primaryKeys as $primaryKey) {
                $this->columns->whereIn('name', $primaryKey->name)->each(function($value) {
                    $value->isPrimaryKey = true;
                });
            }
        }
    }

    /**
     * Get Default Doctrine Schema Manager
     *
     * @return AbstractSchemaManager
     */
    protected static function getDoctrineSchemaManager()
    {
        return DB::getDoctrineSchemaManager();
    }

    /**
     * Get attributes in array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'foreignKeys' => $this->foreignKeys ? array_map(function ($key) {
                return $this->foreignKeyToArray($key);
            }, $this->foreignKeys) : null,
            'columns' => array_map(function ($column) {
                return $column->toArray();
            }, $this->columns->toArray())
        ];
    }

    /**
     * @param ForeignKeyConstraint $foreignKey
     * @return array
     */
    protected function foreignKeyToArray($foreignKey)
    {
        return [
            'constraint_name' => $foreignKey->getName(),
            'local_table' => $foreignKey->table()->name,
            'local_columns' => $foreignKey->name,
            'foreign_table' => $foreignKey->table,
            'foreign_columns' => $foreignKey->foreign_key
        ];
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getColumns()
    {
        if (count($this->excludeColumns) < 1) {
            return $this->columns;
        }

        return $this->columns->filter(function (ColumnSchema $column) {
            return ! in_array($column->name, $this->excludeColumns);
        });
    }

    /**
     * @param $table
     *
     * @return bool
     */
    public static function isTableExist($table)
    {
        return self::getDoctrineSchemaManager()
            ->tablesExist($table);
    }
}