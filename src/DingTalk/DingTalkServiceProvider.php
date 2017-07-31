<?php
namespace DingTalk;

use Illuminate\Support\ServiceProvider;

class DingTalkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('ding-talk.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ding-talk');

        $this->app->singleton('ding-talk', function ($app) {
            $config = $app->config->get('ding-talk');
            return new DingTalk($config);
        });
    }

    public function provides()
    {
        return ['ding_talk'];
    }    
}
