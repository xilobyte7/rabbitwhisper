<?php

namespace FlyerAlarmDigital\RabbitWhisper;

use Illuminate\Support\ServiceProvider;

class RabbitWhisperServiceProvider extends ServiceProvider
{
    /**
     * php artisan vendor:publish --tag=rabbitwhisper
     */
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/config/amqp.php' => config_path('amqp.php'),
            __DIR__.'/config/whisper.php' => config_path('whisper.php'),
            __DIR__.'/Controllers/WhisperProcessController.php' => dir_path('app/Http/Controllers/WhisperProcessController.php'),
        ],'rabbitwhisper');

        $this->commands([
            RabbitListener::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

    }
}
