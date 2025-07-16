<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Astrologer;
use Illuminate\Support\Facades\Storage;
use App\Laratables\BannerLaratables;
use Freshbitsweb\Laratables\Laratables;
use App\Services\AzureStorageService;

class BannerController extends Controller
{
    protected $azureStorageService;

    public function __construct(AzureStorageService $azureStorageService)
    {
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.banners.index');
    }

    public function list(Request $request)
    {
        return Laratables::recordsOf(Banner::class, BannerLaratables::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $astrologers = Astrologer::with(['user', 'skills.category'])
            ->where('astrologers.status', 'approved')
            ->join('users', 'astrologers.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('astrologers.*')
            ->get();
        return view('admin.banners.create', compact('astrologers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'type' => 'required|in:card,popup',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'show_on' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'astrologer_id' => 'nullable|exists:astrologers,id',
        ]);

        if ($request->hasFile('image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('image'),
                    'banners',
                    'banner_' . time()
                );

                if ($result['success']) {
                    $data['image'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload banner image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
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
    public function edit(Banner $banner)
    {
        $astrologers = Astrologer::with(['user', 'skills.category'])
            ->where('astrologers.status', 'approved')
            ->join('users', 'astrologers.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('astrologers.*')
            ->get();
        return view('admin.banners.edit', compact('banner', 'astrologers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'type' => 'required|in:card,popup',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'show_on' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'astrologer_id' => 'nullable|exists:astrologers,id',
        ]);

        if ($request->hasFile('image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('image'),
                    'banners',
                    'banner_' . $banner->id . '_' . time()
                );

                if ($result['success']) {
                    $data['image'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload banner image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }

        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        // Note: We don't delete from Azure here as it might be used elsewhere
        // If you want to delete from Azure, you can add that logic
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
