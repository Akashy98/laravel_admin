<?php

namespace App\Laratables;

use App\Models\Astrologer;
use Freshbitsweb\Laratables\Laratables;

class AstrologerLaratables
{
        public static function laratablesQueryConditions($query)
    {
        $showDeleted = request()->get('showDeleted', false);
        $query = $query->with('user')->select('id', 'user_id', 'status', 'created_at');

        if ($showDeleted) {
            // Only astrologers whose related user is soft-deleted
            $query = $query->whereHas('user', function($q) {
                $q->onlyTrashed();
            });
        } else {
            // Only astrologers whose related user is NOT soft-deleted
            $query = $query->whereHas('user', function($q) {
                $q->whereNull('deleted_at');
            });
        }
        return $query;
    }



    public static function laratablesCustomActions($astrologer)
    {
        return view('admin.astrologers.actions', compact('astrologer'))->render();
    }

    public static function laratablesCustomStatus($astrologer)
    {
        $status = $astrologer->status ?? 'pending';

        $badgeClasses = [
            'pending' => 'badge bg-warning',
            'approved' => 'badge bg-success',
            'rejected' => 'badge bg-danger',
            'blocked' => 'badge bg-secondary'
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'blocked' => 'Blocked'
        ];

        $badgeClass = $badgeClasses[$status] ?? 'badge badge-secondary';
        $label = $statusLabels[$status] ?? ucfirst($status);

        return '<span class="' . $badgeClass . '">' . $label . '</span>';
    }

    public static function laratablesSearchableColumns()
    {
        return ['id', 'user.name', 'user.email', 'status'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'status'];
    }

    public static function laratablesColumns()
    {
        return ['id', 'user.name', 'user.email', 'user.phone', 'status', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['status', 'actions'];
    }

    public static function laratablesCustomName($astrologer)
    {
        $user = $astrologer->user;
        if ($user && $user->profile_image) {
            $avatarUrl = $user->profile_image;
        } else {
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($user->name ?? '-') . "&background=667eea&color=fff";
        }
        return '<div class="d-flex align-items-center">
                    <img src="' . $avatarUrl . '" alt="Avatar" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                    ' . e($user->name ?? '-') . '
                </div>';
    }
}
