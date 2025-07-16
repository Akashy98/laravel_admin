<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\AzureStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Laratables\CustomerLaratables;
use App\Models\Country;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userService;
    protected $azureStorageService;

    public function __construct(UserService $userService, AzureStorageService $azureStorageService)
    {
        $this->userService = $userService;
        $this->azureStorageService = $azureStorageService;
    }

    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Laratables AJAX list endpoint for customers.
     */
    public function list(Request $request)
    {
        return Laratables::recordsOf(User::class, CustomerLaratables::class);
    }

    public function trashedList(Request $request)
    {
        return Laratables::recordsOf(User::class, CustomerLaratables::class);
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.users.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|integer|in:1,2',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->has('is_admin') ? 1 : 2,
            'gender' => $request->gender,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('profile_image'),
                    'profile-images',
                    'user_profile_' . time()
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

        // Convert form data to arrays for service methods
        $addresses = $request->input('addresses', []);
        $contacts = $this->formatContactsData($request);
        $profile = $this->formatProfileData($request);
        if ($request->filled('gender')) {
            $profile['gender'] = $request->gender;
        }

        try {
            DB::beginTransaction();
            $user = $this->userService->saveUserWithRelatedData(
                $userData,
                $addresses,
                $contacts,
                $profile
            );
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while creating the user: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = User::with(['addresses', 'contacts', 'profile'])->findOrFail($id);
        $countries = Country::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                function($attribute, $value, $fail) use ($user) {
                    if ($value && User::where('email', $value)->where('id', '!=', $user->id)->exists()) {
                        $fail('The email has already been taken.');
                    }
                }
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                function($attribute, $value, $fail) use ($user) {
                    if ($value && User::where('phone', $value)->where('id', '!=', $user->id)->exists()) {
                        $fail('The phone has already been taken.');
                    }
                }
            ],
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|integer|in:1,2',
            'country_code' => 'nullable|string|max:10',
            'gender' => 'nullable|string',
            'status' => 'nullable|integer',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->has('is_admin') ? 1 : 2,
            'gender' => $request->gender,
        ];

        if ($request->password) {
            $userData['password'] = $request->password;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('profile_image'),
                    'profile-images',
                    'user_' . $user->id . '_profile'
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

        // Convert form data to arrays for service methods
        $addresses = $request->input('addresses', []);
        $contacts = $this->formatContactsData($request);
        $profile = $this->formatProfileData($request);
        if ($request->filled('gender')) {
            $profile['gender'] = $request->gender;
        }

        try {
            DB::beginTransaction();
            $this->userService->updateUserWithRelatedData(
                $user,
                $userData,
                $addresses,
                $contacts,
                $profile
            );
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while updating the user: ' . $e->getMessage()]);
        }
    }

    /**
     * Format contacts data from form to array format
     */
    private function formatContactsData(Request $request)
    {
        $contacts = [];

        // Handle array structure for contacts
        $contactsData = $request->input('contacts', []);

        foreach ($contactsData as $contactData) {
            if (!empty($contactData['contact_type'])) {
                $contacts[] = [
                    'contact_type' => $contactData['contact_type'],
                    'contact_name' => $contactData['contact_name'] ?? '',
                    'phone_number' => $contactData['phone_number'] ?? '',
                    'relationship' => $contactData['relationship'] ?? '',
                    'country_code' => $contactData['country_code'] ?? '+91',
                    'is_primary' => true,
                    'is_active' => true,
                ];
            }
        }

        return $contacts;
    }

    /**
     * Format profile data from form to array format
     */
    private function formatProfileData(Request $request)
    {
        $profileData = [];

        $profileFields = [
            'birth_date', 'birth_time', 'birth_place', 'marital_status',
            'marriage_date', 'religion', 'caste', 'gotra', 'nakshatra',
            'rashi', 'about_me'
        ];

        foreach ($profileFields as $field) {
            if ($request->has($field)) {
                $profileData[$field] = $request->input($field);
            }
        }

        return $profileData;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function show($id)
    {
        $user = User::with([
            'profile',
            'addresses',
            'contacts',
            'deviceTokens',
            'wallet.transactions' => function($q) {
                $q->latest()->limit(20);
            }
        ])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User status updated successfully.');
    }

    public function trashed()
    {
        $showDeleted = true;
        return view('admin.users.index', compact('showDeleted'));
    }

    public function forceDestroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        try {
            $this->userService->forceDeleteUser($user);
            return redirect()->back()->with('success', 'User permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to permanently delete user: ' . $e->getMessage());
        }
    }
}
