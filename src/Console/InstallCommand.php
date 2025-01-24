<?php

namespace Tfo\AdvancedLog\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'advanced-logger:install';
    protected $description = 'Install the Advanced Logger package';

    private $sourcePath;
    private $destinationPath;

    public function __construct()
    {
        parent::__construct();
        $this->sourcePath = __DIR__ . '/../../';
        $this->destinationPath = base_path();
    }

    public function handle()
    {
        $this->info('Installing Advanced Logger...');

        $this->publishConfig();
        $this->publishLoggers();
        $this->publishProvider();
        $this->updateEnvironmentFile();
        $this->publishRoutes();
        $this->registerServiceProvider();

        $this->info('Advanced Logger installed successfully.');
        $this->info('Please update your .env file with your service credentials.');
    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--tag' => 'advanced-logger-config'
        ]);
    }

    private function publishLoggers()
    {
        $loggersPath = app_path('Loggers');
        if (!File::exists($loggersPath)) {
            File::makeDirectory($loggersPath, 0755, true);
        }

        $sourceLoggers = $this->sourcePath . 'src/Loggers';
        File::copyDirectory($sourceLoggers, $loggersPath);

        // Atualizar namespaces
        $this->updateNamespaces($loggersPath);
    }

    private function publishProvider()
    {
        $providerSource = $this->sourcePath . 'src/Providers/LoggingServiceProvider.php';
        $providerDest = app_path('Providers/LoggingServiceProvider.php');

        File::copy($providerSource, $providerDest);
        $this->updateNamespace($providerDest, 'App\\Providers');
    }

    private function registerServiceProvider()
    {
        $configAppPath = config_path('app.php');
        $providerClass = 'App\\Providers\\LoggingServiceProvider::class';

        $appConfig = File::get($configAppPath);
        if (!str_contains($appConfig, $providerClass)) {
            $pattern = "/'providers' => \[/";
            $replacement = "'providers' => [\n        " . $providerClass . ",";
            $appConfig = preg_replace($pattern, $replacement, $appConfig);
            File::put($configAppPath, $appConfig);
        }
    }

    private function updateEnvironmentFile()
    {
        $envPath = base_path('.env');
        $envExamplePath = $this->sourcePath . '.env.example';

        if (File::exists($envPath)) {
            $existing = File::get($envPath);
            $example = File::get($envExamplePath);

            $lines = explode("\n", $example);
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                if (strpos($line, '=') !== false) {
                    list($key) = explode('=', $line);
                    if (strpos($existing, $key . '=') === false) {
                        File::append($envPath, "\n" . $line);
                    }
                }
            }
        }
    }

    private function publishRoutes()
    {
        $routesPath = base_path('routes/web.php');
        $routesContent = file_get_contents(__DIR__ . '/../../routes/test_routes.php');

        // Remove PHP tags e use statements se já existirem no arquivo
        $routesContent = trim(preg_replace([
            '/^<\?php\s*/i',
            '/^use.*?;[\r\n]*/m',
            '/\s*\?>$/'
        ], '', $routesContent));

        // Adiciona apenas as rotas
        file_put_contents($routesPath, "\n\n" . $routesContent, FILE_APPEND);

        $this->info('Routes published successfully.');
    }

    private function updateNamespaces($path)
    {
        $files = File::allFiles($path);
        foreach ($files as $file) {
            $content = File::get($file);
            $content = str_replace(
                'namespace Tfo\AdvancedLog',
                'namespace App',
                $content
            );
            File::put($file, $content);
        }
    }

    private function updateNamespace($file, $newNamespace)
    {
        $content = File::get($file);
        $pattern = "/namespace.*?;/";
        $replacement = "namespace " . $newNamespace . ";";
        $content = preg_replace($pattern, $replacement, $content);
        File::put($file, $content);
    }
}