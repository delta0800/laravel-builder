<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadRequest extends Model
{
    protected $fillable = [
        'project_id', 'table_id', 'filename', 'version', 'composer'
    ];

    protected $casts = [
        'table_id' => 'array',
        'composer' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
