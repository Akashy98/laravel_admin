<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use App\Laratables\VideoLaratables;
use Freshbitsweb\Laratables\Laratables;
use App\Services\AzureStorageService;

class VideoController extends Controller
{
    protected $azureStorageService;

    public function __construct(AzureStorageService $azureStorageService)
    {
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200', // 50MB
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active'] = $request->has('is_active');
        // Handle video file upload to Azure
        if ($request->hasFile('video_file')) {
            try {
                $result = $this->azureStorageService->uploadFile(
                    $request->file('video_file'),
                    'videos',
                    'video_' . time()
                );
                if ($result['success']) {
                    $data['video_file'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['video_file' => 'Failed to upload video file']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['video_file' => 'Error uploading video: ' . $e->getMessage()]);
            }
        }
        // Handle thumbnail upload to Azure
        if ($request->hasFile('thumbnail')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('thumbnail'),
                    'videos/thumbnails',
                    'thumbnail_' . time()
                );
                if ($result['success']) {
                    $data['thumbnail'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['thumbnail' => 'Failed to upload thumbnail']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['thumbnail' => 'Error uploading thumbnail: ' . $e->getMessage()]);
            }
        }
        Video::create($data);
        return redirect()->route('admin.videos.index')->with('success', 'Video created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_active'] = $request->has('is_active');
        // Handle video file upload to Azure
        if ($request->hasFile('video_file')) {
            try {
                $result = $this->azureStorageService->uploadFile(
                    $request->file('video_file'),
                    'videos',
                    'video_' . $video->id . '_' . time()
                );
                if ($result['success']) {
                    $data['video_file'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['video_file' => 'Failed to upload video file']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['video_file' => 'Error uploading video: ' . $e->getMessage()]);
            }
        }
        // Handle thumbnail upload to Azure
        if ($request->hasFile('thumbnail')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('thumbnail'),
                    'videos/thumbnails',
                    'thumbnail_' . $video->id . '_' . time()
                );
                if ($result['success']) {
                    $data['thumbnail'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['thumbnail' => 'Failed to upload thumbnail']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['thumbnail' => 'Error uploading thumbnail: ' . $e->getMessage()]);
            }
        }
        $video->update($data);
        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        if ($video->video_file) {
            Storage::disk('public')->delete($video->video_file);
        }
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }

    /**
     * DataTables AJAX list endpoint for videos.
     */
    public function list(Request $request)
    {
        return Laratables::recordsOf(Video::class, VideoLaratables::class);
    }
}
