<?php

namespace App\Services;

use App\Exceptions\File\FileDeleteException;
use App\Exceptions\File\FileNotFoundException;
use App\Exceptions\File\FileUploadException;
use App\Exceptions\File\FileSizeExceededException;
use App\Exceptions\File\InvalidFileTypeException;
use App\Services\Contracts\FileServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements FileServiceInterface
{
    protected $disk;
    protected $maxFileSize;
    protected $allowedMimeTypes;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
        $this->maxFileSize = config('app.max_file_size', 10 * 1024 * 1024); // 10MB default
        $this->allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];
    }

    /**
     * Upload a file
     */
    public function upload(UploadedFile $file, string $path, array $options = []): string
    {
        try {
            // Validate file
            $this->validate($file, $options);

            // Generate unique filename if needed
            $filename = $options['preserve_name'] ?? false 
                ? $file->getClientOriginalName()
                : $this->generateUniqueFilename($file, $options['prefix'] ?? '');

            // Store the file
            $fullPath = $file->storeAs($path, $filename, $this->disk);

            if (!$fullPath) {
                throw new FileUploadException('Failed to store file');
            }

            return $fullPath;

        } catch (\Exception $e) {
            if ($e instanceof FileUploadException || 
                $e instanceof InvalidFileTypeException || 
                $e instanceof FileSizeExceededException) {
                throw $e;
            }
            
            throw new FileUploadException('File upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a file
     */
    public function delete(string $path): bool
    {
        try {
            if (!$this->exists($path)) {
                throw new FileNotFoundException("File not found: {$path}");
            }

            $deleted = Storage::disk($this->disk)->delete($path);

            if (!$deleted) {
                throw new FileDeleteException("Failed to delete file: {$path}");
            }

            return true;

        } catch (\Exception $e) {
            if ($e instanceof FileNotFoundException || $e instanceof FileDeleteException) {
                throw $e;
            }
            
            throw new FileDeleteException('File deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Replace an existing file with a new one
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $path, array $options = []): string
    {
        try {
            // Upload new file first
            $newFilePath = $this->upload($newFile, $path, $options);

            // Delete old file if it exists
            if ($oldPath && $this->exists($oldPath)) {
                $this->delete($oldPath);
            }

            return $newFilePath;

        } catch (\Exception $e) {
            throw new FileUploadException('File replacement failed: ' . $e->getMessage());
        }
    }

    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get file URL
     */
    public function url(string $path): string
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found: {$path}");
        }

        try {
            $storage = Storage::disk($this->disk);
            
            // Check if the disk supports URL generation
            if (method_exists($storage, 'url')) {
                // Cast to the specific interface that has the url method
                /** @var \Illuminate\Contracts\Filesystem\Cloud|\Illuminate\Filesystem\FilesystemAdapter $storage */
                return call_user_func([$storage, 'url'], $path);
            }
            
            // For local disk or disks without URL method, generate URL manually
            if ($this->disk === 'local' || $this->disk === 'public') {
                return asset("storage/{$path}");
            }
            
            // Fallback to relative path
            return "/storage/{$path}";
            
        } catch (\Exception $e) {
            // If URL generation fails, fallback to a relative path
            return "/storage/{$path}";
        }
    }

    /**
     * Get file size
     */
    public function size(string $path): int
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found: {$path}");
        }

        return Storage::disk($this->disk)->size($path);
    }

    /**
     * Validate file
     */
    public function validate(UploadedFile $file, array $rules = []): bool
    {
        // Check file size
        $maxSize = $rules['max_size'] ?? $this->maxFileSize;
        if ($file->getSize() > $maxSize) {
            throw new FileSizeExceededException(
                "File size ({$file->getSize()} bytes) exceeds maximum allowed size ({$maxSize} bytes)"
            );
        }

        // Check mime type
        $allowedTypes = $rules['allowed_types'] ?? $this->allowedMimeTypes;
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new InvalidFileTypeException(
                "File type '{$file->getMimeType()}' is not allowed. Allowed types: " . implode(', ', $allowedTypes)
            );
        }

        // Check if file is valid
        if (!$file->isValid()) {
            throw new FileUploadException('Invalid file upload');
        }

        return true;
    }

    /**
     * Generate unique filename
     */
    public function generateUniqueFilename(UploadedFile $file, string $prefix = ''): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        $filename = $prefix ? "{$prefix}_{$timestamp}_{$random}" : "{$timestamp}_{$random}";
        
        return $extension ? "{$filename}.{$extension}" : $filename;
    }

    /**
     * Get tenant-specific upload path
     */
    public function getTenantPath(string $tenantUuid, string $folder = ''): string
    {
        $path = "tenants/{$tenantUuid}";
        
        if ($folder) {
            $path .= "/{$folder}";
        }
        
        return $path;
    }

    /**
     * Upload image with specific validation
     */
    public function uploadImage(UploadedFile $file, string $path, array $options = []): string
    {
        $imageOptions = array_merge($options, [
            'allowed_types' => [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ],
            'max_size' => $options['max_size'] ?? 5 * 1024 * 1024 // 5MB for images
        ]);

        return $this->upload($file, $path, $imageOptions);
    }

    /**
     * Set disk for file operations
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Get current disk
     */
    public function getDisk(): string
    {
        return $this->disk;
    }
}