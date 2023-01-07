<?php

namespace MainaDavid\MultiShortcodeMpesa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

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

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}