<?php

namespace App\Http\Controllers\Api;

use App\TableField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TableFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TableField  $tableField
     * @return \Illuminate\Http\Response
     */
    public function show(TableField $tableField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TableField  $tableField
     * @return \Illuminate\Http\Response
     */
    public function edit(TableField $tableField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TableField  $tableField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TableField $tableField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TableField $tableField
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(TableField $tableField)
    {
        $tableField->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
