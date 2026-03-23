<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasPermissions
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    public function hasPermission($permission)
    {
        return $this->hasRoleThroughPermission($permission) || $this->hasDirectPermission($permission);
    }

    protected function hasRoleThroughPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;
    }

    protected function hasDirectPermission($permission)
    {
        // For now, we only support permissions through roles as per requirements
        return false;
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }
}
