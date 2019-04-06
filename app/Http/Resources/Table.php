<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Table extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'project_id' => $this->project_id,
            'name' => $this->name,
            'sequence' => $this->sequence,
            'table_fields' => TableField::collection($this->table_fields),
        ];
    }
}
