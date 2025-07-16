<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AstrologerDocument;
use Illuminate\Http\Request;
use App\Services\AstrologerService;
use App\Services\AzureStorageService;
use Illuminate\Support\Facades\Log;

class AstrologerDocumentController extends Controller
{
    protected $service;
    protected $azureStorageService;

    public function __construct(AstrologerService $service, AzureStorageService $azureStorageService)
    {
        $this->service = $service;
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $astrologerId)
    {
        try {
            $request->validate([
                'document_type' => 'required|string|max:100',
                'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf',
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $file = $request->file('document_file');

            // Upload to Azure cloud storage
            $result = $this->azureStorageService->uploadDocument($file, 'astrologer_documents');

            if (!$result['success']) {
                throw new \Exception('Failed to upload document to cloud storage');
            }

            // Use the cloud URL instead of local storage URL
            $url = $result['file_url'];

            $this->service->addDocument($astrologerId, $request->document_type, $url, $request->status);

            return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'documents'])
                ->with('success', 'Document uploaded successfully to cloud storage.');

        } catch (\Exception $e) {
            Log::error('Astrologer document upload failed: ' . $e->getMessage(), [
                'astrologer_id' => $astrologerId,
                'document_type' => $request->input('document_type'),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'documents'])
                ->with('error', 'Document upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($astrologerId, $id)
    {
        try {
            // Get the document to get the file URL before deleting
            $document = AstrologerDocument::findOrFail($id);
            $fileUrl = $document->document_url;

            // Delete from database
            $this->service->removeDocument($id);

            // Try to delete from cloud storage if it's an Azure URL
            if (str_contains($fileUrl, 'blob.core.windows.net')) {
                // Extract file path from URL
                $urlParts = parse_url($fileUrl);
                $pathParts = explode('/', trim($urlParts['path'], '/'));

                // Remove container name and get the file path
                array_shift($pathParts); // Remove container name
                $filePath = implode('/', $pathParts);

                // Delete from Azure storage
                $this->azureStorageService->deleteFile($filePath);
            }

            return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'documents'])
                ->with('success', 'Document removed successfully from cloud storage.');

        } catch (\Exception $e) {
            Log::error('Astrologer document deletion failed: ' . $e->getMessage(), [
                'astrologer_id' => $astrologerId,
                'document_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'documents'])
                ->with('error', 'Document deletion failed: ' . $e->getMessage());
        }
    }
}
