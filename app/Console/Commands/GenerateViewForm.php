<?php

namespace App\Console\Commands;

use App\Core\InputStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;

class GenerateViewForm extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'view:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/HTML/Views/_form.stub');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $routeName = strtolower(str_plural(class_basename($name)));
        $replace['DummyReturnUrl'] = "route('".$routeName.".index')";
        $replace = $this->buildInputsReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the view replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildInputsReplacements(array $replace)
    {
        $table = $this->option('table');

        $columns = collect($table->tableFields)->filter(function ($column) {
            return $column->use_on_form == true;
        });

        if($this->argument('name')) {
            return array_merge($replace, [
                'DummyInputs' => $this->buildInputs((new TableSchema(
                    $table, $columns))->getColumns()
                )
            ]);
        }

        return $replace;
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    protected function buildInputs(Collection $columns)
    {
        $htmlStr = '';
        $columns->each(function ($column) use(&$htmlStr) {
            $htmlStr .= (new InputStubHandler($column))
                ->getInput();
        });

        return $htmlStr;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = $this->option('path');

        return $path . '/resources/views/' . $this->argument('name') . '/inc/_form.blade.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Model class'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
            ['table', 't', InputOption::VALUE_REQUIRED, 'Table columns'],
            ['path', null, InputOption::VALUE_NONE, 'Create the crud path'],
        ];
    }
}
