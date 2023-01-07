<?php

namespace MainaDavid\MultiShortcodeMpesa;

use David\MultiShortcodeMpesa\Console\Commands\InstallMultiMpesaPackage;
use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->commands([
                InstallMultiMpesaPackage::class,
            ]);
        }
    }

    /**
     * It publishes the config file and the migrations to the appropriate directories
     */
    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/multi-shortcode-mpesa.php' => config_path('multi-shortcode-mpesa.php')
        ], 'multi-shortcode-mpesa-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'multi-shortcode-mpesa-migrations');
    }
}