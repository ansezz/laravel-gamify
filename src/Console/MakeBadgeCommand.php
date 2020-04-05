<?php

namespace Ansezz\Gamify\Console;

use Ansezz\Gamify\Badge;
use Ansezz\Gamify\GamifyGroup;
use Illuminate\Console\GeneratorCommand;

class MakeBadgeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gamify:badge {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Gamify badge class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Badge';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/badge.stub';
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
        return $rootNamespace . '\Gamify\Badges';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        // clear the cache for badges
        cache()->forget('gamify.badges.all');

        if ($this->confirm('Do you wanna create database record ?')) {
            $name = $this->ask('Badge name?');
            $description = $this->ask('Badge description?');
            $group = $this->ask('Badge Group?');
            $level = $this->choice('Badge Level?', config('gamify.badge_levels'));

            $group = GamifyGroup::firstOrCreate(['name' => $group, 'type' => 'badge']);

            Badge::create([
                'name'            => $name,
                'description'     => $description,
                'gamify_group_id' => $group->id,
                'level'           => config('gamify.badge_levels')[$level],
                'class'           => $this->qualifyClass($this->argument('name')),
            ]);
        }

        return parent::handle();
    }


}
