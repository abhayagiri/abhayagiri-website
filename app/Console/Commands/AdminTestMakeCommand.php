<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class AdminTestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'app:make:admin-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin test';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TestCase';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/admin-test.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace(
            [
                'DummyModel',
                'dummy-route',
                'dummy display',
            ],
            [
                $this->getModelName(),
                $this->getRouteName(),
                $this->getDisplayName(),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getModelName();

        return $this->laravel->basePath().'/tests/Feature/Http/Controllers'.
            "/Admin/{$name}CrudControllerTest.php";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The TitleCase name of the model'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'The lowercase, plural name in the route'],
            ['display', 'd', InputOption::VALUE_OPTIONAL, 'The singular, display name'],
        ];
    }

    /**
     * Get the model name.
     *
     * @return string
     */
    protected function getModelName()
    {
        if ($name = $this->option('model')) {
            return $name;
        }

        $name = str_replace(
            ['\\', '/'],
            '',
            $this->argument('name')
        );

        return Str::singular(Str::studly($name));
    }

    /**
     * Get the route name.
     *
     * @return string
     */
    protected function getRouteName()
    {
        if ($name = $this->option('route')) {
            return $name;
        }

        return Str::plural(Str::kebab($this->getModelName()));
    }

    /**
     * Get the display name.
     *
     * @return string
     */
    protected function getDisplayName()
    {
        if ($name = $this->option('display')) {
            return $name;
        }

        return Str::singular(Str::snake($this->getModelName(), ' '));
    }
}
