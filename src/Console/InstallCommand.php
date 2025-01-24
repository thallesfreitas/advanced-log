<?php

namespace Tfo\AdvancedLog\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

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
        try {
            $this->info('Installing Advanced Logger...');

            $this->publishConfig();
            $this->publishLoggers();
            $this->publishProvider();
            $this->updateEnvironmentFile();
            $this->publishRoutes();
            $this->registerServiceProvider();

            $this->info('Advanced Logger installed successfully.');
            $this->info('Please update your .env file with your service credentials.');
        } catch (\Exception $e) {
            $this->error('Installation failed: ' . $e->getMessage());
        }
    }

    private function publishConfig()
    {
        $this->call('vendor:publish', ['--tag' => 'laravel-assets']);
    }

    private function publishLoggers()
    {
        try {
            $loggersPath = app_path('Loggers');
            if (!File::exists($loggersPath)) {
                File::makeDirectory($loggersPath, 0755, true);
            }

            $sourceLoggers = $this->sourcePath . 'src/Loggers';
            File::copyDirectory($sourceLoggers, $loggersPath);
            $this->updateNamespaces($loggersPath);

            $this->info('Loggers published successfully.');
        } catch (\Exception $e) {
            throw new \Exception('Error publishing loggers: ' . $e->getMessage());
        }
    }

    private function publishProvider()
    {
        try {
            $providerSource = $this->sourcePath . 'src/Providers/LoggingServiceProvider.php';
            $providerDest = app_path('Providers/LoggingServiceProvider.php');

            File::copy($providerSource, $providerDest);
            $this->updateNamespace($providerDest, 'App\\Providers');

            $this->info('Provider published successfully.');
        } catch (\Exception $e) {
            throw new \Exception('Error publishing provider: ' . $e->getMessage());
        }
    }

    private function registerServiceProvider()
    {
        try {
            $configAppPath = config_path('app.php');
            $providerClass = 'App\\Providers\\LoggingServiceProvider::class';

            $appConfig = File::get($configAppPath);
            if (!str_contains($appConfig, $providerClass)) {
                $pattern = "/'providers' => \[/";
                $replacement = "'providers' => [\n        " . $providerClass . ",";
                $appConfig = preg_replace($pattern, $replacement, $appConfig);
                File::put($configAppPath, $appConfig);
            }

            $this->info('Service provider registered successfully.');
        } catch (\Exception $e) {
            throw new \Exception('Error registering service provider: ' . $e->getMessage());
        }
    }

    private function updateEnvironmentFile()
    {
        try {
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

                $this->info('Environment variables added successfully.');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error updating environment file: ' . $e->getMessage());
        }
    }

    private function publishRoutes()
    {
        if (App::environment('production')) {
            $this->warn('Test routes are not published in production environment.');
            return;
        }

        try {
            $routesDir = base_path('routes');
            $testRoutesPath = $routesDir . '/routes/test_routes.php';
            $webRoutesPath = $routesDir . '/web.php';

            // Adicionar require no web.php se não existir
            $requireLine = "\nrequire __DIR__.'/routes/test_routes.php';";
            if (!File::exists($webRoutesPath)) {
                throw new \Exception('web.php not found');
            }

            if (!str_contains(File::get($webRoutesPath), $requireLine)) {
                File::append($webRoutesPath, $requireLine);
            }

            $routesContent = <<<'EOT'

use Illuminate\Support\Facades\Route;
use App\Support\ALog;

// Test Routes for Advanced Logger
Route::prefix('test-logs')->middleware(['web'])->group(function () {
   Route::get('/performance', function () {
       $startTime = microtime(true);
       sleep(1);
       $duration = (microtime(true) - $startTime) * 1000;
       ALog::performance('Test Operation', $duration);
       return 'Performance log tested';
   });

   Route::get('/audit', function () {
       ALog::audit('update', 'User', 1, [
           'name' => ['old' => 'John', 'new' => 'Johnny'],
           'email' => ['old' => 'john@example.com', 'new' => 'johnny@example.com']
       ]);
       return 'Audit log tested';
   });

   Route::get('/security', function () {
       ALog::security('Login Failed', [
           'email' => 'user@example.com',
           'attempts' => 3
       ]);
       return 'Security log tested';
   });

   Route::get('/api', function () {
       $response = response()->json(['status' => 'success']);
       ALog::api('/api/users', 'GET', $response, 150.5);
       return 'API log tested';
   });

   Route::get('/database', function () {
       ALog::database('create', 'users', 1, [
           'data' => ['name' => 'John', 'email' => 'john@example.com']
       ]);
       return 'Database log tested';
   });

   Route::get('/job', function () {
       ALog::job('SendWelcomeEmail', 'completed', [
           'user_id' => 1,
           'duration' => 1500
       ]);
       return 'Job log tested';
   });

   Route::get('/cache', function () {
       ALog::cache('hit', 'user:123', [
           'ttl' => 3600
       ]);
       return 'Cache log tested';
   });

   Route::get('/request', function () {
       ALog::request('API Request', [
           'endpoint' => '/api/users',
           'params' => ['page' => 1]
       ]);
       return 'Request log tested';
   });

   Route::get('/payment', function () {
       ALog::payment('success', 99.99, 'stripe', [
           'transaction_id' => 'tx_123'
       ]);
       return 'Payment log tested';
   });

   Route::get('/notification', function () {
       ALog::notification('email', 'user@example.com', 'welcome', [
           'template' => 'welcome-email'
       ]);
       return 'Notification log tested';
   });

   Route::get('/file', function () {
       ALog::file('upload', 'images/profile.jpg', [
           'size' => '2.5MB',
           'type' => 'image/jpeg'
       ]);
       return 'File log tested';
   });

   Route::get('/auth', function () {
       ALog::auth('login_success', [
           'remember' => true,
           'device' => 'iPhone 13'
       ]);
       return 'Auth log tested';
   });

   Route::get('/export', function () {
       ALog::export('users', 1000, [
           'format' => 'csv',
           'filters' => ['status' => 'active']
       ]);
       return 'Export log tested';
   });
});
EOT;

            File::put($testRoutesPath, $routesContent);
            $this->info('Test routes published successfully.');
        } catch (\Exception $e) {
            throw new \Exception('Error publishing routes: ' . $e->getMessage());
        }
    }

    private function updateNamespaces($path)
    {
        try {
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
        } catch (\Exception $e) {
            throw new \Exception('Error updating namespaces: ' . $e->getMessage());
        }
    }

    private function updateNamespace($file, $newNamespace)
    {
        try {
            $content = File::get($file);
            $pattern = "/namespace.*?;/";
            $replacement = "namespace " . $newNamespace . ";";
            $content = preg_replace($pattern, $replacement, $content);
            File::put($file, $content);
        } catch (\Exception $e) {
            throw new \Exception('Error updating namespace: ' . $e->getMessage());
        }
    }
}