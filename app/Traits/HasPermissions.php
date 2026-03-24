<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasPermissions
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')->withPivot('module_id')->withTimestamps();
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
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->hasRoleThroughPermission($permission) || $this->hasDirectPermission($permission);
    }

    protected function hasRoleThroughPermission($permission)
    {
        $action = Action::with('feature.module')->where('slug', $permission)->first();
        if (!$action) return false;

        $moduleId = $action->feature->module_id;

        foreach ($this->roles as $role) {
            // Check scope from pivot
            $scopeModuleId = $role->pivot->module_id;

            // If scope is restricted to a DIFFERENT module, skip this role assignment
            if ($scopeModuleId && $scopeModuleId != $moduleId) {
                continue;
            }

            // Check if user has full access to the module this action belongs to
            $hasFullModuleAccess = $role->modules()
                ->where('module_id', $moduleId)
                ->wherePivot('full_access', true)
                ->exists();

            if ($hasFullModuleAccess) {
                return true;
            }

            // Check for specific action access
            if ($role->actions->contains('slug', $permission)) {
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
