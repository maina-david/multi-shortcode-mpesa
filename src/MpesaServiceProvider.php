<?php

namespace MainaDavid\MultiShortcodeMpesa;

use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();
    }

    /**
     * It publishes the config file and the migrations to the appropriate directories
     */
    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/multi-shortcode-mpesa.php' => config_path('multi-shortcode-mpesa.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_short_codes_table.stub' => $this->getMigrationFileName('create_short_codes_table.php'),
        ], 'migrations');
    }
}