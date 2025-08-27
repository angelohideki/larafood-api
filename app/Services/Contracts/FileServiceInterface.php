<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    /**
     * Upload a file
     */
    public function upload(UploadedFile $file, string $path, array $options = []): string;

    /**
     * Delete a file
     */
    public function delete(string $path): bool;

    /**
     * Replace an existing file with a new one
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $path, array $options = []): string;

    /**
     * Check if file exists
     */
    public function exists(string $path): bool;

    /**
     * Get file URL
     */
    public function url(string $path): string;

    /**
     * Get file size
     */
    public function size(string $path): int;

    /**
     * Validate file
     */
    public function validate(UploadedFile $file, array $rules = []): bool;

    /**
     * Generate unique filename
     */
    public function generateUniqueFilename(UploadedFile $file, string $prefix = ''): string;
}