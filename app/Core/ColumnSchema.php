<?php

namespace App\Core;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Types\Type;

class ColumnSchema
{
    /**
     * Column Name
     *
     * @var string
     */
    public $name;

    /**
     * Column type
     *
     * @var Type
     */
    public $type;

    /**
     * Default values
     *
     * @var mixed
     */
    public $default;

    /**
     * Is not null
     *
     * @var boolean
     */
    public $notnull;

    /**
     * Column type
     *
     * @var integer|null
     */
    public $length;

    /**
     * Column Precision
     *
     * @var integer|null
     */
    public $precision;

    /**
     * Column Scale
     *
     * @var integer|null
     */
    public $scale;

    /**
     * Is column fixed
     *
     * @var boolean
     */
    public $fixed;

    /**
     * Is column unsigned
     *
     * @var boolean
     */
    public $unsigned;

    /**
     * Is column autoincrement
     *
     * @var boolean
     */
    public $autoincrement;

    /**
     * column definition
     *
     * @var string|null
     */
    public $columnDefinition;

    /**
     * Column inputType
     *
     * @var Type
     */
    public $inputType;

    /**
     * Column Table
     *
     * @var string
     */
    public $table;

    /**
     * Column type
     *
     * @var integer|null
     */
    public $display_field;

    /**
     * Is Column is primary key
     *
     * @var boolean
     */
    public $isPrimaryKey = false;

    /**
     * Is Column is primary key
     *
     * @var boolean
     */
    public $isForeignKey = false;

    /**
     * ForeignKeyConstraint
     *
     * @var ForeignKeyConstraint|null
     */
    public $foreign_key = null;

    /**
     * Column constructor
     *
     * @param $attribute
     */
    public function __construct($attribute)
    {
        $this->name = $attribute->name;
        $this->type = $attribute->type;
        $this->length = $attribute->length;
        $this->default = $attribute->default;
        $this->notnull = $attribute->allow_null;
        $this->unsigned = $attribute->unsigned;
        $this->autoincrement = $attribute->extra ? true : false ;
        $this->inputType = $attribute->inputType;
        $this->table = $attribute->table;
        $this->display_field = $attribute->display_field;

    }

    /**
     * Set ForeignKey for the column
     *
     * @param $foreignKey
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreign_key = $foreignKey->foreign_key;
        $this->isForeignKey = true;
    }

    /**
     * Set ForeignKey for the column
     *
     * @param $foreignKey
     */
    public function setPrimaryTable($primaryKey)
    {
        $this->foreign_key = $primaryKey->foreign_key;
        $this->isPrimaryKey = true;
    }

    /**
     * Get attributes in array
     *
     * @return array
     */
    public function toArray()
    {
        $obj = get_object_vars($this);
        $obj['type'] = $obj['type']->getName();
        $obj['foreign_key'] = $this->foreignKeyToArray();

        return $obj;
    }

    /**
     * @return array|null
     */
    protected function foreignKeyToArray()
    {
        if (! $this->foreign_key) {
            return null;
        }

        return [
            'constraint_name' => $this->foreign_key->getName(),
            'local_table' => $this->table()->name,
            'local_columns' => $this->name,
            'foreign_table' => $this->table,
            'foreign_columns' => $this->foreign_key
        ];
    }
}