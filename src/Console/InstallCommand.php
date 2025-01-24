<?php

namespace Tfo\AdvancedLog\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class InstallCommand extends Command
{
    protected $signature = 'advanced-log:install';
    protected $description = 'Install the Advanced Logger package';
    private $sourcePath;
    private $destinationPath;
    private $backupFiles = [];

    public function __construct()
    {
        parent::__construct();
        $this->sourcePath = __DIR__ . '/../../';
        $this->destinationPath = base_path();
    }

    public function handle()
    {
        try {
            $this->info('Installing Advanced Logger...');

            if (!$this->checkRequirements()) {
                return 1;
            }

            $this->backup();

            $steps = [
                'publishConfig',
                'publishLoggers',
                'publishProvider',
                'updateEnvironmentFile',
                'publishRoutes',
            ];

            foreach ($steps as $step) {
                if (!$this->$step()) {
                    $this->rollback();
                    return 1;
                }
            }

            $this->info('Advanced Logger installed successfully.');
            $this->info('Please update your .env file with your service credentials.');
            return 0;

        } catch (\Exception $e) {
            $this->error('Installation failed: ' . $e->getMessage());
            Log::error('Advanced Logger installation failed', ['error' => $e->getMessage()]);
            $this->rollback();
            return 1;
        }
    }

    private function checkRequirements(): bool
    {
        $requirements = [
            'PHP Version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'Laravel >= 10.0' => version_compare($this->getApplication()->getVersion(), '10.0.0', '>='),
            'Write permissions' => is_writable(base_path())
        ];

        $pass = true;
        foreach ($requirements as $name => $met) {
            if (!$met) {
                $this->error("Requirement not met: $name");
                $pass = false;
            }
        }
        return $pass;
    }

    private function backup(): void
    {
        $gitignore = base_path('.gitignore');
        $backupPattern = "\n# Advanced Logger Backups\n*.backup-*\n";

        if (!str_contains(File::get($gitignore), $backupPattern)) {
            File::append($gitignore, $backupPattern);
        }

        $filesToBackup = [
            config_path('app.php'),
            base_path('.env'),
            app_path('Providers/LoggingServiceProvider.php')
        ];

        foreach ($filesToBackup as $file) {
            if (File::exists($file)) {
                $backupPath = $file . '.backup-' . time();
                File::copy($file, $backupPath);
                $this->backupFiles[] = [
                    'original' => $file,
                    'backup' => $backupPath
                ];
            }
        }
    }

    private function rollback(): void
    {
        foreach ($this->backupFiles as $backup) {
            if (File::exists($backup['backup'])) {
                File::copy($backup['backup'], $backup['original']);
                File::delete($backup['backup']);
            }
        }
    }

    private function publishConfig(): bool
    {
        try {
            $configSource = $this->sourcePath . 'config/advanced-log.php';
            $configDest = config_path('advanced-log.php');

            if (!File::exists($configSource)) {
                throw new \Exception('Config file not found');
            }

            File::copy($configSource, $configDest);
            $this->info('Config published successfully');
            return true;
        } catch (\Exception $e) {
            $this->error('Error publishing config: ' . $e->getMessage());
            return false;
        }
    }

    private function publishLoggers(): bool
    {
        try {
            $loggersPath = app_path('Loggers');
            $sourceLoggers = $this->sourcePath . 'src/Loggers';

            if (!File::exists($sourceLoggers)) {
                throw new \Exception('Logger files not found');
            }

            File::makeDirectory($loggersPath, 0755, true, true);
            File::copyDirectory($sourceLoggers, $loggersPath);
            $this->updateNamespaces($loggersPath);

            $this->info('Loggers published successfully');
            return true;
        } catch (\Exception $e) {
            $this->error('Error publishing loggers: ' . $e->getMessage());
            return false;
        }
    }

    private function publishProvider(): bool
    {
        try {
            $laravelVersion = $this->getApplication()->getVersion();

            if (version_compare($laravelVersion, '11.0', '>=')) {
                return $this->registerProviderLaravel11();
            }
            return $this->registerProviderLegacy();
        } catch (\Exception $e) {
            $this->error('Error registering provider: ' . $e->getMessage());
            return false;
        }
    }

    private function registerProviderLaravel11(): bool
    {
        $providersPath = base_path('bootstrap/providers.php');
        if (!File::exists($providersPath)) {
            throw new \Exception('providers.php not found');
        }

        $providers = require $providersPath;
        $providerClass = 'App\\Providers\\LoggingServiceProvider';

        if (!in_array($providerClass, $providers)) {
            $providers[] = $providerClass;
            $content = "<?php\n\nreturn [\n    " . implode(",\n    ", $providers) . "::class ,\n];";
            File::put($providersPath, $content);
        }

        $this->info('Service provider registered for Laravel 11');
        return true;
    }

    private function registerProviderLegacy(): bool
    {
        $configAppPath = config_path('app.php');
        $providerClass = 'App\\Providers\\LoggingServiceProvider';

        $appConfig = File::get($configAppPath);
        if (!str_contains($appConfig, $providerClass)) {
            $pattern = "/'providers' => \[/";
            $replacement = "'providers' => [\n        " . $providerClass . "::class ,";
            $appConfig = preg_replace($pattern, $replacement, $appConfig);
            File::put($configAppPath, $appConfig);
        }

        $this->info('Service provider registered for Laravel 10 or below');
        return true;
    }

    private function publishRoutes(): bool
    {
        if (App::environment('production')) {
            $this->warn('Test routes are not published in production environment');
            return true;
        }

        try {
            $sourceRoute = $this->sourcePath . 'routes/advanced-log.php';
            $destRoute = base_path('routes/advanced-log.php');

            if (!File::exists($sourceRoute)) {
                throw new \Exception('Route file not found');
            }

            File::copy($sourceRoute, $destRoute);

            $this->info('Test routes published successfully');
            $this->info('Add to RouteServiceProvider::boot():');
            $this->info('Route::middleware("web")->group(base_path("routes/advanced-log.php"));');

            return true;
        } catch (\Exception $e) {
            $this->error('Error publishing routes: ' . $e->getMessage());
            return false;
        }
    }

    private function updateEnvironmentFile(): bool
    {
        try {
            $envPath = base_path('.env');
            $envExamplePath = $this->sourcePath . '.env.example';

            if (!File::exists($envPath) || !File::exists($envExamplePath)) {
                throw new \Exception('Environment files not found');
            }

            $existing = File::get($envPath);
            $example = File::get($envExamplePath);
            $added = false;

            $lines = explode("\n", $example);
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                if (strpos($line, '=') !== false) {
                    list($key) = explode('=', $line);
                    if (!str_contains($existing, $key . '=')) {
                        File::append($envPath, "\n" . $line);
                        $added = true;
                    }
                }
            }

            if ($added) {
                $this->info('Environment variables added successfully');
            } else {
                $this->info('No new environment variables to add');
            }
            return true;
        } catch (\Exception $e) {
            $this->error('Error updating environment file: ' . $e->getMessage());
            return false;
        }
    }

    private function updateNamespaces($path): void
    {
        foreach (File::allFiles($path) as $file) {
            $content = File::get($file);
            $content = str_replace(
                'namespace Tfo\AdvancedLog',
                'namespace App',
                $content
            );
            File::put($file, $content);
        }
    }
}
