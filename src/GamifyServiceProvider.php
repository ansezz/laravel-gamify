<?php

namespace Ansezz\Gamify;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Ansezz\Gamify\Listeners\SyncBadges;
use Illuminate\Support\ServiceProvider;
use Ansezz\Gamify\Console\MakeBadgeCommand;
use Ansezz\Gamify\Console\MakePointCommand;
use Ansezz\Gamify\Events\ReputationChanged;

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

        // publish migration
        if (!class_exists('CreateGamifyTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/migrations/create_gamify_tables.php.stub' => database_path("/migrations/{$timestamp}_create_gamify_tables.php"),
                __DIR__ . '/migrations/add_reputation_on_user_table.php.stub' => database_path("/migrations/{$timestamp}_add_reputation_field_on_user_table.php"),
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
        Event::listen(ReputationChanged::class, SyncBadges::class);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
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
