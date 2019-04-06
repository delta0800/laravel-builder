<?php

namespace App\Console\Commands;

use App\Table;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {table}';

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

        if (! $tableId) {
            $this->error('please provide table');
            return;
        }

        $table = Table::with('tableFields')->find($tableId);


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
        $this->call('crud:model', [
            'name' => $model,
            '--force' => $force,
            '--table' => $table,
        ]);

        $this->call('crud:data:table', [
            'name' => $model.'DataTables',
            '--model' => $modelClass,
            '--force' => $force,
            '--table' => $table,
        ]);

        $this->call('crud:request', [
            'name' => $model.'Request',
            '--table' => $table,
        ]);

        $this->call('crud:controller', [
            'name' => $model.'Controller',
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
        ]);

        $this->call('view:index', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
        ]);

        $this->call('view:form', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
        ]);

        $this->call('view:create', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
        ]);

        $this->call('view:edit', [
            'name' => $table->name,
            '--force' => $force,
            '--model' => $modelClass,
            '--table' => $table,
        ]);

        $this->call('make:policy', [
            'name' => $model.'Policy',
            '--model' => $model
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
            // ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
        ];
    }
}
