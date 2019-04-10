<?php

namespace App\Console\Commands;

use App\Core\MigrationFieldStubHandler;
use App\Core\MigrationForeignStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class GenerateCrudMigration extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/Migration.stub');
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildTableReplacements($replace);
        $replace = $this->buildFieldsReplacement($replace);

        return (str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        ));
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildTableReplacements(array $replace)
    {
        $table = $this->option('table');

        $model = str_replace('_', '', Str::title($table->name));

        return array_merge($replace, [
            'DummyTable' => $table->name,
            'DummyNameClass' => 'Create'.$model.'Table'
        ]);
    }

    /**
     * Build the view replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildFieldsReplacement(array $replace)
    {
        $table = $this->option('table');

        $columns = collect($table->tableFields);

        $foreignColumns = $columns->filter(function ($value) {
            return $value->table != null;
        });

        return (array_merge($replace, [
            'DummyFields' => $this->buildFields((new TableSchema(
                $table, $columns
            ))->getColumns()),
            'DummyForeignFields' => $this->buildForeignTable((new TableSchema(
                $table, $foreignColumns
            ))->getColumns()),
        ]));
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildFields(Collection $columns)
    {
        $table = $this->option('table');

        $html = '';

        if ($columns) {
            $columns->each(function ($column) use(&$html) {
                $html .= rtrim((new MigrationFieldStubHandler($column))->getInput()).";\n";
            });
        }

        $html .= $table->use_timestamp ? "\t\t\t".'$table->timestamps();'."\n" : '';
        $html .= $table->safe_delete ? "\t\t\t".'$table->softDeletes();'."\n" : '';

        return trim($html);
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildForeignTable(Collection $columns)
    {
        $html = '';

        if ($columns) {
            $columns->each(function ($column) use(&$html) {
                $html .= (new MigrationForeignStubHandler($column))->getInput()."\n";
            });
        }

        return rtrim($html);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['table', 't', InputOption::VALUE_REQUIRED, 'Table'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
        ]);
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $date = now()->format('Y_m_d_his');

        return '../database/migrations/'.$date.'_create_'.strtolower(str_plural($name)).'_table.php';
    }
}
