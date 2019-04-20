<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'tables';

    protected $fillable = [
        'project_id', 'name', 'sequence', 'use_timestamp', 'soft_delete', 'auth', 'notify', 'label', 'icon',
    ];

    protected $casts = [
        'use_timestamp' => 'boolean',
        'soft_delete' => 'boolean',
        'auth' => 'boolean',
        'notify' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tableFields()
    {
        return $this->hasMany(TableField::class, 'table_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tableMany()
    {
        return $this->hasMany(TableMany::class, 'table_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
