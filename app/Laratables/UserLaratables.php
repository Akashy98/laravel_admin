<?php

namespace App\Laratables;

use App\User;
use Freshbitsweb\Laratables\Laratables;

class UserLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public static function laratablesCustomAction($user)
    {
        return view('admin.users.actions', compact('user'))->render();
    }

    public static function laratablesCustomName($user)
    {
        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=667eea&color=fff";
        return '<div class="d-flex align-items-center">
                    <img src="' . $avatarUrl . '" alt="Avatar" class="rounded-circle me-2" width="32">
                    ' . e($user->name) . '
                </div>';
    }

    public static function laratablesCustomIsAdmin($user)
    {
        if ($user->is_admin) {
            return '<span class="badge bg-success">Admin</span>';
        } else {
            return '<span class="badge bg-secondary">User</span>';
        }
    }

    public static function laratablesSearchableColumns()
    {
        return ['name', 'email'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'name', 'email', 'is_admin', 'created_at'];
    }

    public static function laratablesColumns()
    {
        return [
            'id',
            'name',
            'email',
            'is_admin',
            'created_at',
            'action'
        ];
    }
}
