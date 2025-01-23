<?php

namespace VendorName\PackageName\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'advanced-logger:install';
    protected $description = 'Install the Advanced Logger package';

    public function handle()
    {
        $this->info('Installing Advanced Logger...');

        $this->call('vendor:publish', [
            '--tag' => 'advanced-logger-config'
        ]);

        $this->updateEnvironmentFile();

        $this->publishRoutes();

        $this->info('Advanced Logger installed successfully.');
        $this->info('Please update your .env file with your service credentials.');
    }

    private function updateEnvironmentFile()
    {
        $envPath = base_path('.env');
        $envExamplePath = __DIR__ . '/../.env.example';

        if (file_exists($envPath)) {
            $existing = file_get_contents($envPath);
            $example = file_get_contents($envExamplePath);

            // Adiciona apenas variáveis que não existem
            $lines = explode("\n", $example);
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                if (strpos($line, '=') !== false) {
                    list($key) = explode('=', $line);
                    if (strpos($existing, $key . '=') === false) {
                        file_put_contents($envPath, "\n" . $line, FILE_APPEND);
                    }
                }
            }
        }
    }

    private function publishRoutes()
    {
        $routesPath = base_path('routes/web.php');
        $routesContent = File::get(__DIR__ . '/../../routes/test_routes.php');

        File::append($routesPath, $routesContent);

        $this->info('Routes published successfully.');
    }
}