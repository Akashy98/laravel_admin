<?php

namespace App\Services;

use App\Constants\AzureConstants;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AzureStorageService
{
    private $blobClient;
    private $containerName;

    public function __construct()
    {
        $this->containerName = AzureConstants::AZURE_STORAGE_CONTAINER;
        $this->blobClient = BlobRestProxy::createBlobService(AzureConstants::getConnectionString());
    }

    /**
     * Upload a file to Azure Blob Storage
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $customFileName
     * @return array
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file, string $path = '', ?string $customFileName = null): array
    {
        try {
            // Validate file
            $this->validateFile($file);

            // Ensure path starts with astroindia if not already specified
            if (!empty($path) && !str_starts_with($path, 'astroindia/')) {
                $path = 'astroindia/' . $path;
            } elseif (empty($path)) {
                $path = 'astroindia';
            }

            // Generate unique filename
            $fileName = $customFileName ?? $this->generateUniqueFileName($file);
            $blobName = $this->buildBlobPath($path, $fileName);

            // Get file content
            $fileContent = file_get_contents($file->getRealPath());

            // Set blob options
            $options = new CreateBlockBlobOptions();
            $options->setContentType($file->getMimeType());

            // Upload to Azure
            $this->blobClient->createBlockBlob($this->containerName, $blobName, $fileContent, $options);

            // Return file information
            return [
                'success' => true,
                'file_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $blobName,
                'file_url' => $this->getFileUrl($blobName),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ];

        } catch (ServiceException $e) {
            Log::error('Azure Storage Upload Error: ' . $e->getMessage());
            throw new \Exception('Failed to upload file to Azure Storage: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('File Upload Error: ' . $e->getMessage());
            throw new \Exception('Failed to upload file: ' . $e->getMessage());
        }
    }

    /**
     * Upload multiple files to Azure Blob Storage
     *
     * @param array $files
     * @param string $path
     * @return array
     */
    public function uploadMultipleFiles(array $files, string $path = ''): array
    {
        $results = [];
        $errors = [];

        foreach ($files as $file) {
            try {
                $results[] = $this->uploadFile($file, $path);
            } catch (\Exception $e) {
                $errors[] = [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'success' => empty($errors),
            'uploaded_files' => $results,
            'errors' => $errors
        ];
    }

    /**
     * Delete a file from Azure Blob Storage
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        try {
            $options = new DeleteBlobOptions();
            $this->blobClient->deleteBlob($this->containerName, $filePath, $options);
            return true;
        } catch (ServiceException $e) {
            Log::error('Azure Storage Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple files from Azure Blob Storage
     *
     * @param array $filePaths
     * @return array
     */
    public function deleteMultipleFiles(array $filePaths): array
    {
        $results = [];
        $errors = [];

        foreach ($filePaths as $filePath) {
            $success = $this->deleteFile($filePath);
            if ($success) {
                $results[] = $filePath;
            } else {
                $errors[] = $filePath;
            }
        }

        return [
            'success' => empty($errors),
            'deleted_files' => $results,
            'failed_files' => $errors
        ];
    }

    /**
     * Get file URL from Azure Blob Storage
     *
     * @param string $filePath
     * @return string
     */
    public function getFileUrl(string $filePath): string
    {
        return AzureConstants::getContainerUrl() . '/' . $filePath;
    }

    /**
     * Check if file exists in Azure Blob Storage
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists(string $filePath): bool
    {
        try {
            $this->blobClient->getBlobProperties($this->containerName, $filePath);
            return true;
        } catch (ServiceException $e) {
            return false;
        }
    }

    /**
     * Get file properties from Azure Blob Storage
     *
     * @param string $filePath
     * @return array|null
     */
    public function getFileProperties(string $filePath): ?array
    {
        try {
            $properties = $this->blobClient->getBlobProperties($this->containerName, $filePath);
            return [
                'content_length' => $properties->getContentLength(),
                'content_type' => $properties->getContentType(),
                'last_modified' => $properties->getLastModified(),
                'etag' => $properties->getETag(),
            ];
        } catch (ServiceException $e) {
            Log::error('Azure Storage Get Properties Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload image with specific settings
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $customFileName
     * @return array
     */
    public function uploadImage(UploadedFile $file, string $path = AzureConstants::DEFAULT_PROFILE_IMAGE_PATH, ?string $customFileName = null): array
    {
        $this->validateImageFile($file);
        // Ensure path starts with astroindia if not already specified
        if (!str_starts_with($path, 'astroindia/')) {
            $path = 'astroindia/' . $path;
        }
        return $this->uploadFile($file, $path, $customFileName);
    }

    /**
     * Upload video with specific settings
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $customFileName
     * @return array
     */
    public function uploadVideo(UploadedFile $file, string $path = AzureConstants::DEFAULT_VIDEO_PATH, ?string $customFileName = null): array
    {
        $this->validateVideoFile($file);
        // Ensure path starts with astroindia if not already specified
        if (!str_starts_with($path, 'astroindia/')) {
            $path = 'astroindia/' . $path;
        }
        return $this->uploadFile($file, $path, $customFileName);
    }

    /**
     * Upload document with specific settings
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $customFileName
     * @return array
     */
    public function uploadDocument(UploadedFile $file, string $path = AzureConstants::DEFAULT_DOCUMENT_PATH, ?string $customFileName = null): array
    {
        $this->validateDocumentFile($file);
        // Ensure path starts with astroindia if not already specified
        if (!str_starts_with($path, 'astroindia/')) {
            $path = 'astroindia/' . $path;
        }
        return $this->uploadFile($file, $path, $customFileName);
    }

    /**
     * Validate file for upload
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file uploaded');
        }

        if ($file->getSize() > AzureConstants::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds maximum limit of ' . (AzureConstants::MAX_FILE_SIZE / 1024 / 1024) . 'MB');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = AzureConstants::getAllAllowedTypes();

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }
    }

    /**
     * Validate image file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateImageFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = AzureConstants::getAllowedImageTypes();

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('Image type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }
    }

    /**
     * Validate video file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateVideoFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = AzureConstants::getAllowedVideoTypes();

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('Video type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }
    }

    /**
     * Validate document file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateDocumentFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = AzureConstants::getAllowedDocumentTypes();

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('Document type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $randomString = Str::random(10);

        return $timestamp . '_' . $randomString . '.' . $extension;
    }

    /**
     * Build blob path
     *
     * @param string $path
     * @param string $fileName
     * @return string
     */
    private function buildBlobPath(string $path, string $fileName): string
    {
        $path = trim($path, '/');
        return $path ? $path . '/' . $fileName : $fileName;
    }
}
