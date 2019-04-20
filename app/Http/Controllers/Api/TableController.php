<?php

namespace App\Http\Controllers\Api;

use App\DownloadRequest;
use App\Events\GenerateCurd;
use App\Package;
use App\Project;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            Table::with('tableFields', 'tableMany')
                ->where('project_id', $project->id)
                ->orderBy('sequence')
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
        //return response()->json($data);

        $tabledata = $data['table'];

        $tabledata['use_timestamp'] = request()->has('table.use_timestamp');

        $tabledata['soft_delete'] = request()->has('table.soft_delete');

        $tabledata['auth'] = request()->has('table.auth');

        $tabledata['notify'] = request()->has('table.notify');

        $fields = collect($data['fields']);

        $manyTables = collect($data['tableMany']);

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
            'fields.*.label' => ['nullable']
        ]);

        $fields->each(function ($field) use ($table) {
            $field['project_id'] = $table['project_id'];
            $table->tableFields()->create($field);
        });

        if ($data['use_many'] == true) {
            $manyTables->map(function ($many) use ($table) {
                $many['project_id'] = $table->project_id;
                $table->tableMany()->create($many);
            });
        }

        return response()->json($table->load('tableFields', 'tableMany'));
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
            Table::with('tableFields', 'tableMany')->find($tableId)
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
     * @param TableRequest $request
     * @param  \App\Table $table
     * @return \Illuminate\Http\Response
     */
    public function update(TableRequest $request, Table $table)
    {
        $data = $request->all();

        //return response()->json($data);

        $tabledata = $data['table'];

        $tabledata['use_timestamp'] = \request()->has('table.use_timestamp');
        $tabledata['soft_delete'] = \request()->has('table.soft_delete');
        $tabledata['auth'] = \request()->has('table.auth');
        $tabledata['notify'] = \request()->has('table.notify');

        $fields = collect($data['fields']);

        $manyTables = collect($data['tableMany']);

        $table->fill($tabledata)->save();

        $request->validate([
            'fields.*.name' => [
                'required',
                //'unique:table_fields,name,NULL,id,table_id,'.$table->id,
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
            'fields.*.label' => ['nullable'],
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

        if ($data['use_many'] == true) {
            $manyTables->map(function ($many) use ($table) {
                if(array_key_exists('id', $many)) {
                    $table->tableMany()->where('id', $many['id'])->update($many);
                } else {
                    $many['project_id'] = $table->project_id;
                    $table->tableMany()->create($many);
                }
            });
        }

        if ($data['deletedId']) {
            $table->tableFields()->whereIn('id', $data['deletedId'])->delete();
        }

        if ($data['deletedManyId']) {
            $table->tableMany()->whereIn('id', $data['deletedManyId'])->delete();
        }

        return response()->json($table->load('tableFields', 'tableMany'));
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generator(Request $request)
    {
        $data = $request->all();

        $packageId = $data['packageId'];

        $project = Project::where('slug', $data['slug'])->first();

        $download = DownloadRequest::where('project_id', $project->id)->latest()->first();

        $project->packages()->sync($packageId);

        $composer = collect(Package::whereIn('id', $packageId)->pluck('composer_version', 'composer'));

        $downloadRequest = DownloadRequest::create([
            'project_id' => $project->id,
            'table_id' => json_encode($data['tabId']),
            'version' => $download ? $download->version + 1 : 1,
            'composer' => json_encode($composer),
        ]);

        if ($data['generat'] == 'project') {
            File::copyDirectory('../templetes/default', storage_path('app/generated/'.$project->id.'/'.$downloadRequest->id));
        } else {
            File::makeDirectory(storage_path('app/generated/'.$project->id.'/'.$downloadRequest->id));
        }

        $webpath = storage_path('app/generated/'.$project->id.'/'.$downloadRequest->id).'/routes/web.php';

        if (! File::exists($webpath)) {
            File::makeDirectory(storage_path('app/generated/'.$project->id.'/'.$downloadRequest->id).'/routes');
            file_put_contents($webpath, "<?php\n");
        }

        event(new GenerateCurd($downloadRequest));

        return response()->json([
            'success' => true,
        ]);
    }
}
