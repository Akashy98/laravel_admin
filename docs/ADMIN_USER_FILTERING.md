# Admin User Filtering Solution

This document explains how to exclude admin users from lists and dropdowns in the application.

## Overview

The application now includes a simple and reusable solution to exclude admin users (role_id = 1) from user lists and dropdowns. This is implemented through a trait that provides query scopes.

## Implementation

### 1. Trait: `ExcludesAdminUsers`

Located at: `app/Traits/ExcludesAdminUsers.php`

This trait provides the following query scopes:

- `excludeAdmins()` - Excludes admin users (role_id != 1)
- `onlyAdmins()` - Gets only admin users (role_id = 1)
- `onlyRegularUsers()` - Gets only regular users (role_id = 2)
- `onlyAstrologers()` - Gets only astrologer users (role_id = 3)

### 2. User Model Integration

The `User` model now uses the `ExcludesAdminUsers` trait, making these scopes available on all User queries.

## Usage Examples

### Basic Usage

```php
// Get all users except admins
$users = User::excludeAdmins()->get();

// Get only regular users (customers)
$customers = User::onlyRegularUsers()->get();

// Get only astrologers
$astrologers = User::onlyAstrologers()->get();

// Get only admins
$admins = User::onlyAdmins()->get();
```

### In Controllers

```php
// In a controller method
public function create()
{
    $users = User::excludeAdmins()->get();
    return view('form', compact('users'));
}

public function edit($id)
{
    $users = User::excludeAdmins()->get();
    return view('edit', compact('users'));
}
```

### With Additional Conditions

```php
// Get active users excluding admins
$activeUsers = User::excludeAdmins()->where('status', 1)->get();

// Get users created this month excluding admins
$recentUsers = User::excludeAdmins()
    ->whereMonth('created_at', now()->month)
    ->get();
```

## Updated Controllers

The following controllers have been updated to use this solution:

### AstrologerReviewController

- `create()` method: Now uses `User::excludeAdmins()->get()`
- `edit()` method: Now uses `User::excludeAdmins()->get()`

## Benefits

1. **Consistent**: All parts of the application will exclude admin users by default
2. **Reusable**: The trait can be used in any model that needs this functionality
3. **Flexible**: Multiple scopes available for different use cases
4. **Maintainable**: Single place to modify admin filtering logic
5. **Simple**: Easy to use with existing Laravel query builder

## When to Use Each Scope

- `excludeAdmins()`: When you want all users except admins (most common use case)
- `onlyRegularUsers()`: When you specifically want only customers/users
- `onlyAstrologers()`: When you specifically want only astrologers
- `onlyAdmins()`: When you specifically want only admin users

## Adding to New Controllers

When creating new controllers that need user lists, simply use:

```php
$users = User::excludeAdmins()->get();
```

This ensures admin users are never included in dropdowns or lists unless specifically required. 
