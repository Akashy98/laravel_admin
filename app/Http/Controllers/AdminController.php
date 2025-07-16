<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\Banner;
use App\Models\WalletOffer;
use App\Models\AstrologerCategory;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If user is already authenticated and is admin, redirect to dashboard
        if (Auth::check() && Auth::user()->isAdmin() && Auth::user()->isActive()) {
            return redirect('/admin/dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is admin
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ]);
            }

            // Check if user is active
            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $users = User::where('role_id', 2)->count(); // 2 for regular users
        $admins = User::where('role_id', 1)->count(); // 1 for admins
        $astrologers = Astrologer::count();

        // User registrations per month (current year)
        $userActivity = User::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();

        // Unified recent activity (latest 10 from all major tables)
        $recent = collect();
        $recent = $recent->concat(User::select('id', 'name', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'User';
            $item->label = $item->name;
            return $item;
        }));
        $recent = $recent->concat(Astrologer::select('id', 'user_id', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Astrologer';
            $item->label = 'Astrologer #' . $item->id;
            return $item;
        }));
        $recent = $recent->concat(Banner::select('id', 'title', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Banner';
            $item->label = $item->title;
            return $item;
        }));
        $recent = $recent->concat(WalletOffer::select('id', 'amount', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Wallet Offer';
            $item->label = '₹' . $item->amount;
            return $item;
        }));
        $recent = $recent->concat(AstrologerCategory::select('id', 'name', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Category';
            $item->label = $item->name;
            return $item;
        }));
        $recent = $recent->concat(Page::select('id', 'title', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Page';
            $item->label = $item->title;
            return $item;
        }));
        $recent = $recent->concat(Service::select('id', 'name', 'created_at')->latest('created_at')->take(10)->get()->map(function($item) {
            $item->type = 'Service';
            $item->label = $item->name;
            return $item;
        }));
        $recent = $recent->sortByDesc('created_at')->take(10);

        return view('admin.dashboard', compact('users', 'admins', 'astrologers', 'userActivity', 'recent'));
    }

    /**
     * Show example page
     */
    public function example()
    {
        return view('admin.example');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
