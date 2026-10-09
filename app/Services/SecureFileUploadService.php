<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SecureFileUploadService
{
    /**
     * Allowed MIME types for uploads
     */
    protected array $allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Allowed file extensions
     */
    protected array $allowedExtensions = [
        'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'
    ];

    /**
     * Maximum file size in bytes (10MB)
     */
    protected int $maxFileSize = 10485760;

    /**
     * Dangerous file extensions to block
     */
    protected array $dangerousExtensions = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht',
        'exe', 'dll', 'bat', 'cmd', 'sh', 'bash', 'com',
        'js', 'jar', 'vbs', 'wsf', 'cpl', 'msi', 'scr',
        'sql', 'asp', 'aspx', 'jsp', 'pl', 'py', 'rb'
    ];

    /**
     * Validate and securely upload a file
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $filename
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public function upload(UploadedFile $file, string $directory, ?string $filename = null): array
    {
        // Validate file
        $validation = $this->validateFile($file);
        if (!$validation['success']) {
            return $validation;
        }

        // Generate secure filename
        $secureFilename = $this->generateSecureFilename($file, $filename);

        // Sanitize directory path
        $directory = $this->sanitizeDirectory($directory);

        try {
            // Store file with private visibility
            $path = $file->storeAs(
                $directory,
                $secureFilename,
                ['disk' => 'local', 'visibility' => 'private']
            );

            return [
                'success' => true,
                'path' => $path,
                'filename' => $secureFilename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'File upload failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @return array
     */
    public function validateFile(UploadedFile $file): array
    {
        // Check if file was uploaded
        if (!$file->isValid()) {
            return [
                'success' => false,
                'error' => 'Invalid file upload.',
            ];
        }

        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            return [
                'success' => false,
                'error' => 'File size exceeds maximum allowed size of 10MB.',
            ];
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            return [
                'success' => false,
                'error' => 'File type not allowed. Allowed types: ' . implode(', ', $this->allowedExtensions),
            ];
        }

        // Check for dangerous extensions
        if (in_array($extension, $this->dangerousExtensions)) {
            return [
                'success' => false,
                'error' => 'Dangerous file type detected.',
            ];
        }

        // Validate MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            return [
                'success' => false,
                'error' => 'Invalid file type. MIME type not allowed.',
            ];
        }

        // Additional security: Check if file extension matches MIME type
        if (!$this->validateMimeExtensionMatch($mimeType, $extension)) {
            return [
                'success' => false,
                'error' => 'File extension does not match file content.',
            ];
        }

        // Scan for null bytes (possible file upload attack)
        $originalName = $file->getClientOriginalName();
        if (strpos($originalName, "\0") !== false) {
            return [
                'success' => false,
                'error' => 'Invalid filename detected.',
            ];
        }

        return ['success' => true];
    }

    /**
     * Validate that MIME type matches file extension
     *
     * @param string $mimeType
     * @param string $extension
     * @return bool
     */
    protected function validateMimeExtensionMatch(string $mimeType, string $extension): bool
    {
        $mimeExtensionMap = [
            'application/pdf' => ['pdf'],
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'application/vnd.ms-excel' => ['xls'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        ];

        if (!isset($mimeExtensionMap[$mimeType])) {
            return false;
        }

        return in_array($extension, $mimeExtensionMap[$mimeType]);
    }

    /**
     * Generate secure filename
     *
     * @param UploadedFile $file
     * @param string|null $customName
     * @return string
     */
    protected function generateSecureFilename(UploadedFile $file, ?string $customName = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($customName) {
            // Sanitize custom name
            $customName = $this->sanitizeFilename($customName);
            return $customName . '.' . $extension;
        }

        // Generate random secure filename
        return Str::random(40) . '_' . time() . '.' . $extension;
    }

    /**
     * Sanitize filename to remove dangerous characters
     *
     * @param string $filename
     * @return string
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove file extension if present
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace spaces with underscores
        $filename = str_replace(' ', '_', $filename);
        
        // Remove any character that isn't alphanumeric, underscore, or hyphen
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
        
        // Limit length
        $filename = substr($filename, 0, 50);
        
        // Ensure not empty
        return empty($filename) ? Str::random(10) : $filename;
    }

    /**
     * Sanitize directory path
     *
     * @param string $directory
     * @return string
     */
    protected function sanitizeDirectory(string $directory): string
    {
        // Remove any directory traversal attempts
        $directory = str_replace(['../', '..\\', './'], '', $directory);
        
        // Remove leading/trailing slashes
        return trim($directory, '/\\');
    }

    /**
     * Delete a file securely
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        try {
            return Storage::delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if file exists
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return Storage::exists($path);
    }
}
