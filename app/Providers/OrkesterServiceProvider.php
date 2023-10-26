<?php

namespace App\Providers;

use DI\ContainerBuilder;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Orkester\GraphQL\GraphQLConfiguration;
use Orkester\Persistence\DatabaseConfiguration;
use Orkester\Persistence\PersistenceManager;
use PDO;

class OrkesterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(GraphQLConfiguration::class, function () {
            $data = require \Illuminate\Support\Facades\App::configPath('api.php');
            return new GraphQLConfiguration(
                $data['resources'],
                $data['services'],
                Container::getInstance(),
                $this->app->hasDebugModeEnabled()
            );
        });
        PersistenceManager::init(
            $this->app->get('db'),
            Log::channel('stack')
        );

        DB::enableQueryLog();
        DB::listen(function ($query) {
            if (env('LOG_TRACE_PORT') != '0') {
                Log::channel('stack')->info(
                    $query->sql,
                    [
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]
                );
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(app_path("UI/Components"), 'wt');
        Blade::anonymousComponentPath(app_path("UI/Layouts"), 'wt');
    }
}
