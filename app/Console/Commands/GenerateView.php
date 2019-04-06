<?php

namespace App\Console\Commands;

use App\Core\InputStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateView extends GeneratorCommand
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
    protected $description = 'Create a new index view';

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
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        $replace = $this->buildInputsReplacements($replace);

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

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
        if($this->argument('name')) {
            return array_merge($replace, [
                'DummyInputs' => $this->buildInputs((
                    new TableSchema($this->argument('name'))
                )->columns)
            ]);
        }

        return $replace;
    }

    /**
     *
     */
    protected function buildInputs($columns)
    {
        $htmlStr = '';
        $columns->map(function ($column) use(&$htmlStr) {
            $htmlStr .= (new InputStubHandler($column))->getInput();
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
        return resource_path('views/' . $this->argument('name')) . '/_form.blade.php';
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
        ];
    }
}
