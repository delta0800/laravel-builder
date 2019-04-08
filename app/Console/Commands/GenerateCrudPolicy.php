<?php

namespace App\Console\Commands;

use App\Core\ControllerFieldStubHandler;
use App\Core\ControllerForeignStubHandler;
use App\Core\TableSchema;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Collection;

class GenerateCrudPolicy extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Policy';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Core/Stub/Policy.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Policies';
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $this->modelClass = $this->option('model');

        $replace = [];

        $replace = $this->buildModelReplacements($replace);
        $replace = $this->buildRouteReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        if($this->modelClass) {
            $modelClass = $this->modelClass;

            return array_merge($replace, [
                'DummyFullModelClass' => $modelClass,
                'DummyModelClassVariable' => strtolower(class_basename($modelClass)),
                'DummyModelClass' => class_basename($modelClass),
                'DummyModelUse' => "use App\\". $modelClass,
            ]);
        }

        return $replace;
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildRouteReplacements(array $replace)
    {
        $routeName = strtolower(str_plural(class_basename($this->modelClass)));

        return array_merge($replace, [
            'DummyRouteName' => $routeName,
        ]);
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
