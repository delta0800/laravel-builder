<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadRequest extends Model
{
    protected $fillable = [
        'project_id', 'table_id', 'filename', 'version',
    ];

    protected $casts = [
        'table_id' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
