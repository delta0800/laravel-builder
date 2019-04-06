<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'project_id', 'name', 'sequence', 'use_timestamp', 'safe_delete',
    ];

    protected $casts = [
        'use_timestamp' => 'boolean',
        'safe_delete' => 'boolean',
    ];

    public function tableFields()
    {
        return $this->hasMany(TableField::class, 'table_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
