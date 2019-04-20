<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableField extends Model
{
    protected $fillable = [
        'table_id', 'project_id', 'name', 'type', 'length', 'unsigned', 'allow_null', 'key', 'default', 'extra',
        'table', 'foreign_key', 'onDelete', 'onUpdate', 'inputType', 'display_field', 'show_on', 'use_on_form',
        'label'
    ];

    protected $casts = [
        'unsigned' => 'boolean',
        'allow_null' => 'boolean',
        'show_on' => 'boolean',
        'use_on_form' =>'boolean',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
