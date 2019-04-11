<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateCrudRoute extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Route';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

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
        return app_path('Core/Stub/Route.stub');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents(
            base_path('routes/web.php'),
            $this->compileControllerStub(),
            FILE_APPEND
        );
    }

    /**
     * @return mixed|string
     */
    protected function compileControllerStub()
    {
        $this->modelClass = $this->option('model');

        $replace = [];
        $replace = $this->buildRouteReplacements($replace);

        return (str_replace(
            array_keys($replace), array_values($replace), file_get_contents($this->getStub())
        ));
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
            'DummyModelClass' => class_basename($this->modelClass),
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
