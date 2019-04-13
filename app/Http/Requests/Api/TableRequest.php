<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->request->all();

        $table = $data['table'];

        $id = request()->has('table.id') ? $table['id'] : null;

        return [
            'table.name' => [
                'required',
                'unique:tables,name,'.$id.',id,project_id,'. $table['project_id'],
            ],
            'table.sequence' => ['nullable'],
            'table.use_timestamp' => ['nullable','boolean'],
            'table.soft_delete' => ['nullable','boolean'],
        ];
    }
}
