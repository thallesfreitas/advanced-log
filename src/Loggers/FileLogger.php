<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Monolog\Level;

/**
 * Logs file operations and storage activities
 * 
 * @example
 * // Log file upload
 * (new FileLogger('upload', 'images/profile.jpg'))->log([
 *     'file' => $uploadedFile,
 *     'disk' => 's3'
 * ]);
 * 
 * @example
 * // Log file deletion
 * (new FileLogger('delete', 'documents/contract.pdf'))->log([
 *     'reason' => 'user_request',
 *     'backup_created' => true
 * ]);
 */
class FileLogger extends BaseLogger
{
    public function __construct(
        private string $action,
        private string $path,
        private ?string $disk = null
    ) {
        $this->disk = $disk ?? config('filesystems.default');
    }

    public function log(array $context = []): void
    {
        $fileContext = array_merge(
            [
                'action' => $this->action,
                'path' => $this->path,
                'disk' => $this->disk,
                'timestamp' => now()->format('Y-m-d H:i:s.u')
            ],
            $this->getFileDetails($context['file'] ?? null)
        );

        Log::log(
            $this->getLogLevel()->name,
            "File {$this->action}: {$this->path}",
            $this->mergeContext(array_merge($fileContext, $context))
        );
    }

    private function getFileDetails(?UploadedFile $file): array
    {
        if (!$file) {
            return [];
        }

        return [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $this->formatFileSize($file->getSize()),
            'extension' => $file->getClientOriginalExtension()
        ];
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
    }

    private function getLogLevel(): Level
    {
        return match ($this->action) {
            'delete', 'move' => self::WARNING,
            'upload', 'create' => self::INFO,
            'download', 'read' => self::DEBUG,
            default => self::INFO
        };
    }
}