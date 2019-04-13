<?php

namespace App\Console\Commands;

use App\Table;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class GenerateCrudPivotMigration extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:pivot:migration';

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

        $primaryTable = Table::find($table->table_id);

        $foreignTable = Table::find($table->foreign_table);

        $model = str_replace('_', '', Str::title($primaryTable->name)).str_replace('_', '', Str::title($foreignTable->name));

        return array_merge($replace, [
            'DummyTable' => $this->argument('name'),
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
        $table = collect($this->option('table'));

        return (array_merge($replace, [
            'DummyFields' => $this->buildFields($table),
            'DummyForeignFields' => $this->buildForeignTable($table),
        ]));
    }

    /**
     * @param $table
     * @return string
     */
    protected function buildFields($table)
    {
        $html = '';
        if ($table) {
            $primaryTable = Table::find($table['table_id']);
            $foreignTable = Table::find($table['foreign_table']);

            $primaryField = (str_replace('_', '',str_singular($primaryTable->name))).'_'.$table['table_key'];

            $foreignField = (str_replace('_', '',str_singular($foreignTable->name))).'_'.$table['foreign_key'];

            $html .= '$table->increments'."('id');"."\n";
            $html .= "\t\t\t".'$table->unsignedInteger('."'".$primaryField."');\n";
            $html .= "\t\t\t".'$table->unsignedInteger('."'".$foreignField."');\n";
            $html .= "\t\t\t".'$table->timestamps();'."\n";
        }

        return $html;
    }

    /**
     * @param $table
     * @return string
     */
    protected function buildForeignTable($table)
    {
        $html = '';

        if ($table) {
            $primaryTable = Table::find($table['table_id']);
            $foreignTable = Table::find($table['foreign_table']);

            $primaryKey = $table['table_key'];

            $foreignKey = $table['foreign_key'];

            $primaryField = (str_replace('_', '',str_singular($primaryTable->name))).'_'.$primaryKey;

            $foreignField = (str_replace('_', '',str_singular($foreignTable->name))).'_'.$foreignKey;

            $html .= '$table->foreign('."'".$primaryField."')
                   ->references('$primaryKey')->on('$primaryTable->name')
                   ->onDelete('cascade');\n";

            $html .= "\n\t\t\t".'$table->foreign('."'".$foreignField."')
                   ->references('$foreignKey')->on('$foreignTable->name')
                   ->onDelete('cascade');\n";
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
