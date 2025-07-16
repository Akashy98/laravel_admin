<?php

namespace App\Laratables;

use App\Models\User;
use Freshbitsweb\Laratables\Laratables;

class CustomerLaratables
{
        public static function laratablesQueryConditions($query)
    {
        $showDeleted = request()->get('showDeleted', false);
        $query = $query->select('id', 'name', 'phone', 'status', 'created_at', 'profile_image')->where('role_id', 2);

        if ($showDeleted) {
            $query->onlyTrashed();
        }

        return $query->orderBy('id', 'desc');
    }

    public static function laratablesCustomActions($user)
    {
        return view('admin.users.actions', compact('user'))->render();
    }

    public static function laratablesCustomName($user)
    {
        // Use actual profile image if available, otherwise use generated avatar
        if ($user->profile_image) {
            $avatarUrl = $user->profile_image;
        } else {
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=667eea&color=fff";
        }

        return '<div class="d-flex align-items-center">
                    <img src="' . $avatarUrl . '" alt="Avatar" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                    ' . e($user->name) . '
                </div>';
    }

    public static function laratablesCustomStatus($user)
    {
        if ($user->status == 1) {
            return '<span class="badge bg-success">Active</span>';
        } else {
            return '<span class="badge bg-secondary">Inactive</span>';
        }
    }

    public static function laratablesSearchableColumns()
    {
        return ['name', 'phone'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'name', 'phone', 'status', 'created_at'];
    }

    public static function laratablesColumns()
    {
        return ['id', 'name', 'phone', 'status', 'created_at', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['status', 'actions'];
    }
}
