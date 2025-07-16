<?php

namespace App\Http\Controllers\Api;

use App\Services\AzureStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Constants\AzureConstants;

class FileUploadController extends BaseController
{
    protected $azureStorageService;

    public function __construct(AzureStorageService $azureStorageService)
    {
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Upload a single file (image, video, document, etc.)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:' . (AzureConstants::MAX_FILE_SIZE / 1024),
                'path' => 'nullable|string|max:255',
                'custom_filename' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $file = $request->file('file');
            $path = $request->input('path', '');
            $customFilename = $request->input('custom_filename');

            $result = $this->azureStorageService->uploadFile($file, $path, $customFilename);

            return $this->successResponse($result, 'File uploaded successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('File upload failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload multiple files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultipleFiles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files.*' => 'required|file|max:' . (AzureConstants::MAX_FILE_SIZE / 1024),
                'path' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $files = $request->file('files');
            $path = $request->input('path', '');

            $result = $this->azureStorageService->uploadMultipleFiles($files, $path);

            if ($result['success']) {
                return $this->successResponse($result, 'Files uploaded successfully');
            } else {
                return $this->errorResponse('Some files failed to upload', 422, $result);
            }

        } catch (\Exception $e) {
            return $this->errorResponse('File upload failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete a file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_path' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $filePath = $request->input('file_path');
            $success = $this->azureStorageService->deleteFile($filePath);

            if ($success) {
                return $this->successResponse(null, 'File deleted successfully');
            } else {
                return $this->errorResponse('Failed to delete file', 500);
            }

        } catch (\Exception $e) {
            return $this->errorResponse('File deletion failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete multiple files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMultipleFiles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_paths' => 'required|array',
                'file_paths.*' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $filePaths = $request->input('file_paths');
            $result = $this->azureStorageService->deleteMultipleFiles($filePaths);

            if ($result['success']) {
                return $this->successResponse($result, 'Files deleted successfully');
            } else {
                return $this->errorResponse('Some files failed to delete', 422, $result);
            }

        } catch (\Exception $e) {
            return $this->errorResponse('File deletion failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get file information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFileInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_path' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $filePath = $request->input('file_path');
            $properties = $this->azureStorageService->getFileProperties($filePath);

            if ($properties) {
                return $this->successResponse([
                    'file_path' => $filePath,
                    'file_url' => $this->azureStorageService->getFileUrl($filePath),
                    'properties' => $properties,
                    'exists' => true
                ], 'File information retrieved successfully');
            } else {
                return $this->errorResponse('File not found', 404);
            }

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get file information: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Check if file exists
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkFileExists(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_path' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $filePath = $request->input('file_path');
            $exists = $this->azureStorageService->fileExists($filePath);

            return $this->successResponse([
                'file_path' => $filePath,
                'exists' => $exists,
                'file_url' => $exists ? $this->azureStorageService->getFileUrl($filePath) : null
            ], 'File existence checked successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to check file existence: ' . $e->getMessage(), 500);
        }
    }
}
