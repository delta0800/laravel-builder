<?php

namespace App\Console\Commands;

use App\Table;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {table} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tableId = $this->argument('table');

        $path = $this->option('path');

        if (! $tableId) {
            $this->error('please provide table');
            return;
        }

        $table = Table::with('tableFields', 'tableMany')->find($tableId);

        if (! Table::where('name', $table->name)->get()) {
            $this->error('Please provide valid table name');
            return;
        }

        $force = true;

        $model = str_replace(
            '_', '',
            Str::title(str_singular($table->name))
        );

        $modelClass = 'App\\'.$model;

        $this->call('crud:migration', [
            'name' => $model,
            '--force' => $force,
            '--table' => $table,
            '--path' => $path,
        ]);

        if (count($table->tableMany) > 0) {
            foreach ($table->tableMany as $many) {
                $foreign_table = Table::find($many->foreign_table);
                $models = str_replace('_', '', str_singular($table->name)).'_'.
                    str_replace('_', '', str_singular($foreign_table->name));

                $this->call('crud:pivot:migration', [
                    'name' => $models,
                    '--force' => $force,
                    '--table' => $many,
                    '--path' => $path,
                ]);
            }
        }

        $this->call('crud:model', [
            'name' => $model,
            '--force' => $force,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('crud:data:table', [
            'name' => $model.'DataTables',
            '--model' => $modelClass,
            '--force' => $force,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('crud:request', [
            'name' => $model.'Request',
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('crud:controller', [
            'name' => $model.'Controller',
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('view:index', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--path' => $path,
        ]);

        $this->call('view:form', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('view:create', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('view:edit', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('view:show', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
            '--path' => $path,
        ]);

        $this->call('crud:policy', [
            'name' => $model.'Policy',
            '--model' => $model,
            '--path' => $path,
        ]);

        $this->call('crud:route', [
            'name' => $model,
            '--force' => $force,
            '--model' => $modelClass,
            '--path' => $path,
        ]);
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
             ['table', InputArgument::REQUIRED, 'The table of the class'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
             ['path', null, InputOption::VALUE_NONE, 'Create the crud path'],
        ];
    }
}
