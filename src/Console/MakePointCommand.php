<?php

namespace Ansezz\Gamify\Console;

use Ansezz\Gamify\GamifyGroup;
use Ansezz\Gamify\Point;
use Illuminate\Console\GeneratorCommand;

class MakePointCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gamify:point {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Gamify point type class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Point';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/point.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace The root namespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Gamify\Points';
    }


    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if ($this->confirm('Do you wanna create database record ?')) {

            $name = $this->ask('Point name?');
            $description = $this->ask('Point description?');
            $group = $this->ask('Point Group?');
            $point = $this->ask('Point value?');
            $allow_duplicate = false;

            if ($this->confirm('Allow duplicate ?')) {
                $allow_duplicate = true;
            }

            $group = GamifyGroup::firstOrCreate(['name' => $group, 'type' => 'point']);

            Point::create([
                'name'            => $name,
                'description'     => $description,
                'allow_duplicate' => $allow_duplicate,
                'point'           => $point,
                'gamify_group_id' => $group->id,
                'class'           => $this->qualifyClass($this->argument('name')),
            ]);
        }


        return parent::handle();
    }
}
