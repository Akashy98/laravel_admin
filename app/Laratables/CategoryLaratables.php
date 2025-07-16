<?php

namespace App\Laratables;

use App\Models\AstrologerCategory;

class CategoryLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->select(['id', 'name', 'description', 'is_active'])->orderBy('id', 'desc');
    }

    public static function laratablesCustomStatus($category)
    {
        return $category->is_active
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    public static function laratablesCustomActions($category)
    {
        return view('admin.categories.actions', compact('category'))->render();
    }

    public static function laratablesSearchableColumns()
    {
        return ['name', 'description'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'name', 'description', 'is_active'];
    }

    public static function laratablesColumns()
    {
        return ['name', 'description', 'status', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['status', 'actions'];
    }
}
