<?php

namespace App\Constants;

class AzureConstants
{
    // Azure Storage Configuration
    const AZURE_STORAGE_ACCOUNT_NAME = 'clientsasset';
    const AZURE_STORAGE_ACCOUNT_KEY = 'JtDr1A27sIRoeSU5Cglw54oaHNduVNGdF6rrgriNc1m/eLzu9iTleMvG1nqyGrIFYIY8p+v//xoS+ASt8E0Eqg==';
    const AZURE_STORAGE_ENDPOINT_SUFFIX = 'core.windows.net';
    const AZURE_STORAGE_CONTAINER = 'assests';

    // Azure Storage URLs
    const AZURE_STORAGE_BASE_URL = 'https://clientsasset.blob.core.windows.net';
    const AZURE_STORAGE_CONTAINER_URL = 'https://clientsasset.blob.core.windows.net/assests';
    const AZURE_STORAGE_ASTROINDIA_URL = 'https://clientsasset.blob.core.windows.net/assests/astroindia';

    // File upload settings
    const MAX_FILE_SIZE = 10485760; // 10MB in bytes
    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const ALLOWED_VIDEO_TYPES = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
    const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'txt', 'rtf'];

    // Default file paths
    const DEFAULT_PROFILE_IMAGE_PATH = 'astroindia/profile-images';
    const DEFAULT_BANNER_IMAGE_PATH = 'astroindia/banners';
    const DEFAULT_DOCUMENT_PATH = 'astroindia/documents';
    const DEFAULT_VIDEO_PATH = 'astroindia/videos';

    /**
     * Get the connection string for Azure Storage
     */
    public static function getConnectionString(): string
    {
        return sprintf(
            'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;EndpointSuffix=%s',
            self::AZURE_STORAGE_ACCOUNT_NAME,
            self::AZURE_STORAGE_ACCOUNT_KEY,
            self::AZURE_STORAGE_ENDPOINT_SUFFIX
        );
    }

    /**
     * Get the container URL
     */
    public static function getContainerUrl(): string
    {
        return self::AZURE_STORAGE_CONTAINER_URL;
    }

    /**
     * Get the astroindia folder URL
     */
    public static function getAstroindiaUrl(): string
    {
        return self::AZURE_STORAGE_ASTROINDIA_URL;
    }

    /**
     * Get the base URL for Azure Storage
     */
    public static function getBaseUrl(): string
    {
        return self::AZURE_STORAGE_BASE_URL;
    }

    /**
     * Get allowed file types for images
     */
    public static function getAllowedImageTypes(): array
    {
        return self::ALLOWED_IMAGE_TYPES;
    }

    /**
     * Get allowed file types for videos
     */
    public static function getAllowedVideoTypes(): array
    {
        return self::ALLOWED_VIDEO_TYPES;
    }

    /**
     * Get allowed file types for documents
     */
    public static function getAllowedDocumentTypes(): array
    {
        return self::ALLOWED_DOCUMENT_TYPES;
    }

    /**
     * Get all allowed file types
     */
    public static function getAllAllowedTypes(): array
    {
        return array_merge(
            self::ALLOWED_IMAGE_TYPES,
            self::ALLOWED_VIDEO_TYPES,
            self::ALLOWED_DOCUMENT_TYPES
        );
    }
}
