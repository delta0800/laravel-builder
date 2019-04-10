<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'project_id', 'name', 'sequence', 'use_timestamp', 'soft_delete',
    ];

    protected $casts = [
        'use_timestamp' => 'boolean',
        'soft_delete' => 'boolean',
    ];

    public function tableFields()
    {
        return $this->hasMany(TableField::class, 'table_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
