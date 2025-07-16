# Azure Storage Setup and Usage Guide

## Overview

This document provides a comprehensive guide for setting up and using Azure Blob Storage in the Laravel application for file uploads, including images, videos, and documents.

## Components Created

### 1. AzureConstants (`app/Constants/AzureConstants.php`)
- Contains all Azure Storage configuration constants
- Defines allowed file types, sizes, and default paths
- Provides helper methods for connection strings and URLs

### 2. AzureStorageService (`app/Services/AzureStorageService.php`)
- Main service class for Azure Storage operations
- Handles file upload, deletion, and validation
- Supports single and multiple file operations
- Includes specific methods for images, videos, and documents

### 3. FileUploadController (`app/Http/Controllers/Api/FileUploadController.php`)
- API controller for file upload operations
- Provides endpoints for different file types
- Includes validation and error handling



## Installation

### 1. Install Azure Storage SDK

```bash
composer require microsoft/azure-storage-blob
```

### 2. Environment Configuration

Add the following to your `.env` file:

```env
# Azure Storage Configuration
AZURE_STORAGE_ACCOUNT_NAME=clientsasset
AZURE_STORAGE_ACCOUNT_KEY=JtDr1A27sIRoeSU5Cglw54oaHNduVNGdF6rrgriNc1m/eLzu9iTleMvG1nqyGrIFYIY8p+v//xoS+ASt8E0Eqg==
AZURE_STORAGE_ENDPOINT_SUFFIX=core.windows.net
AZURE_STORAGE_CONTAINER=assests
AZURE_STORAGE_BASE_URL=https://clientsasset.blob.core.windows.net
```

## API Endpoints

### File Upload Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/files/upload` | Upload any file |
| POST | `/api/files/upload-multiple` | Upload multiple files |
| POST | `/api/files/upload-profile-image` | Upload profile image |
| POST | `/api/files/upload-banner-image` | Upload banner image |
| POST | `/api/files/upload-video` | Upload video |
| POST | `/api/files/upload-document` | Upload document |

### File Management Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/files/delete` | Delete a file |
| POST | `/api/files/delete-multiple` | Delete multiple files |
| GET | `/api/files/info` | Get file information |
| GET | `/api/files/exists` | Check if file exists |

## Usage Examples

### 1. Using the Service Directly

```php
use App\Services\AzureStorageService;

$azureService = app(AzureStorageService::class);

// Upload a file
$result = $azureService->uploadFile($request->file('file'), 'profile-images');

// Upload an image
$result = $azureService->uploadImage($request->file('image'), 'banners');

// Delete a file
$success = $azureService->deleteFile('profile-images/filename.jpg');
```

### 2. API Usage Examples

#### Upload Profile Image
```bash
curl -X POST /api/files/upload-profile-image \
  -F "image=@profile.jpg" \
  -F "custom_filename=user_profile"
```

#### Upload Multiple Files
```bash
curl -X POST /api/files/upload-multiple \
  -F "files[]=@file1.jpg" \
  -F "files[]=@file2.png" \
  -F "path=documents"
```

#### Delete File
```bash
curl -X POST /api/files/delete \
  -H "Content-Type: application/json" \
  -d '{"file_path": "profile-images/filename.jpg"}'
```

## File Validation

### Allowed File Types

- **Images**: jpg, jpeg, png, gif, webp
- **Videos**: mp4, avi, mov, wmv, flv, webm
- **Documents**: pdf, doc, docx, txt, rtf

### File Size Limits

- Maximum file size: 10MB (configurable in `AzureConstants`)

## Error Handling

The implementation includes comprehensive error handling:

- File validation errors
- Azure Storage connection errors
- File upload failures
- File deletion errors

All errors are logged and returned with appropriate HTTP status codes.

## Security Considerations

1. **File Type Validation**: Only allowed file types can be uploaded
2. **File Size Limits**: Prevents large file uploads
3. **Unique Filenames**: Prevents filename conflicts
4. **Secure URLs**: Files are served through Azure's secure URLs

## Configuration Options

### AzureConstants Configuration

You can modify the following constants in `app/Constants/AzureConstants.php`:

- `MAX_FILE_SIZE`: Maximum file size in bytes
- `ALLOWED_IMAGE_TYPES`: Allowed image file extensions
- `ALLOWED_VIDEO_TYPES`: Allowed video file extensions
- `ALLOWED_DOCUMENT_TYPES`: Allowed document file extensions
- `DEFAULT_PROFILE_IMAGE_PATH`: Default path for profile images
- `DEFAULT_BANNER_IMAGE_PATH`: Default path for banner images
- `DEFAULT_DOCUMENT_PATH`: Default path for documents
- `DEFAULT_VIDEO_PATH`: Default path for videos

## Best Practices

1. **Use the Service**: Use the `AzureStorageService` for all Azure operations
2. **Validate Files**: Always validate files before uploading
3. **Handle Errors**: Implement proper error handling for file operations
4. **Clean Up**: Delete old files when updating or deleting records
5. **Use Appropriate Paths**: Organize files in appropriate folders/paths
6. **Monitor Usage**: Monitor Azure Storage usage and costs

## Troubleshooting

### Common Issues

1. **Connection Errors**: Check Azure credentials and network connectivity
2. **File Upload Failures**: Verify file size and type restrictions
3. **Permission Errors**: Ensure Azure Storage account has proper permissions
4. **URL Access Issues**: Verify container is publicly accessible if needed

### Debugging

Enable logging to debug issues:

```php
Log::debug('Azure Storage operation', [
    'operation' => 'upload',
    'file' => $file->getClientOriginalName(),
    'path' => $path
]);
```

## Performance Considerations

1. **File Size**: Keep files as small as possible
2. **Batch Operations**: Use multiple file upload for better performance
3. **Caching**: Consider caching file URLs for frequently accessed files
4. **CDN**: Use Azure CDN for better global performance

## Cost Optimization

1. **File Lifecycle**: Implement automatic deletion of old files
2. **Storage Tier**: Use appropriate Azure Storage tiers
3. **Compression**: Compress files before upload when possible
4. **Monitoring**: Monitor storage usage and costs regularly 
