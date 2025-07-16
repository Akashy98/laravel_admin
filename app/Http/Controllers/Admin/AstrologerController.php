<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AstrologerService;
use App\Models\AstrologerCategory;
use App\Laratables\AstrologerLaratables;
use App\Models\Astrologer;
use App\Models\Service;
use App\Models\User;
use Freshbitsweb\Laratables\Laratables;
use App\Services\AzureStorageService;

class AstrologerController extends Controller
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
        return view('admin.astrologers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Show a form for creating a new astrologer (and user)
        return view('admin.astrologers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'about_me' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);
        // Create user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 3, // 3 for astrologer
            'status' => $request->status ?? 1,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ];
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('profile_image'),
                    'profile-images',
                    'astrologer_profile_' . time()
                );
                if ($result['success']) {
                    $userData['profile_image'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['profile_image' => 'Failed to upload profile image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['profile_image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }
        $user = User::create($userData);
        // Create wallet for user
        $user->wallet()->create(['balance' => 0]);
        // Create astrologer
        $astrologer = $this->service->create([
            'user_id' => $user->id,
            'about_me' => $request->about_me,
            'experience_years' => $request->experience_years,
            'status' => 'pending',
            'is_online' => false,
        ]);
        return redirect()->route('admin.astrologers.show', [$astrologer->id])
            ->with('success', 'Astrologer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $tab = $request->get('tab', 'profile');
        $astrologer = $this->service->find($id, [
            'user', 'skills.category', 'languages', 'availability', 'pricing', 'documents', 'bankDetails', 'reviews.user', 'wallet.transactions' => function($q) { $q->latest()->limit(20); }
        ]);
        $categories = AstrologerCategory::all();

        // Get all active services
        $allServices = Service::where('is_active', true)->get();

        // Get services that already have pricing for this astrologer
        $existingPricingServiceIds = $astrologer->pricing->pluck('service_id')->toArray();

        // Filter out services that already have pricing
        $availableServices = $allServices->whereNotIn('id', $existingPricingServiceIds);

        // Pass both services (for services tab) and availableServices (for pricing tab)
        $services = $allServices;

        return view('admin.astrologers.show', compact('astrologer', 'tab', 'categories', 'availableServices', 'services'));
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
        $astrologer = $this->service->find($id);
        $request->validate([
            'about_me' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'status' => 'nullable|in:pending,approved,rejected,blocked',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);
        $data = $request->only(['about_me', 'experience_years', 'status']);
        $data['is_online'] = $request->has('is_online');
        $data['is_fake'] = $request->has('is_fake');
        $data['is_test'] = $request->has('is_test');
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('profile_image'),
                    'profile-images',
                    'astrologer_' . $astrologer->user_id . '_profile_' . time()
                );
                if ($result['success']) {
                    $astrologer->user->profile_image = $result['file_url'];
                    $astrologer->user->save();
                } else {
                    return redirect()->back()->withInput()->withErrors(['profile_image' => 'Failed to upload profile image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['profile_image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }
        $this->service->update($astrologer, $data);
        return redirect()->route('admin.astrologers.show', [$id, 'tab' => 'profile'])
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Laratables AJAX list endpoint for astrologers.
     */
    public function list(Request $request)
    {
        return Laratables::recordsOf(Astrologer::class, AstrologerLaratables::class);
    }

    public function updateServices(Request $request, $id)
    {
        $astrologer = Astrologer::findOrFail($id);
        $services = Service::all();
        $input = $request->input('services', []);
        $syncData = [];
        foreach ($services as $service) {
            $syncData[$service->id] = [
                'is_enabled' => isset($input[$service->id]) ? 1 : 0
            ];
        }
        $astrologer->services()->sync($syncData);
        return redirect()->route('admin.astrologers.show', [$id, 'tab' => 'services'])
            ->with('success', 'Services updated successfully.');
    }

    public function trashed()
    {
        $showDeleted = true;
        return view('admin.astrologers.index', compact('showDeleted'));
    }

    public function trashedList(Request $request)
    {
        return Laratables::recordsOf(Astrologer::class, AstrologerLaratables::class);
    }

    public function forceDestroy($id)
    {
        $astrologer = Astrologer::withTrashed()->findOrFail($id);
        try {
            $this->service->forceDeleteAstrologer($astrologer);
            return redirect()->back()->with('success', 'Astrologer permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to permanently delete astrologer: ' . $e->getMessage());
        }
    }
}
