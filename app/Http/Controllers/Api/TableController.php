<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TableRequest;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $project = Project::where('slug', $slug)->first();

        return response()->json(
            Table::with('tableFields')
                ->where('project_id', $project->id)
                ->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TableRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TableRequest $request)
    {
        $data = $request->all();

        $tabledata = $data['table'];

        $tabledata['use_timestamp'] = request()->has('table.use_timestamp');

        $tabledata['safe_delete'] = request()->has('table.safe_delete');

        $fields = collect($data['fields']);

        $table = Table::create($tabledata);

        $request->validate([
            'fields.*.name' => [
                'required',
                'unique:table_fields,name,NULL,id,table_id,'.$table->id,
            ],
            'fields.*.type' => ['required'],
            'fields.*.length' => ['nullable'],
            'fields.*.unsigned' => ['boolean'],
            'fields.*.allow_null' => ['boolean'],
            'fields.*.key' => ['nullable'],
            'fields.*.default' => ['nullable'],
            'fields.*.extra' => ['nullable'],
            'fields.*.show_on' => ['boolean'],
            'fields.*.table' => ['nullable'],
            'fields.*.foreign_key' => ['nullable'],
            'fields.*.onDelete' => ['nullable'],
            'fields.*.onUpdate' => ['nullable'],
        ]);

        $fields->each(function ($field) use ($table) {
            $field['project_id'] = $table['project_id'];
            $table->tableFields()->create($field);
        });

        return response()->json($table->load('tableFields'));
    }

    /**
     * Display the specified resource.
     *
     * @param $tableId
     * @return \Illuminate\Http\Response
     */
    public function show($tableId)
    {
        return response()->json(
            Table::with('tableFields')->find($tableId)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $data = $request->all();

        // return response()->json($data);

        $tabledata = $data['table'];

        $tabledata['use_timestamp'] = request()->has('table.use_timestamp');

        $tabledata['safe_delete'] = request()->has('table.safe_delete');

        $fields = collect($data['fields']);

        $deletedId = $data['deletedId'];

        $table->fill($tabledata)->save();

        $request->validate([
            'fields.*.name' => [
                'required',
                 Rule::unique('users')->ignore('id')
            ],
            'fields.*.type' => ['required'],
            'fields.*.length' => ['nullable'],
            'fields.*.unsigned' => ['boolean'],
            'fields.*.allow_null' => ['boolean'],
            'fields.*.key' => ['nullable'],
            'fields.*.default' => ['nullable'],
            'fields.*.extra' => ['nullable'],
            'fields.*.show_on' => ['boolean'],
            'fields.*.table' => ['nullable'],
            'fields.*.foreign_key' => ['nullable'],
            'fields.*.onDelete' => ['nullable'],
            'fields.*.onUpdate' => ['nullable'],
        ]);

        $fields->map(function ($field) use ($table) {
            if(array_key_exists('id', $field)) {
                $table->tableFields()
                    ->where('id', $field['id'])
                    ->update($field);
            } else {
                $field['project_id'] = $table['project_id'];
                $table->tableFields()->create($field);
            }

        });

        if($deletedId) {
            $table->tableFields()->whereIn('id', $deletedId)->delete();
        }

        return response()->json($table->load('tableFields'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Table $table
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function generator(Request $request)
    {
        $tableId = $request['tableId'];

        foreach ($tableId as $id) {
            Artisan::call('generate:crud', [
                'table' => $id,
            ]);
        }

        $tables = Table::whereIn('id', $tableId)->get();

        Artisan::call('generate:sidebar', [
            'name' => 'nav',
            '--tables' => $tables
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
