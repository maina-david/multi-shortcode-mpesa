<?php

namespace MainaDavid\MultiShortcodeMpesa\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallMultiMpesaPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multi-mpesa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the Multi Shortcode Mpesa Package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Multi Shortcode Mpesa Package...');

        $this->info('Publishing configuration...');

        if (!$this->configExists('multi-shortcode-mpesa.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed Multi Shortcode Mpesa Package');
    }

    /**
     * If the file exists, return true, otherwise return false
     * 
     * @param fileName The name of the file to be created.
     * 
     * @return The return value is a boolean.
     */
    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    /**
     * If the config file already exists, ask the user if they want to overwrite it
     * 
     * @return The return value of the confirm method.
     */
    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    /**
     * It publishes the configuration file of the package to the config folder of the Laravel application
     * 
     * @param forcePublish This is a boolean value that determines whether to force publish the
     * configuration file.
     */
    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "MainaDavid\MultiShortcodeMpesa\MpesaServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}