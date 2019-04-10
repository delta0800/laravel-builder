<?php

namespace App\Console\Commands;

use App\Core\SidebarNavStubHandler;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateSidebar extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'generate:sidebar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Sidebar';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Sidebar';

    /**
     * @var array
     */
    private $tables = [];

    /**
     * @return string|bool
     */
    protected function getStub()
    {
        return app_path("Core/Stub/SidebarNav.stub");
    }

    protected function buildClass($name)
    {
        $this->tables = $this->option('tables');

        if (! $this->tables) {
            $this->error('please provide table');
            return;
        }

        $replace = [];

        $replace = $this->buildModelReplacements($replace);

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
    protected function buildModelReplacements(array $replace)
    {
        if($this->tables) {
            $tables = $this->tables;

            return array_merge($replace, [
                'DummyHasFields' => $this->buildInputs($tables),
            ]);
        }

        return $replace;
    }

    protected function buildInputs($tables)
    {
        $html = '';

        $tables->each(function ($table) use(&$html) {
            $html .= (new SidebarNavStubHandler($table))->getInput();
        });

        return $html;
    }

    /**
     * Get the destination class path.
     *
     * @param $name
     * @return string
     */
    protected function getPath($name)
    {
        return resource_path('views/layouts/partials/' . $this->argument('name')) . '.blade.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['tables', null, InputOption::VALUE_NONE, 'table columns'],
            ['force', null, InputOption::VALUE_NONE, 'Create the crud if already exists'],
        ];
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the view'],
        ];
    }
}
