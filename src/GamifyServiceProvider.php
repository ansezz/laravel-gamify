<?php

namespace Ansezz\Gamify;

use Ansezz\Gamify\Facades\Gamify;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Ansezz\Gamify\Listeners\SyncBadges;
use Illuminate\Support\ServiceProvider;
use Ansezz\Gamify\Console\MakeBadgeCommand;
use Ansezz\Gamify\Console\MakePointCommand;
use Ansezz\Gamify\Events\PointsChanged;

class GamifyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // publish config
        $this->publishes([
            __DIR__ . '/config/gamify.php' => config_path('gamify.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/config/gamify.php', 'gamify');

        $this->loadFactoriesFrom(__DIR__ . '/Factories');

        // publish migration
        if (!class_exists('CreateGamifyTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/migrations/create_gamify_tables.php.stub'         => database_path("/migrations/{$timestamp}_create_gamify_tables.php"),
            ], 'migrations');
        }

        // register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePointCommand::class,
                MakeBadgeCommand::class,
            ]);
        }

        // register event listener
        Event::listen(PointsChanged::class, SyncBadges::class);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Gamify::class, function () {
            return new Gamify();
        });
        $this->app->alias(Gamify::class, 'gamify');

        $this->app->singleton('badges', function () {
            return cache()->rememberForever('gamify.badges.all', function () {
                return $this->getBadges()->map(function ($badge) {
                    return new $badge;
                });
            });
        });
    }

    /**
     * Get all the badge inside app/Gamify/Badges folder
     *
     * @return Collection
     */
    protected function getBadges()
    {
        $badgeRootNamespace = config(
            'gamify.badge_namespace',
            $this->app->getNamespace() . 'Gamify\Badges'
        );

        $badges = [];

        foreach (glob(app_path('/Gamify/Badges/') . '*.php') as $file) {
            if (is_file($file)) {
                $badges[] = app($badgeRootNamespace . '\\' . pathinfo($file, PATHINFO_FILENAME));
            }
        }

        return collect($badges);
    }
}
