<?php

namespace App\Traits;

trait SharedHelpers
{
    /**
     * Process name fields to handle name splitting and combining logic
     *
     * @param array $data
     * @return array
     */
    private function processNameFields(array $data): array
    {
        // If name is provided but first_name and last_name are empty, split the name
        if (!empty($data['name']) && empty($data['first_name']) && empty($data['last_name'])) {
            $nameParts = explode(' ', trim($data['name']), 2);
            $data['first_name'] = $nameParts[0] ?? '';
            $data['last_name'] = $nameParts[1] ?? '';
        }
        // If name is empty but first_name or last_name are provided, combine them
        elseif (empty($data['name']) && (!empty($data['first_name']) || !empty($data['last_name']))) {
            $data['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        }

        return $data;
    }

    /**
     * Convert service slug to service name
     */
    protected function convertServiceSlugToName($slug)
    {
        $serviceMap = [
            'chat' => 'Chat',
            'call' => 'Call',
            'video_call' => 'Video Call',
        ];

        return $serviceMap[strtolower($slug)] ?? null;
    }

    /**
     * Get available service slugs for validation
     */
    protected function getAvailableServiceSlugs()
    {
        return ['chat', 'call', 'video_call'];
    }

    /**
     * Validate service slug and return error response if invalid
     */
    protected function validateServiceSlug($slug)
    {
        $serviceName = $this->convertServiceSlugToName($slug);

        if (!$serviceName) {
            return [
                'valid' => false,
                'error' => 'Invalid service type. Available services: chat, call, video-call'
            ];
        }

        return [
            'valid' => true,
            'service_name' => $serviceName
        ];
    }
}
