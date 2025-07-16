<?php

namespace App\Traits;

trait ExcludesAdminUsers
{
    /**
     * Get users excluding admin users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeAdmins($query)
    {
        return $query->where('role_id', '!=', 1);
    }

    /**
     * Get only admin users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAdmins($query)
    {
        return $query->where('role_id', 1);
    }

    /**
     * Get only regular users (non-admin, non-astrologer)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyRegularUsers($query)
    {
        return $query->where('role_id', 2);
    }

    /**
     * Get only astrologer users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAstrologers($query)
    {
        return $query->where('role_id', 3);
    }
}
