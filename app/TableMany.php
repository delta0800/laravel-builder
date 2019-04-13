<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableMany extends Model
{
    protected $table = 'table_many';

    protected $fillable = [
        'project_id', 'table_id', 'table_key', 'foreign_table', 'foreign_key',
    ];
}
